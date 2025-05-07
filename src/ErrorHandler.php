<?php

namespace Adwiv\Laravel\ErrorMailer;

use Illuminate\Support\Facades\Log;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Level;

class ErrorHandler extends AbstractProcessingHandler
{
    public function __construct($level = Level::Error, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            $url = url()->current();
            $inputs = request()->input();
            $message = $record['message'];
            $message = substr($message, 0, 255);
            $content = (string) $record['formatted'];
            if (!$this->isHtmlBody($content)) {
                $content = "<pre style=\"font-family: inherit\">$content</pre>";
            }
    
            $repeatSeconds = config('error-mailer.repeat_after', 300);
            $hourlyLimit = config('error-mailer.hourly_limit', 10);
    
            if ($lastLog = ErrorLog::withRecentMessage($message, $repeatSeconds)) {
                $lastLog->increment('repeats');
            } elseif (ErrorLog::recentCount(3600) < $hourlyLimit) {
                ErrorLog::createLog($message, $content, $url, $inputs)->reportByMail();
            } else {
                ErrorLog::createLog($message, $content, $url, $inputs);
            }
        } catch (\Exception $e) {
            Log::error('Error creating error log: ' . $e->getMessage());
        }
    }

    protected function isHtmlBody(string $body): bool
    {
        return ($body[0] ?? null) === '<';
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new HtmlFormatter();
    }
}

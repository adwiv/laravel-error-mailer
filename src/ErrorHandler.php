<?php

namespace Adwiv\Laravel\ErrorMailer;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class ErrorHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::ERROR, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $url = url()->current();
        $inputs = request()->input();
        $message = $record['message'];
        $message = substr($message, 0, 255);
        $content = (string)($record['formatted']);
        if (!$this->isHtmlBody($content)) {
            $content = "<pre style=\"font-family: inherit\">$content</pre>";
        }

        $repeatSeconds = env('ERROR_MAILER_REPEAT_AFTER', 300);
        $hourlyLimit = env('ERROR_MAILER_HOURLY_LIMIT', 10);

        if ($lastLog = ErrorLog::withRecentMessage($message, $repeatSeconds)) {
            $lastLog->increment('repeats');
        } else if (ErrorLog::recentCount(3600) < $hourlyLimit) {
            ErrorLog::createLog($message, $content, $url, $inputs)->reportByMail();
        } else {
            ErrorLog::createLog($message, $content, $url, $inputs);
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

<?php

namespace Adwiv\Laravel\ErrorMailer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorReportingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $inputs;
    public $message;
    public $content;

    public function __construct($message, $content, $url, $inputs)
    {
        $this->url = $url;
        $this->inputs = $inputs;
        $this->message = $message;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reportTo = config('error-mailer.report_to');
        $reportFrom = config('error-mailer.mail_from');

        if ($reportTo && $reportFrom) {
            $mailable = new ErrorMail($this->message, $this->content, $this->url, $this->inputs);
            Mail::send($mailable);
        } else if (!$reportTo) {
            Log::info("ERROR_MAILER_TO environment variable is not set");
        } else {
            Log::info("ERROR_MAILER_FROM or MAIL_FROM_ADDRESS environment variable is not set");
        }
    }
}

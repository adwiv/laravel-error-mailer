<?php

namespace Adwiv\Laravel\ErrorMailer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $inputs;
    public $subject;
    public $content;

    public function __construct($message, $content, $url, $inputs)
    {
        $this->url = $url;
        $this->inputs = $inputs;
        $this->subject = $message;
        $this->content = $content;
    }

    /**
     * Build the mailable.
     *
     * @return $this|null
     */
    public function build(): ErrorMail
    {
        $mailFrom = config('error-mailer.mail_from');
        $reportTo = config('error-mailer.report_to');
        $reportTo = explode(',', $reportTo);
        $reportTo = array_map('trim', $reportTo);

        return $this->view('error-mailer::error-mail')
            ->from($mailFrom)
            ->to($reportTo)
            ->subject($this->subject)
            ->with([
                'url' => $this->url,
                'inputs' => $this->inputs,
                'content' => $this->content,
            ]);
    }
}

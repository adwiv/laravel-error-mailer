<?php

return [
    'error-mailer' => [
        'driver' => 'monolog',
        'handler' => Adwiv\Laravel\ErrorMailer\ErrorHandler::class,
        'formatter' => Monolog\Formatter\HtmlFormatter::class,
        'level' => 'error',
    ],
];

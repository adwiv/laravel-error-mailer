<?php

return [
    'from' => env('ERROR_MAILER_FROM', env('MAIL_FROM_ADDRESS')),
    'to' => env('ERROR_MAILER_TO'),
    'repeat_after' => env('ERROR_MAILER_REPEAT_AFTER'),
    'hourly_limit' => env('ERROR_MAILER_HOURLY_LIMIT'),
];

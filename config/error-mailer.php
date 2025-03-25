<?php

return [
    'mail_from' => env('ERROR_MAILER_FROM', env('MAIL_FROM_ADDRESS')),
    'report_to' => env('ERROR_MAILER_TO'),
    'repeat_after' => env('ERROR_MAILER_REPEAT_AFTER', 300),
    'hourly_limit' => env('ERROR_MAILER_HOURLY_LIMIT', 10),
];

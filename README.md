# Laravel ErrorLog Mailer
Package to report laravel errors by email with throttling and deduplication.

## Installation

1. Require this package with composer using the following command:

```bash
composer require adwiv/laravel-error-mailer
```

2. Run the migration to create the `error_logs` table.

```bash
php artisan migrate
```

3. Add the following keys to .env file

```bash
# Required Key
ERROR_MAILER_TO = "hello@example.com,admin@example.com" # Comma separated list of email addresses
```
```bash
# Optional Keys
ERROR_MAILER_FROM = "errors@example.com" # Mail sender, if not defined defaults to config setting
ERROR_MAILER_REPEAT_AFTER = 300   # Time in seconds for which same error will not be reported again
ERROR_MAILER_HOURLY_LIMIT = 10    # Maximum number of error mails that will be sent in an hour
```

4. Configure `config/logging.php`. The package automatically adds a logging channel named `error-mailer`. 

The simplest way to use it is to add it the `stack` channel.

```php
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'error-mailer'], #<-- Add here
            'ignore_exceptions' => false,
        ],
        ...
     ],
```

Thats all. Any errors should now be reported to all the email addresses listed in `ERROR_MAILER_TO` environment option.

## Troubleshooting
If mails are not being recieived, please check:

1. Mail sending is configured correctly and you are able to send other mails without any issues.
2. The `ERROR_MAILER_TO` variable is defined in .env file and has valid email addresses (without spaces)
3. The mail queue is being processed correctly
4. Check the laravel.log for any reported issues.

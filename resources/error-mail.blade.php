<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Error Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body style="font-family:serif; padding: 5px;">
  <p>Url: {{ $url }}</p>
  @isset($inputs)
    <p>Request: {{ json_encode($inputs) }}</p>
  @endisset
  {!! $content !!}
  <br><br>
  <p>
    Regards, <br>Error Mailer Daemon<br>
    <small>{{ now()->toDateTimeString() }}</small>
  </p>
</body>

</html>

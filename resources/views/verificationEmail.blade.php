<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ $mailData["title"] }}</title>
    </head>
    <body>
        <h3>Hello</h3>
        <p>{{ $mailData["body"] }}</p>
        <a href="{{ $mailData['url'] }}">Click Her To Verify Email</a>
        <p>Thank you</p>
    </body>
</html>

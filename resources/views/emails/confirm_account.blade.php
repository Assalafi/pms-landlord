@php
    $confirmation_link = config('app.url').'/confirm-email/'.$id;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">Confirm Your Email on {{ config('app.name') }}</h2>

        <p>Hello {{ $landlord }},</p>

        <p>Thank you for signing up for {{ config('app.name') }}! To get started, we need you to confirm your email address. This step helps us ensure your account's security.</p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $confirmation_link }}"
               style="display: inline-block; background-color: #4CAF50; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
               Confirm Email
            </a>
        </div>

        <p>If the button above doesn’t work, copy and paste the following link into your browser:</p>
        <div style="padding: 10px; background-color: #f9f9f9; border-left: 4px solid #5D87FF; word-wrap: break-word;">
            <a href="{{ $confirmation_link }}" style="color: #4CAF50; text-decoration: none;">{{ $confirmation_link }}</a>
        </div>

        <p>If you did not create an account on {{ config('app.name') }}, please ignore this email.</p>

        <p>Thank you for joining {{ config('app.name') }}!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>
</body>
</html>

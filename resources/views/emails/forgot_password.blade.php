@php
    $reset_link = config('app.url').'/reset-password/'.$token;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">Reset Your Password on {{ config('app.name') }}</h2>

        <p>Hello {{ $user_name }},</p>

        <p>We received a request to reset your password for your {{ config('app.name') }} account. To proceed with the password reset, please click the button below:</p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $reset_link }}"
               style="display: inline-block; background-color: #4CAF50; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
               Reset Password
            </a>
        </div>

        <p>If the button above doesn't work, copy and paste the following link into your browser:</p>
        <div style="padding: 10px; background-color: #f9f9f9; border-left: 4px solid #5D87FF; word-wrap: break-word;">
            <a href="{{ $reset_link }}" style="color: #4CAF50; text-decoration: none;">{{ $reset_link }}</a>
        </div>

        <p><strong>Note:</strong> This password reset link will expire in 60 minutes for security reasons.</p>

        <p>If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>

        <p>Thank you for using {{ config('app.name') }}!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>
</body>
</html>

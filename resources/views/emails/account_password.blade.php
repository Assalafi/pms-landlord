<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Account Created</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">Your New Account on {{ config('app.name') }}</h2>

        <p>Hello {{ $tenant }},</p>

        <p>We’re excited to welcome you to {{ config('app.name') }}! You’ve been added as a tenant to a property managed by {{ $landlord }}, and an account has been created for you on our platform.</p>

        <div style="padding: 10px; background-color: #f9f9f9; border-left: 4px solid #5D87FF; margin: 20px 0;">
            <p> {{ $unit }}, {{ $property }}</p>
            <p>{{ $address }}</p>
        </div>

        <p>Please use the following temporary password to log in for the first time. Once logged in, we recommend updating your password to secure your account.</p>

        <div style="padding: 10px; background-color: #f0f8ff; border-left: 4px solid #5D87FF; margin: 20px 0;">
            <p><strong>Temporary Password:</strong> {{ $password }}</p>
        </div>

        <p>You can log in here: <a href="https://tenant.sublimerent.com.ng" style="color: #4CAF50; text-decoration: none;">Tenant Dashboard</a></p>

        <p>Thank you for joining {{ config('app.name') }}!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>

</body>
</html>

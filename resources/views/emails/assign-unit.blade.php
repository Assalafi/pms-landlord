<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Added Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">You've Been Added to a New Property Unit</h2>

        <p>Hello {{ $tenant }},</p>

        <p>We’re writing to let you know that you have been added as a tenant to a property managed by {{ $landlord }} on {{ config('app.name') }}.</p>

        <div style="padding: 10px; background-color: #f9f9f9; border-left: 4px solid #5D87FF; margin: 20px 0;">
            <p>{{ $unit }}, {{ $property }}</p>
            <p>{{ $address }}</p>
        </div>

        <p>You can log in to your account to view more details or contact your landlord directly via the platform.</p>

        <p>Thank you for using {{ config('app.name') }}!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request Update</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">Service Request Update</h2>

        <p>Hello {{ $tenant }},</p>

        <p>We wanted to let you know that there’s been an update to your service request ({{ $ref }}) on {{ config('app.name') }}.</p>

        <p>You can log in to your account on {{ config('app.name') }} to view the details or contact us if you have any questions.</p>

        <p>Thank you for using {{ config('app.name') }}!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>

</body>
</html>

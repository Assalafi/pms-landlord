<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #333333;">Payment Confirmation and Receipt</h2>

        <p>Hello {{ $tenant }},</p>

        <p>We wanted to let you know that a payment has been recorded for your account on {{ config('app.name') }} by your landlord, {{ $landlord }}.</p>

        <div style="padding: 10px; background-color: #f9f9f9; border-left: 4px solid #5D87FF; margin: 20px 0;">
            <p><strong>Payment Details:</strong></p>
            <p>Property Address: {{ $property }}, {{ $address }}</p>
            <p>Unit: {{ $unit }}</p>
            <p>Amount Paid: N{{ number_format($amount, 2) }}</p>
            <p>Date of Payment: {{ date('d M, Y', strtotime($date)) }}</p>
            <p>Payment Method: {{ $payment }}</p>
        </div>

        <p>Please find the attached receipt for your records <a href="https://tenant.sublimerent.com.ng/print-receipt/{{ $ref }}">here</a>.</p>

        <p>Thank you for staying with us!</p>

        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <p>Hello {{ $userName }},</p>
    <p>You requested a password reset for your Pioneers Edu panel account.</p>
    <p style="font-size: 28px; font-weight: bold; letter-spacing: 4px; margin: 24px 0;">{{ $otp }}</p>
    <p>This code expires in 15 minutes. If you did not request a reset, you can ignore this email.</p>
    <p>— Pioneers Edu</p>
</body>
</html>

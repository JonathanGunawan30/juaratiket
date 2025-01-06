<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin: 0; padding: 20px; background-color: #f9fafb; font-family: system-ui, -apple-system, sans-serif;">
<!-- Main Container -->
<div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    <!-- Header -->
    <h1 style="color: #111827; margin: 0 0 24px 0; font-size: 28px; font-weight: 700;">
        Welcome Aboard, {{ $booking->name }}! 🎉
    </h1>

    <!-- Introduction -->
    <p style="color: #4b5563; line-height: 1.6; margin: 0 0 32px 0; font-size: 16px;">
        Thank you for choosing <strong>{{ config('app.name') }}</strong> for your travel arrangements. We're currently processing your payment and will update you shortly.
    </p>

    <!-- Booking Details Box -->
    <div style="background-color: #f3f4f6; padding: 24px; border-radius: 12px; margin-bottom: 32px;">
        <h2 style="color: #111827; margin: 0 0 16px 0; font-size: 20px; font-weight: 600;">
            Booking Details
        </h2>
        <div style="display: flex; align-items: center;">
            <span style="color: #4b5563; font-weight: 500;">Transaction ID:</span>
            <span style="background: #e5e7eb; padding: 4px 12px; border-radius: 6px; margin-left: 8px; font-family: monospace; color: #111827;">
                    {{ $booking->booking_trx_id }}
                </span>
        </div>
    </div>

    <!-- Action Button -->
    <div style="text-align: center; margin-bottom: 32px;">
        <a href="{{ route('front.check_booking') }}" style="display: inline-block; padding: 14px 32px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; font-size: 16px;">
            Track My Booking
        </a>
    </div>

    <!-- Support Info -->
    <div style="border-top: 1px solid #e5e7eb; padding-top: 24px;">
        <p style="color: #6b7280; font-size: 15px; line-height: 1.6; margin: 0 0 24px 0;">
            Need assistance? Our customer support team is here to help! Contact us through our support channels.
        </p>

        <p style="color: #111827; margin: 0; font-size: 15px;">
            Best regards,<br>
            <strong style="color: #2563eb;">{{ config('app.name') }} Team</strong>
        </p>
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 40px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
        <p style="color: #9ca3af; font-size: 13px; margin: 0;">
            This is an automated message. Please do not reply directly to this email.
        </p>
    </div>
</div>
</body>
</html>

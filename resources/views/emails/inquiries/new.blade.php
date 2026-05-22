<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Inquiry — PropTrack</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #374151; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .header { background: #4f46e5; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .header p { color: #c7d2fe; margin: 6px 0 0; font-size: 14px; }
        .body { padding: 36px 40px; }
        .label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 15px; color: #111827; margin-bottom: 20px; }
        .message-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; font-size: 15px; line-height: 1.6; color: #374151; white-space: pre-wrap; }
        .property-box { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 8px; padding: 16px 20px; margin-bottom: 28px; }
        .property-box .prop-title { font-weight: 600; font-size: 16px; color: #3730a3; }
        .property-box .prop-city  { font-size: 13px; color: #6366f1; margin-top: 2px; }
        .btn { display: inline-block; margin-top: 28px; padding: 12px 28px; background: #4f46e5; color: #ffffff; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; }
        .footer { padding: 20px 40px; border-top: 1px solid #f3f4f6; text-align: center; font-size: 12px; color: #9ca3af; }
        hr { border: none; border-top: 1px solid #f3f4f6; margin: 24px 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>New Inquiry Received</h1>
        <p>Someone is interested in your listing</p>
    </div>

    <div class="body">
        {{-- Property --}}
        <div class="property-box">
            <div class="label">Property</div>
            <div class="prop-title">{{ $inquiry->property->title }}</div>
            <div class="prop-city">{{ $inquiry->property->city }}</div>
        </div>

        {{-- Inquirer details --}}
        <div class="label">From</div>
        <div class="value">{{ $inquiry->name }}</div>

        <div class="label">Email</div>
        <div class="value"><a href="mailto:{{ $inquiry->email }}" style="color:#4f46e5;">{{ $inquiry->email }}</a></div>

        @if($inquiry->phone)
            <div class="label">Phone</div>
            <div class="value">{{ $inquiry->phone }}</div>
        @endif

        <hr>

        <div class="label">Message</div>
        <div class="message-box">{{ $inquiry->message }}</div>

        <a href="{{ route('inquiries.show', $inquiry->id) }}" class="btn">View Inquiry in Dashboard</a>
    </div>

    <div class="footer">
        This email was sent by <strong>PropTrack</strong>. Do not reply to this email.
    </div>
</div>
</body>
</html>

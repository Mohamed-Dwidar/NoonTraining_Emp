<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $msg_subject }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #37BC9B;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #37BC9B;
            font-size: 20px;
        }
        .email-body p {
            margin: 15px 0;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            margin: 20px 0;
            background-color: #37BC9B;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background-color: #2fa885;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #777777;
            font-size: 14px;
        }
        .contract-info {
            background-color: #f9f9f9;
            border-right: 4px solid #37BC9B;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; direction: rtl; text-align: right; background-color: #f4f4f4; font-family: 'Arial', sans-serif;">
    <div class="email-container" dir="rtl" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); direction: rtl; text-align: right;">
        <div class="email-header">
            <h1 style="margin: 0; font-size: 24px;">{{ $msg_subject }}</h1>
        </div>

        <div class="email-body">
            <p style="margin: 15px 0; font-size: 16px;">{{ $msg_body }}</p>
        </div>

        <div class="email-footer">
            <p style="margin: 15px 0; font-size: 16px;">{{ $msg_footer }}</p>
        </div>
    </div>
</body>
</html>

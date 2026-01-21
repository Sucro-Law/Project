<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-text {
            font-size: 18px;
            color: #800000;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #800000;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #800000;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 25px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            opacity: 0.9;
        }
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .security-warning {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .expiry-notice {
            background: #ffebee;
            border: 1px solid #ef5350;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .link-fallback {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            word-break: break-all;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>

        <div class="content">
            <p class="welcome-text">Hello {{ $user->full_name }},</p>

            <p>We received a request to reset the password for your PUP Student Organization Management account.</p>

            <div class="info-box">
                <h3>Reset Your Password</h3>
                <p>Click the button below to create a new password. If you didn't request this, you can safely ignore this email.</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </p>

            <div class="link-fallback">
                <strong>If the button doesn't work, copy and paste this link into your browser:</strong><br>
                {{ $resetUrl }}
            </div>

            <div class="expiry-notice">
                <strong>This link will expire in {{ $expiryMinutes }} minutes.</strong>
            </div>

            <div class="security-warning">
                <p style="margin: 0;"><strong>Security Tips:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Never share this link with anyone</li>
                    <li>Choose a strong, unique password</li>
                    <li>If you didn't request this reset, please secure your email account</li>
                </ul>
            </div>

            <div class="important-note">
                <p style="margin: 0;"><strong>Didn't request this?</strong><br>
                If you didn't request a password reset, please ignore this email. Your password will remain unchanged and the reset link will expire automatically.</p>
            </div>

            <p>If you have any questions or need assistance, please contact our support team.</p>

            <p>Best regards,<br>
            <strong>PUP Student Organization Management Team</strong></p>
        </div>

        <div class="footer">
            <p>Polytechnic University of the Philippines<br>
            Student Organization Management System</p>
            <p style="margin-top: 10px;">This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

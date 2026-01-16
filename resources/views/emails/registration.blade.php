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
        .credentials-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .credential-item {
            background: white;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #800000;
        }
        .credential-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 18px;
            font-weight: bold;
            color: #800000;
            letter-spacing: 1px;
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
            padding: 12px 30px;
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .important-note {
            background: #ffebee;
            border: 1px solid #ef5350;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎓 Welcome to PUP Student Organization Management</h1>
        </div>
        
        <div class="content">
            <p class="welcome-text">Hello {{ $user->full_name }},</p>
            
            <p>Thank you for registering with the Polytechnic University of the Philippines Student Organization Management System!</p>
            
            <div class="info-box">
                <h3>Your Account Details</h3>
                <p><strong>Full Name:</strong> {{ $user->full_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Account Type:</strong> {{ $accountType }}</p>
            </div>
            
            <div class="important-note">
                <p style="margin: 0;"><strong>⚠️ IMPORTANT:</strong> Please save your login credentials securely. This is the only time your password will be sent to you.</p>
            </div>
            
            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #800000; text-align: center;">Your Login Credentials</h3>
                
                <div class="credential-item">
                    <div class="credential-label">User ID</div>
                    <div class="credential-value">{{ $userId }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">{{ $accountType }} Number</div>
                    <div class="credential-value">{{ $schoolId }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">{{ $plainPassword }}</div>
                </div>
            </div>

            <div class="security-warning">
                <p style="margin: 0;"><strong>🔒 Security Reminder:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Please change your password after your first login</li>
                    <li>Never share your password with anyone</li>
                    <li>Use your {{ $accountType }} Number ({{ $schoolId }}) or email to login</li>
                </ul>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
            </p>
            
            <div class="info-box">
                <h3>📝 What's Next?</h3>
                <ul style="padding-left: 20px;">
                    <li>Use your {{ $accountType }} Number or email and password to log in</li>
                    <li>Complete your profile setup</li>
                    <li>Explore available student organizations</li>
                    <li>Start managing your memberships</li>
                </ul>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
            
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
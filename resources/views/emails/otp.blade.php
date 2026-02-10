<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f7fa;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .header {
            background: linear-gradient(135deg, #004F68 0%, #006a8a 100%);
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border-radius: 20px;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
        
        .logo {
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
        }
        
        .header-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message {
            font-size: 15px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .otp-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #004F68;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .otp-label {
            font-size: 14px;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            color: #004F68;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0, 79, 104, 0.1);
        }
        
        .otp-expiry {
            font-size: 13px;
            color: #999999;
            margin-top: 15px;
        }
        
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        
        .security-tips {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
        }
        
        .security-tips h3 {
            font-size: 16px;
            color: #004F68;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-tips ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .security-tips li {
            padding: 8px 0;
            font-size: 14px;
            color: #666666;
            position: relative;
            padding-left: 25px;
        }
        
        .security-tips li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #999999;
        }
        
        .footer a {
            color: #004F68;
            text-decoration: none;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 30px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }
            
            .header-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo-container">
                <img src="https://limegreen-lemur-724209.hostingersite.com/images/logo.png" alt="Logo" class="logo">
            </div>
            <h1 class="header-title">Two-Factor Authentication</h1>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <p class="greeting">Hello,</p>
            
            <p class="message">
                We received a login request for your account. To complete your login, please use the verification code below:
            </p>
            
            <!-- OTP Code Box -->
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ substr($otp, 0, 3) }} {{ substr($otp, 3, 3) }}</div>
                <div class="otp-expiry">⏱️ This code expires in {{ $expiryMinutes }} minutes</div>
            </div>
            
            
            
            <div class="divider"></div>
            
            <!-- Security Tips -->
           
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>IQC Sense</strong></p>
            <p>This is an automated message, please do not reply to this email.</p>
            <p style="margin-top: 15px; color: #666666;">
                © {{date('Y')}} IQC Sense. All rights reserved
            </p>
        </div>
    </div>
</body>
</html>

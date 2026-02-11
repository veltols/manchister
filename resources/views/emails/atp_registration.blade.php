<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .header { background: #0f172a; padding: 40px; text-align: center; }
        .logo { color: white; font-size: 24px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
        .content { padding: 40px; color: #334155; line-height: 1.6; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; color: #64748b; font-size: 12px; }
        .btn { display: inline-block; padding: 14px 28px; background: #4f46e5; color: white; text-decoration: none; border-radius: 12px; font-weight: bold; margin-top: 20px; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
        h1 { color: #0f172a; margin-bottom: 24px; font-weight: 900; font-size: 24px; }
        p { margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">MANCHESTER</div>
        </div>
        <div class="content">
            <h1>Welcome to the Network</h1>
            <p>Hello <strong>{{ $atp->contact_name }}</strong>,</p>
            <p>You have been registered as an <strong>Authorized Training Provider (ATP)</strong> for Manchester Training Center.</p>
            <p>Your unique reference ID: <strong>{{ $atp->atp_ref }}</strong></p>
            <p>You can now log in to the portal and complete your accreditation profile.</p>
            <a href="{{ url('/login') }}" class="btn">Access Portal</a>
            <p style="margin-top: 40px; font-size: 11px; color: #94a3b8;">If you did not expect this invitation, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Manchester Training Center. All rights reserved.
        </div>
    </div>
</body>
</html>

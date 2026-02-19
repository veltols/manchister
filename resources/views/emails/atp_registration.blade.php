<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 150px; height: auto; }
        .box { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table>
                <tr>
                    <td style="text-align: center; padding-bottom: 20px;">
                        @php
                            $logoPath = \App\Models\AppSetting::where('key', 'logo_path')->value('value');
                            $logoUrl = $logoPath ? asset('uploads/' . $logoPath) : asset('images/logo.png');
                        @endphp
                        <img src="{{ $logoUrl }}" alt="IQC Logo" style="width: 120px; height: auto;">
                    </td>
                </tr>
            </table>
        </div>
        <div class="content">
            <h1>Welcome to the Network</h1>
            <p>Hello <strong>{{ $atp->contact_name }}</strong>,</p>
            <p>You have been registered as an <strong>Authorized Training Provider (ATP)</strong> for IQC Sense.</p>
            <p>Your unique reference ID: <strong>{{ $atp->atp_ref }}</strong></p>
            <p>You can now log in to the portal and complete your accreditation profile.</p>
            <a href="{{ url('/login') }}" class="btn">Access Portal</a>
            <p style="margin-top: 40px; font-size: 11px; color: #94a3b8;">If you did not expect this invitation, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} IQC Sense. All rights reserved.
        </div>
    </div>
</body>
</html>

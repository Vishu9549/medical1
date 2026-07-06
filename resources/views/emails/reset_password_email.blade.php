<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
    <tr>
      <td align="center" style="padding: 40px 0;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
          <!-- Header -->
          <tr>
            <td align="center" style="background: linear-gradient(135deg, #1A3C8F, #2563EB); padding: 30px;">
              <h1 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0;">Dawalo 💊</h1>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding: 40px 30px;">
              <h2 style="color: #1A1A1A; font-size: 18px; font-weight: 700; margin-top: 0; margin-bottom: 16px;">Hello!</h2>
              <p style="color: #4A5568; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                You are receiving this email because we received a password reset request for your account on Dawalo.
              </p>
              
              <div align="center" style="margin-bottom: 30px;">
                <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #1A3C8F; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 10px rgba(26,60,143,0.2);">
                  Reset Password
                </a>
              </div>

              <p style="color: #4A5568; font-size: 14px; line-height: 1.6; margin-bottom: 24px;">
                This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required.
              </p>

              <hr style="border: none; border-top: 1px dashed #e2e8f0; margin-bottom: 20px;">

              <p style="color: #718096; font-size: 12px; line-height: 1.5; margin: 0;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
              </p>
              <p style="color: #2563EB; font-size: 12px; word-break: break-all; line-height: 1.5; margin-top: 8px;">
                <a href="{{ $resetUrl }}" style="color: #2563EB; text-decoration: underline;">{{ $resetUrl }}</a>
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td align="center" style="background-color: #f7fafc; padding: 24px; border-top: 1px solid #edf2f7; color: #718096; font-size: 12px;">
              &copy; {{ date('Y') }} Dawalo. All rights reserved.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>

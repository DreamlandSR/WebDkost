<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP Verifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Halo,</h2>
        <p>Anda menerima email ini karena seseorang meminta reset password untuk akun Anda.</p>
        <p style="font-size: 18px; font-weight: bold;">Kode OTP Anda: <span style="color: #007bff;">{{ $otp }}</span></p>
        <p>Masukkan kode ini di halaman verifikasi untuk melanjutkan proses reset password.</p>
        <hr>
        <p style="font-size: 12px; color: #999;">Abaikan email ini jika Anda tidak meminta reset password.</p>
    </div>
</body>
</html>

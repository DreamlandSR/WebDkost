<!DOCTYPE html>
<html>
<body style="font-family: Arial; padding: 20px;">
    <div style="max-width: 400px; margin: 0 auto; text-align: center;">
        <h2 style="color: #2ECC71;">D'Kost</h2>
        <p>Kode OTP untuk daftar akun Anda:</p>
        <div style="font-size: 36px; font-weight: bold;
                    color: #2ECC71; letter-spacing: 8px;
                    padding: 20px; background: #f5f5f5;
                    border-radius: 10px; margin: 20px 0;">
            {{ $otp }}
        </div>
        <p style="color: #999;">Kode berlaku selama <strong>10 menit</strong>.</p>
        <p style="color: #999; font-size: 12px;">
            Abaikan email ini jika Anda tidak ingin mendaftar akun baru.
        </p>
    </div>
</body>
</html>

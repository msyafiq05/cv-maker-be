<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <div style="max-width: 500px; margin: auto; border: 1px solid #e0e0e0; padding: 30px; border-radius: 10px;">
        <h2 style="color: #74c0fc; text-align: center;">Reset Password</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset password akun CV Maker Anda. Berikut adalah kode OTP Anda:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; padding: 15px 30px; background-color: #f5f5f5; border-radius: 8px;">
                {{ $otp }}
            </span>
        </div>
        
        <p>Silakan masukkan kode 6 digit tersebut di halaman aplikasi untuk membuat password baru.</p>
        <p>Jika Anda tidak merasa meminta reset password, silakan abaikan email ini.</p>
        <br>
        <p>Terima kasih,<br>Tim CV Maker</p>
    </div>
</body>
</html>

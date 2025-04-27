<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã xác thực tài khoản KeySport</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #2D3F7B;
            margin: 10px 0 5px;
            font-size: 28px;
        }
        .header p {
            color: #555;
            margin: 0;
            font-size: 16px;
        }
        .otp-container {
            background-color: #f5f8ff;
            padding: 25px;
            text-align: center;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #2D3F7B;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #2D3F7B;
            margin: 10px 0;
            padding: 10px 0;
        }
        .note {
            font-size: 13px;
            color: #777;
            margin-top: 5px;
        }
        .button {
            background-color: #2D3F7B;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 15px 0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
            border-top: 1px solid #f0f0f0;
        }
        .social-links {
            margin: 15px 0 10px;
        }
        .social-link {
            display: inline-block;
            margin: 0 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRyW8o8zfepSWIl7XTpP2i-SVj-GclKgi-xiQ&s" alt="KeySport Logo" class="logo">
            <p>Xác thực tài khoản của bạn</p>
        </div>
        
        <p>Xin chào,</p>
        
        <p>Cảm ơn bạn đã đăng ký tài khoản trên <strong>KeySport</strong>. Để hoàn tất quá trình và bảo mật tài khoản, vui lòng sử dụng mã xác thực bên dưới.</p>
        
        <div class="otp-container">
            <p>Mã xác thực OTP của bạn là:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p class="note">Mã này có hiệu lực trong vòng 10 phút</p>
        </div>
        
        <p>Vui lòng không chia sẻ mã này với bất kỳ ai, kể cả nhân viên của KeySport. Đội ngũ của chúng tôi sẽ không bao giờ yêu cầu bạn cung cấp mã này.</p>
        
        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay.</p>
        
        <p>Trân trọng,<br><strong>Đội ngũ KeySport</strong></p>
        
        <div class="footer">
            <div class="social-links">
                <a href="#" class="social-link">Facebook</a> |
                <a href="#" class="social-link">Instagram</a> |
                <a href="#" class="social-link">Twitter</a>
            </div>
            <p>Đây là email tự động, vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} KeySport. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html> 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di AtapIndonesia</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .header {
            background-color: #0F172A;
            color: #fff;
            padding: 10px 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .content h2 {
            color: #F97316;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #F97316;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AtapIndonesia</h1>
        </div>
        <div class="content">
            <h2>Selamat Datang, {{ $nama }}!</h2>
            <p>Terima kasih telah mendaftar di AtapIndonesia. Akun Anda telah berhasil dibuat.</p>
            <p>Anda sekarang dapat mengakses semua fitur kami, termasuk:</p>
            <ul>
                <li>Melihat katalog produk lengkap</li>
                <li>Menggunakan sistem pakar untuk menghitung kebutuhan talang</li>
                <li>Melakukan pemesanan dengan mudah</li>
            </ul>
            <p>Silakan klik tombol di bawah ini untuk masuk ke dashboard Anda.</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $loginUrl }}" class="button">Masuk ke Dashboard</a>
            </p>
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            <p>Salam,<br>Tim AtapIndonesia</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AtapIndonesia. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>

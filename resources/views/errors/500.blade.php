<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan Sistem</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
            border: 1px solid #e2e8f0;
            padding: 3rem 2rem;
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: .75rem;
        }
        .error-desc {
            color: #64748b;
            font-size: .95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-home {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            border: none;
            padding: .75rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 4px 15px rgba(37,99,235,.3);
            transition: all .2s;
        }
        .btn-home:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,.4);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="error-card">
            <div class="error-code">500</div>
            <h1 class="error-title">Kesalahan Sistem</h1>
            <p class="error-desc">Maaf, terjadi kesalahan internal pada sistem kami. Tim kami sedang berusaha memperbaikinya segera mungkin.</p>
            <a href="/" class="btn-home">
                <i class="bi bi-house"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIMRS - Antigravity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --bg-glass: rgba(255, 255, 255, 0.85);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg-glass);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
        .logo-box {
            width: 64px;
            height: 64px;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 24px;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        }
        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            background: #f9fafb;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .btn-login {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
        .brand-name {
            font-weight: 800;
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .brand-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
        }
        .bg-shapes {
            position: fixed;
            z-index: -1;
            top: 0; left: 0; width: 100%; height: 100%;
        }
        .shape {
            position: absolute;
            background: var(--primary);
            filter: blur(80px);
            opacity: 0.15;
            border-radius: 50%;
        }
        .shape-1 { width: 300px; height: 300px; top: -50px; right: -50px; }
        .shape-2 { width: 400px; height: 400px; bottom: -100px; left: -100px; }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <div class="login-card">
        <div class="logo-box">
            <i class="fas fa-hospital-user"></i>
        </div>
        <h1 class="brand-name">SIMRS Antigravity</h1>
        <p class="brand-subtitle">Silakan masuk untuk mengelola data rumah sakit.</p>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 small py-2 mb-4" style="border-radius: 10px;">
                <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">USERNAME</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #e5e7eb;">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <input type="text" name="username" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="Ketik username Anda" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #e5e7eb;">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                Masuk ke Sistem <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="mt-5 text-center">
            <p class="small text-muted mb-0">&copy; 2026 SIMRS Antigravity Core</p>
            <div class="mt-2">
                <span class="badge bg-light text-muted border">Ver 2.1.0-alpha</span>
            </div>
        </div>
    </div>
</body>
</html>

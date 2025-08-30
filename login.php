<?php
session_start();
include 'koneksi.php';

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)){
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Set session dan redirect ke admin.php
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: admin.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username dan password wajib diisi";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin ENT PENS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-color: #fd7e14;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,128L48,117.3C96,107,192,85,288,112C384,139,480,213,576,218.7C672,224,768,160,864,138.7C960,117,1056,139,1152,149.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-position: bottom;
            opacity: 0.2;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transform: translateY(0);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        
        .logo-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        h3 {
            color: var(--dark-bg);
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0a58ca, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.4);
        }
        
        .btn-back {
            background: white;
            color: var(--secondary-color);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: var(--light-bg);
            border-color: var(--secondary-color);
        }
        
        .alert-danger {
            border-radius: 8px;
            padding: 12px 15px;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: var(--secondary-color);
            font-size: 0.85rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group .bi {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3>Admin Portal</h3>
            <p class="subtitle">ENT PENS News Management</p>
        </div>
        
        <?php if($error): ?>
            <div class='alert alert-danger mb-4' role='alert'>
                <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="mb-4">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username admin" required>
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    <i class="bi bi-key"></i>
                </div>
            </div>
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </div>
            
            <div class="d-grid">
                <a href="index.php" class="btn btn-back">
                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </form>
        
        <div class="footer-text">
            <p>© 2023 ENT PENS News. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Validasi form sederhana
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.querySelector('input[name="username"]');
            const password = document.querySelector('input[name="password"]');
            
            if (!username.value.trim()) {
                e.preventDefault();
                alert('Username harus diisi');
                username.focus();
            } else if (!password.value.trim()) {
                e.preventDefault();
                alert('Password harus diisi');
                password.focus();
            }
        });
    </script>
</body>
</html>
<?php
require_once 'config.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($name) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("SELECT id, email, password, name FROM users WHERE name = ? AND password = ?");
            $stmt->execute([$name, $password]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                
                // Update last login
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                // Handle remember me
                if ($remember) {
                    setcookie('user_name', $user['name'], time() + (86400 * 30), '/');
                    setcookie('user_password', $password, time() + (86400 * 30), '/');
                }
                
                redirect('index.php');
            } else {
                $error = 'Invalid name or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error occurred. Please try again.';
            error_log($e->getMessage());
        }
    }
}

// Check if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern Auth</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0a0a0a;
            color: #f8fafc;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, #3b82f6 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, #8b5cf6 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, #1d4ed8 0%, transparent 50%);
            opacity: 0.1;
            z-index: -1;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
            background: rgba(31, 41, 55, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid #374151;
            border-radius: 25px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            animation: pulse 2s infinite;
        }

        .logo-container i {
            font-size: 2rem;
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f8fafc, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #94a3b8;
            font-size: 1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #374151;
            border-radius: 12px;
            color: #f8fafc;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.9);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-group input:focus + .input-icon {
            color: #3b82f6;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #3b82f6;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
        }

        .remember-me label {
            color: #cbd5e1;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #2563eb;
        }

        .login-button {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #374151;
        }

        .divider span {
            background: rgba(31, 41, 55, 0.95);
            padding: 0 1rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
        }

        .social-button {
            flex: 1;
            padding: 0.8rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #374151;
            border-radius: 12px;
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-button:hover {
            background: rgba(15, 23, 42, 1);
            border-color: #3b82f6;
            transform: translateY(-2px);
        }

        .signup-link {
            text-align: center;
            margin-top: 2rem;
            color: #94a3b8;
        }

        .signup-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #2563eb;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
                max-width: none;
            }
            .login-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <i class="fas fa-lock"></i>
            </div>
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your account</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="email">User Name</label>
                <div class="input-wrapper">
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_COOKIE['user_name']) ? htmlspecialchars($_COOKIE['user_name']) : ''; ?>">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" required 
                           value="<?php echo isset($_COOKIE['user_password']) ? htmlspecialchars($_COOKIE['user_password']) : ''; ?>">
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
                </div>
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" 
                           <?php echo isset($_POST['remember']) ? 'checked' : ''; ?>>
                    <label for="remember">Remember me</label>
                </div>
                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="login-button" id="loginBtn">
                <span id="loginText">Sign In</span>
                <div class="loading" id="loading"></div>
                <i class="fas fa-arrow-right" id="loginArrow"></i>
            </button>
        </form>

        <div class="divider">
            <span>or continue with</span>
        </div>

        <div class="social-login">
            <a href="oauth-google.php" class="social-button">
                <i class="fab fa-google"></i>
                Google
            </a>
            <a href="oauth-github.php" class="social-button">
                <i class="fab fa-github"></i>
                GitHub
            </a>
        </div>

        <div class="signup-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('password');

        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form submission handling
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loading = document.getElementById('loading');
        const loginArrow = document.getElementById('loginArrow');

        loginForm.addEventListener('submit', function() {
            // Show loading state
            loginBtn.disabled = true;
            loginText.style.display = 'none';
            loginArrow.style.display = 'none';
            loading.style.display = 'block';
        });

        // Input focus effects
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>
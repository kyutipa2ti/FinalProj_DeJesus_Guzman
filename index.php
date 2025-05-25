<?php
session_start();

$message = "";
$xmlFile = 'account.xml';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailInput = $_POST['username'] ?? '';
    $passwordInput = $_POST['password'] ?? '';

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        $found = false;

        $found = false;

      foreach ($xml->user as $user) {
          $email = (string)$user->email;
          $password = (string)$user->password;
          $firstName = (string)$user->first_name;
          $lastName = (string)$user->last_name;

          if ($email === $emailInput && $password === $passwordInput) {
              if (strtolower($firstName) === 'admin' && strtolower($lastName) === 'admin') {
                  $found = true;
                  $_SESSION['user_email'] = $email;
                  $_SESSION['user_name'] = $firstName . ' ' . $lastName;

                  header('Location: LandingPage.php');
                  exit;
              } else {
                  $message = "❌ Access denied. Admins only.";
                  $found = true; // Prevent "Invalid email or password" message
                  break;
              }
          }
      }

      if (!$found) {
          $message = "❌ Invalid email or password.";
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('campus.jpg');
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.80);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 10px 25px #061e4e;
      width: 100%;
      max-width: 350px;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 1.5rem;
    }

    .input-group {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .input-group input {
      width: 100%;
      padding: 10px 35px 10px 35px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
    }

    .input-group .fa-user {
      left: 10px;
    }

    .input-group .toggle-password {
      right: 10px;
      cursor: pointer;
    }

    .forgot-password {
      display: block;
      text-align: right;
      font-size: 0.85rem;
      margin-bottom: 1rem;
      color: #555;
      text-decoration: none;
    }

    .forgot-password:hover {
      text-decoration: underline;
    }

    .login-btn {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 25px;
      background: linear-gradient(to right, #2b8dfc, #0072ff);
      color: white;
      font-size: 1rem;
      cursor: pointer;
      margin-bottom: 1rem;
    }

    .login-btn:hover {
      background: linear-gradient(to right, #246bcc, #061e4e);
    }

    .social-login {
      margin: 1rem 0;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .social-login a {
      width: 40px;
      height: 40px;
      background: #f1f1f1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 1.2rem;
      color: #333;
      text-decoration: none;
      transition: background 0.3s;
    }

    .social-login a:hover {
      background: #ddd;
    }

    .register {
      font-size: 0.9rem;
      margin-top: 1rem;
    }

    .register a {
      color: #007bff;
      text-decoration: none;
    }

    .register a:hover {
      text-decoration: underline;
    }

    .error-message {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }
      @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }

            input, select {
                font-size: 15px;
            }
        }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if ($message): ?>
      <div class="error-message"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="email" name="username" placeholder="Email" required />
      </div>
      <div class="input-group">
        <input type="password" id="password" name="password" placeholder="Password" required />
        <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
      </div>
      <a href="#" class="forgot-password">Forgot password?</a>
      <button type="submit" class="login-btn">Login</button>
    </form>

    <p>or log in using</p>
    <div class="social-login">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-google"></i></a>
    </div>

    <p class="register">No account? <a href="signup.php">Sign up</a></p>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.toggle-password');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>

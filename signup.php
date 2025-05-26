<?php
$message = "";

$accountXmlFile = 'account.xml';
$mainXmlFile = 'main.xml';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = ucwords(strtolower(trim($_POST['first_name'] ?? '')));
    $lastName  = ucwords(strtolower(trim($_POST['last_name'] ?? '')));
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($firstName && $lastName && $email && $password) {
        if (file_exists($accountXmlFile)) {
            $accountXml = simplexml_load_file($accountXmlFile);
        } else {
            $accountXml = new SimpleXMLElement('<users></users>');
        }

        $duplicate = false;
        foreach ($accountXml->user as $existingUser) {
            $existingEmail = (string)$existingUser->email;
            $existingFullName = strtolower(trim((string)$existingUser->first_name . ' ' . (string)$existingUser->last_name));
            $newFullName = strtolower(trim($firstName . ' ' . $lastName));

            if ($email === $existingEmail || $newFullName === $existingFullName) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $message = "❌ Email or full name already exists.";
        } else {
            $user = $accountXml->addChild('user');
            $user->addChild('first_name', $firstName);
            $user->addChild('last_name', $lastName);
            $user->addChild('email', $email);
            $user->addChild('password', $password); 

            $domAccount = new DOMDocument('1.0');
            $domAccount->preserveWhiteSpace = false;
            $domAccount->formatOutput = true;
            $domAccount->loadXML($accountXml->asXML());
            $domAccount->save($accountXmlFile);

            $message = "✅ User registered successfully.";
        }
    } else {
        $message = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Registration</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-image: url('sunset.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0, 0, 0, 0.3);
      z-index: 0;
    }

    .signup-form {
      position: relative;
      z-index: 1;
      background-color: rgba(255, 255, 255, 0.92);
      padding: 30px 25px;
      border-radius: 10px;
      box-shadow: 0 0 15px #061e4e;
      width: 100%;
      max-width: 350px;
      display: flex;
      flex-direction: column;
    }

    .signup-form h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }

    .signup-form input[type="text"],
    .signup-form input[type="email"],
    .signup-form input[type="password"] {
      width: 100%;
      padding: 12px 10px;
      margin-bottom: 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .signup-form button {
      width: 100%;
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 15px;
      margin-top: 10px;
      cursor: pointer;
    }

    .signup-form .back-button {
      background-color: #777;
      margin-top: 8px;
    }

    .signup-form button:hover {
      background-color: #45a049;
    }

    .signup-form .back-button:hover {
      background-color: #5e5e5e;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 15px;
      color: red;
    }

    .message.success {
      color: green;
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
  <form class="signup-form" method="POST">
    <h2>User Registration</h2>
    <input type="text" name="first_name" placeholder="First Name" required />
    <input type="text" name="last_name" placeholder="Last Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Register</button>
    <button type="button" class="back-button" onclick="window.location.href='index.php'">← Back</button>

    <?php if ($message): ?>
      <div class="message <?= strpos($message, '✅') === 0 ? 'success' : '' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>
  </form>
</body>
</html>

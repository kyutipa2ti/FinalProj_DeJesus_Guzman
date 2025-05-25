<?php
$xmlFile = 'main.xml';
$firstNameToDelete = $_POST['first_name'] ?? '';
$lastNameToDelete = $_POST['last_name'] ?? '';

$statusMessage = '';
$statusClass = '';

if ($firstNameToDelete && $lastNameToDelete && file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    $found = false;

    for ($i = 0; $i < count($xml->user); $i++) {
        $user = $xml->user[$i];
        if (
            trim(strtolower((string)$user->first_name)) === trim(strtolower($firstNameToDelete)) &&
            trim(strtolower((string)$user->last_name)) === trim(strtolower($lastNameToDelete))
        ) {
            unset($xml->user[$i]);
            $found = true;
            break;
        }
    }

    if ($found) {
        $xml->asXML($xmlFile);
        $statusMessage = "✅ User <strong>$firstNameToDelete $lastNameToDelete</strong> has been deleted successfully.";
        $statusClass = 'success';
    } else {
        $statusMessage = "❌ No matching user found with name <strong>$firstNameToDelete $lastNameToDelete</strong>.";
        $statusClass = 'error';
    }
} else {
    $statusMessage = "❌ Invalid request or missing XML file.";
    $statusClass = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete User Result</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .card {
      background-color: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 450px;
      width: 100%;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }

    .back-button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #061e4e;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .back-button:hover {
      background-color: #0056b3;
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

<div class="card">
  <div class="message <?= $statusClass ?>">
    <?= $statusMessage ?>
  </div>
  <a href="LandingPage.php" class="back-button">← Back to Directory</a>
</div>

</body>
</html>

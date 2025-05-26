<?php
session_start();
// Path to the XML file
$xmlFile = 'main.xml';

// Directory to store uploaded images
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $position   = ucwords(strtolower(trim($_POST['position'] ?? '')));
    $employeeNumber = trim($_POST['employee_number'] ?? '');
    $firstName = ucwords(strtolower(trim($_POST['first_name'] ?? '')));
    $middleName = ucwords(strtolower(trim($_POST['middle_name'] ?? '')));
    $lastName  = ucwords(strtolower(trim($_POST['last_name'] ?? '')));
    $department = ucwords(strtolower(trim($_POST['department'] ?? '')));
    $contact = trim($_POST['contact'] ?? '');
    $imagePath = '';

    if ($position && $firstName && $lastName && $department && $contact && $employeeNumber) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . uniqid() . '_' . $imageName;

            if (move_uploaded_file($imageTmpPath, $targetFilePath)) {
                $imagePath = $targetFilePath;
            } else {
                $message = "❌ Failed to upload image.";
            }
        }

        if (file_exists($xmlFile)) {
            $xml = simplexml_load_file($xmlFile);
        } else {
            $xml = new SimpleXMLElement('<officials></officials>');
        }

        $duplicate = false;
        foreach ($xml->user as $existingUser) {
            if ((string)$existingUser->employee_number === $employeeNumber) {
                $duplicate = true;
                break;
            }
        }

        if ($duplicate) {
            $message = "❌ Employee number already exists.";
        } else {
            $user = $xml->addChild('user');
            $user->addChild('employee_number', $employeeNumber);
            $user->addChild('position', $position);
            $user->addChild('first_name', $firstName);
            $user->addChild('middle_name', $middleName);
            $user->addChild('last_name', $lastName);
            $user->addChild('department', $department);
            $user->addChild('contact', $contact);
            $user->addChild('image', $imagePath ?: 'uploads/default.jpg');

            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            $dom->save($xmlFile);

            $message = "✅ New user added successfully.";
        }
    } else {
        $message = "❌ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add School User</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100vw;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #061e4e;
            padding: 12px 20px;
            margin: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100vw;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .logo img {
            height: 100px;
            margin-top: -15px;
            margin-bottom: -15px;
        }

        .navbar .logo span {
            color: white;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }

        .navbar .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .navbar .buttons form {
            margin: 0;
        }

        .navbar .buttons form button {
            background-color: white;
            color: #061e4e;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar .buttons form button:hover,
        .navbar .buttons form.active button {
            background-color: #061e4e;
            color: white;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 15px 30px #061e4e;
            width: 100%;
            max-width: 500px;
            margin: 30px auto 60px;
            flex-grow: 1;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #061e4e;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight:600;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            padding: 10px 14px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.3);
        }

        select {
            appearance: none;
            background: #fff url("data:image/svg+xml;utf8,<svg fill='gray' height='12' viewBox='0 0 24 24' width='12' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>") no-repeat right 14px center;
            background-size: 12px;
        }

        .message {
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .message.success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .message.error {
            background-color: #f8d7da;
            color: #842029;
        }

        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            color: white;
            background-color: #061e4e;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            background-color: #6c757d;
        }

        .back-btn:hover {
            background-color: #495057;
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

<div class="navbar">
    <div class="logo">
        <img src="bilog.png" alt="Logo">
        <span>M.A. Staff Management System</span>
    </div>
    <div class="buttons">
        <form method="POST" action="LandingPage.php">
            <button type="submit"><i class='bx bx-home'></i>Home</button>
        </form>
        <form method="POST" action="AddMain.php" class="active">
            <button type="submit"><i class='bx bx-user-plus'></i>Add User</button>
        </form>
        <form method="POST" action="dashboard.php">
            <button type="submit"><i class='bx bx-bar-chart-alt'></i>Dashboard</button>
        </form>
        <form method="POST" action="aboutus.php">
            <button type="submit"><i class='bx bx-info-circle'></i>About Us</button>
        </form>
        <form method="POST" action="index.php">
            <button type="submit"><i class='bx bx-log-out'></i>Logout</button>
        </form>
    </div>
</div>

<!-- Form container -->
<div class="form-container">
    <h2><i class='bx bx-user-plus'></i> Add School User</h2>

    <?php if ($message): ?>
        <div class="message <?= str_contains($message, '✅') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="position">Position</label>
        <select name="position" id="position" required>
            <option value="">-- Select Position --</option>
            <option value="Dean">Dean</option>
            <option value="Faculty">Faculty</option>
            <option value="Professor">Professor</option>
            <option value="Research Staff">Research Staff</option>
            <option value="Administrative Staff">Administrative Staff</option>
            <option value="Support Staff">Support Staff</option>
        </select>

        <label for="employee_number">Employee Number</label>
        <input type="text" name="employee_number" id="employee_number" required>

        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="middle_name">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name">

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" required>

        <label for="department">Department</label>
        <select name="department" id="department" required>
            <option value="">-- Select Department --</option>
            <option value="Sciences">Sciences</option>
            <option value="Social Sciences">Social Sciences</option>
            <option value="Humanities">Humanities</option>
            <option value="Business">Business</option>
            <option value="Education">Education</option>
            <option value="Engineering">Engineering</option>
            <option value="Information Technology">Information Technology</option>
        </select>

        <label for="contact">Contact Number</label>
        <input type="number" name="contact" id="contact" required>

        <label for="image">Upload Image</label>
        <input type="file" name="image" id="image" accept="image/*">

        <div class="buttons">
            <button type="submit"><i class='bx bx-plus'></i> Add User</button>
            <button type="button" class="back-btn" onclick="window.location.href='LandingPage.php'">
                <i class='bx bx-arrow-back'></i> Back
            </button>
        </div>
    </form>
</div>

</body>
</html>

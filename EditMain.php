<?php
$xmlFile = 'main.xml';
$uploadDir = 'uploads/';
$message = '';
$editFirstName = $_GET['first_name'] ?? '';
$editLastName = $_GET['last_name'] ?? '';
$editingUser = null;

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
} else {
    die("XML file not found.");
}

foreach ($xml->user as $user) {
    if ((string)$user->first_name === $editFirstName && (string)$user->last_name === $editLastName) {
        $editingUser = $user;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPosition = isset($_POST['position']) && trim($_POST['position']) !== ''
      ? ucwords(strtolower(trim($_POST['position'])))
      : (string)$editingUser->position;
    $employeeNumber = trim($_POST['employee_number'] ?? '');
    $newFirstName = ucwords(strtolower(trim($_POST['first_name'] ?? '')));
    $newMiddleName = ucwords(strtolower(trim($_POST['middle_name'] ?? '')));
    $newLastName  = ucwords(strtolower(trim($_POST['last_name'] ?? '')));
    $newDepartment = isset($_POST['department']) && trim($_POST['department']) !== ''
      ? ucwords(strtolower(trim($_POST['department'])))
      : (string)$editingUser->department;
    $newContact = $_POST['contact'] ?? '';

    if ($editingUser && $newFirstName && $newLastName && $newPosition && $newDepartment && $newContact) {
        $editingUser->first_name = htmlspecialchars($newFirstName);
        $editingUser->middle_name = htmlspecialchars($newMiddleName);
        $editingUser->last_name = htmlspecialchars($newLastName);
        $editingUser->position = htmlspecialchars($newPosition);
        $editingUser->department = htmlspecialchars($newDepartment);
        $editingUser->contact = htmlspecialchars($newContact);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $uploadDir . uniqid() . '_' . $imageName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($imageTmpPath, $targetFilePath)) {
                $editingUser->image = $targetFilePath;
            } else {
                $message = "❌ Failed to upload image.";
            }
        }

        $xml->asXML($xmlFile);
        $message = "✅ User record updated successfully.";
    } else {
        $message = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f2f6fc;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .form-container {
      width: 100%;
      max-width: 500px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px #061e4e;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #061e4e;
    }

    input[type="text"], input[type="file"], input[type="number"], select, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
      transition: 0.3s;
    }

    input[type="text"]:focus {
      border-color: #007BFF;
      outline: none;
    }

    input[type="file"] {
      background: #f7f7f7;
      cursor: pointer;
    }

    button {
      background-color: #061e4e;
      color: white;
      font-weight: bold;
      border: none;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .back-button {
      background-color: #6c757d;
      margin-top: 10px;
    }

    .back-button:hover {
      background-color: #495057;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin: 10px 0;
      color: green;
    }

    .error {
      color: red;
    }

    .image-container {
      text-align: center;
      margin-bottom: 15px;
    }

    .image-container img {
      width: 140px;
      height: 140px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #061e4e;
    }

    label {
      font-weight: 600;
      margin-bottom: 5px;
      display: block;
      color: #333;
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

<div class="form-container">
  <h2>Edit User</h2>

  <?php if ($editingUser): ?>
    <?php if (!empty($editingUser->image)): ?>
      <div class="image-container">
        <img src="<?= htmlspecialchars($editingUser->image) ?>" alt="User Image">
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="employee_number" value="<?= htmlspecialchars($editingUser->employee_number) ?>" hidden>

        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($editingUser->first_name) ?>" required>

        <label>Middle Name</label>
        <input type="text" name="middle_name" value="<?= htmlspecialchars($editingUser->middle_name) ?>">

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($editingUser->last_name) ?>" required>

        <label>Position</label>
          <select name="position">
            <option value="">-- Select Position --</option>
            <?php
              $positions = ["Dean", "Faculty", "Professor", "Research Staff", "Administrative Staff", "Support Staff"];
              foreach ($positions as $pos) {
                  $selected = ($editingUser->position === $pos) ? 'selected' : '';
                  echo "<option value=\"$pos\" $selected>$pos</option>";
              }
            ?>
          </select>

        <label>Department</label>
        <select name="department">
          <option value="">-- Select Department --</option>
          <?php
            $departments = ["Sciences", "Social Sciences", "Humanities", "Business", "Education", "Engineering", "Health Sciences", "Information Technology"];
            foreach ($departments as $dept) {
                $selected = ($editingUser->department === $dept) ? 'selected' : '';
                echo "<option value=\"$dept\" $selected>$dept</option>";
            }
          ?>
        </select>

        <label>Contact</label>
        <input type="number" name="contact" value="<?= htmlspecialchars($editingUser->contact ?? '') ?>" required>

        <label>Change Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">💾 Save Changes</button>
    </form>

  <?php else: ?>
    <p class="message error">❌ User not found.</p>
  <?php endif; ?>

  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>

  <button class="back-button" onclick="window.location.href='LandingPage.php'">← Back</button>
</div>

</body>
</html>

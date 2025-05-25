<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>School Officials Directory</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: black;
            width: 100vw;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .search {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding: 0 10px;
            text-align: center;
        }

        .search h3 {
            flex-basis: 100%;
            font-size: 2.5rem;
            font-weight: 900;
            color: #f0f0f0;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
            margin: 0 0 10px 0;
            letter-spacing: 2px;
            position: relative;
        }

        .search h3::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: #061e4e;
            margin: 12px auto 0 auto;
            border-radius: 2px;
        }

        .search form {
            display: flex;
            gap: 10px;
            justify-content: center;
            width: 100%;
            max-width: 400px;
        }

        .search input {
            flex-grow: 1;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .search button {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            background-color: #061e4e;
            color: white;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
        }

        .search button:hover {
            background-color: white;
            color: #061e4e;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 10px 40px 10px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-height: 320px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 123, 255, 0.4);
        }

        .card img.profile-pic {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #061e4e;
            cursor: pointer;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4);
        }

        .card h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #061e4e;
        }

        .card p {
            margin: 4px 0;
            font-size: 0.9rem;
            color: #555;
        }

        /* UPDATED BUTTON LAYOUT */
        .card .actions {
            margin-top: auto;
            display: flex;
            gap: 12px;          
            width: 100%;
            justify-content: center; 
        }

        .card .actions form {
            flex: 1;            
        }

        .card .actions form button {
            width: 100%;        
            background-color: #061e4e;
            color: white;
            border: none;
            padding: 12px 0;    
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;    
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        .card .actions form button:hover {
            background-color: #0056b3;
        }

        #imageModal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        #imageModal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
        }

        #imageModal span {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="bilog.png" alt="Logo" />
        <span>M.A. Staff Management System</span>
    </div>
    <div class="buttons">
        <form method="POST" action="LandingPage.php">
            <button type="submit"><i class='bx bx-home'></i>Home</button>
        </form>
        <form method="POST" action="AddMain.php">
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

<div class="search">
    <h3>School Officials Directory</h3>
    <form method="GET">
        <input type="text" name="search" placeholder="Search..." 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        <button type="submit">Search</button>
    </form>
</div>

<div class="card-grid">
<?php
$xml = simplexml_load_file('main.xml');
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

foreach ($xml->user as $user):
    $position = isset($user->position) ? htmlspecialchars($user->position) : '—';
    $employee_number = htmlspecialchars($user->employee_number);
    $first_name = htmlspecialchars($user->first_name);
    $last_name = htmlspecialchars($user->last_name);
    $middle_name = htmlspecialchars($user->middle_name);
    $full_name = trim("$first_name $middle_name $last_name");
    $department = isset($user->department) ? htmlspecialchars($user->department) : '—';
    $contact = isset($user->contact) ? htmlspecialchars($user->contact) : '—';
    $image = (isset($user->image) && file_exists($user->image)) ? htmlspecialchars($user->image) : 'default.png';

    $match = !$search || (
        stripos($employee_number, $search) !== false ||
        stripos($first_name, $search) !== false ||
        stripos($middle_name, $search) !== false ||
        stripos($last_name, $search) !== false ||
        stripos($position, $search) !== false ||
        stripos($department, $search) !== false
    );

    if (!$match) continue;
?>
    <div class="card">
        <img src="<?= $image ?>" alt="User Image" class="profile-pic" onclick="enlargeImage(this.src)" />
        <h4><?= $employee_number ?></h4>
        <h4><?= $full_name ?></h4>
        <p><strong><?= $position ?></strong></p>
        <p><?= $department ?></p>
        <p><?= $contact ?></p>
        <div class="actions">
            <form method="GET" action="EditMain.php">
                <input type="hidden" name="first_name" value="<?= $first_name ?>">
                <input type="hidden" name="middle_name" value="<?= $first_name ?>">
                <input type="hidden" name="last_name" value="<?= $last_name ?>">
                <button type="submit">Edit</button>
            </form>
            <form method="POST" action="DeleteMain.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                <input type="hidden" name="first_name" value="<?= $first_name ?>">
                <input type="hidden" name="middle_name" value="<?= $first_name ?>">
                <input type="hidden" name="last_name" value="<?= $last_name ?>">
                <button type="submit">Delete</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div id="imageModal" onclick="closeModal(event)">
    <span onclick="closeModal(event)">&times;</span>
    <img id="modalImage" src="" alt="Enlarged Image" />
</div>

<script>
function enlargeImage(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'flex';
    modalImg.src = src;
}

function closeModal(event) {
    if(event.target.id === 'imageModal' || event.target.tagName === 'SPAN') {
        document.getElementById('imageModal').style.display = 'none';
    }
}
</script>

</body>
</html>

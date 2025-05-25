<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - School Directory</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Reset margin, padding, and prevent horizontal scroll */
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100vw;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR */
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

        /* CONTENT */
        .content {
            background-color: white;
            padding: 30px 20px; /* Add padding here, since body has none */
            border-radius: 12px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 4px 10px #061e4e;
            flex-grow: 1;
            box-sizing: border-box;
        }

        .content h2 {
            text-align: center;
            color: #061e4e;
            margin-bottom: 30px;
        }

        .card-row {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .flip-card {
            background-color: transparent;
            width: 280px;
            height: 360px;
            perspective: 1000px;
            overflow: hidden;
            display: inline-block;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            will-change: transform;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            box-sizing: border-box;
            overflow: hidden;
            top: 0;
            left: 0;
        }

        .flip-card-front {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .flip-card-front img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            display: block;
        }

        .name-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 10px;
            font-weight: bold;
            font-size: 16px;
            border-radius: 0 0 12px 12px;
            text-align: center;
            box-sizing: border-box;
        }

        .flip-card-back {
            background-color: #061e4e;
            color: white;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .flip-card-back h3 {
            margin: 10px 0 5px;
        }

        .flip-card-back p {
            font-size: 14px;
            line-height: 1.4;
        }

        /* FOOTER */
        footer.custom-footer {
            background-color: #061e4e;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            letter-spacing: 0.5px;
            padding: 25px 20px; 
            margin: 0;
            width: 100vw;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        footer.custom-footer .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
            row-gap: 6px;
        }

        footer.custom-footer .left,
        footer.custom-footer .right {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        footer.custom-footer a {
            color: white;
            text-decoration: underline;
        }

        footer.custom-footer a:hover {
            color: #e6e6e6;
        }

        @media (max-width: 600px) {
            footer.custom-footer .footer-content {
                flex-direction: column;
                text-align: center;
            }

            footer.custom-footer .left,
            footer.custom-footer .right {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">
        <img src="bilog.png" alt="Logo">
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
         <form method="POST" action="aboutus.php" class="active">
            <button type="submit"><i class='bx bx-info-circle'></i>About Us</button>
        </form>
        <form method="POST" action="index.php">
            <button type="submit"><i class='bx bx-log-out'></i>Logout</button>
        </form>
    </div>
</div>

<!-- Team Section -->
<div class="content">
    <h2>OUR TEAM</h2>

    <div class="card-row">
        <!-- Card 1 -->
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="ocram.jpg" alt="Ocram">
                    <div class="name-overlay">Marco Angelo C. De Jesus</div>
                </div>
                <div class="flip-card-back">
                    <h3>Frontend Developer</h3>
                    <p>My role focuses on designing and implementing the visual layout, intuitive navigation, and user-friendly interactions of the website. I ensure that users have a seamless experience across different devices while also supporting the team in collaborative development efforts.</p>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="aubrey.jpg" alt="Obringot">
                    <div class="name-overlay">Aubrey M. Guzman</div>
                </div>
                <div class="flip-card-back">
                    <h3>Backend Developer</h3>
                    <p>I am responsible for handling the website’s server-side operations, particularly managing data storage and retrieval using XML as our primary database format. I ensure that the system logic works smoothly behind the scenes and that the front-end components can access and process data efficiently.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="custom-footer">
    <div class="footer-content">
        <div class="left">
            <span>CONTACT US: m.a.university@gmail.com</span>
            <span>| Phone: +63 917 123 4567</span>
            <span>| BSIT 3B-G1</span>
        </div>
        <div class="right">
            <span>COPYRIGHT © 2025 </span>
        </div>
    </div>
</footer>

</body>
</html>

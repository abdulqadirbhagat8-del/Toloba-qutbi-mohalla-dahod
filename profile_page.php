<?php

session_start();

require_once "config.php";

/*
==================================================
MEMBER LOGIN REQUIRED
==================================================
*/

if (!isset($_SESSION["member_id"])) {
    header("Location: websitelogin.php");
    exit;
}


/*
==================================================
GET LOGGED-IN MEMBER
==================================================
*/

$member_id = $_SESSION["member_id"];

$stmt = $conn->prepare("
    SELECT
        id,
        its_id,
        full_name,
        designation,
        contact_no,
        age,
        jamiat,
        jamaat,
        zone,
        status,
        first_login
    FROM members
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $member_id);
$stmt->execute();

$memberResult = $stmt->get_result();

if ($memberResult->num_rows !== 1) {

    session_unset();
    session_destroy();

    header("Location: websitelogin.php");
    exit;
}

$member = $memberResult->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
My Profile - TOLOBA QUTBI MOHALLA
</title>


<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

html {
    scroll-behavior: smooth;
}

body {
    background: #f5f7f8;
    color: #222;
    overflow-x: hidden;
}

/*
==================================================
HEADER
==================================================
*/

header {

    position: fixed;

    top: 18px;

    left: 50%;

    transform: translateX(-50%);

    width: 94%;

    max-width: 1400px;

    background: rgba(255,255,255,.82);

    backdrop-filter: blur(18px);

    border-radius: 22px;

    padding: 15px 35px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    box-shadow: 0 10px 30px rgba(0,0,0,.08);

    z-index: 999;
}


.logo {

    display: flex;

    align-items: center;

    gap: 15px;
}


.logo img {

    width: 62px;

    height: 62px;

    border-radius: 50%;

    object-fit: cover;

    background: white;

    padding: 4px;
}


.logo h2 {

    color: #0b5d1e;

    font-size: 26px;

    font-weight: 700;
}


.logo span {

    display: block;

    font-size: 13px;

    color: #666;
}


/*
==================================================
MEMBER NAVIGATION
==================================================
*/

nav {

    display: flex;

    gap: 24px;

    align-items: center;
}


nav a {

    text-decoration: none;

    font-weight: 600;

    color: #0b5d1e;

    transition: .3s;

    cursor: pointer;
}


nav a:hover {

    color: #084617;
}


.profile-btn {

    background: #0b5d1e;

    color: white !important;

    padding: 11px 18px;

    border-radius: 30px;
}


.logout-btn {

    background: #8b1e1e;

    color: white !important;

    padding: 11px 18px;

    border-radius: 30px;
}


.profile-btn:hover {

    background: #084617;

    color: white !important;
}


.logout-btn:hover {

    background: #6e1717;

    color: white !important;
}

/*
==================================================
MAIN CONTENT
==================================================
*/

.container {
    max-width: 900px;
    margin: 150px auto 50px;
    padding: 40px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,.08);
}

.page-title {
    text-align: center;
    font-size: 42px;
    font-weight: 800;
    color: #0b5d1e;
    margin-bottom: 50px;
}

.profile-info {
    background: #f9f9f9;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-left: 4px solid #0b5d1e;
}

.profile-info h3 {
    color: #0b5d1e;
    margin-bottom: 10px;
    font-size: 16px;
    font-weight: 600;
}

.profile-info p {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.back-link {
    display: inline-block;
    margin-bottom: 30px;
    color: #0b5d1e;
    text-decoration: none;
    font-weight: 600;
    transition: .3s;
}

.back-link:hover {
    color: #084617;
    margin-left: 5px;
}

/*
==================================================
DARK MODE
==================================================
*/

.dark {
    background: #121212;
    color: white;
}

.dark .container {
    background: #1f1f1f;
    color: white;
}

.dark header {
    background: rgba(30,30,30,.9);
}

.dark .profile-info {
    background: #2a2a2a;
    color: white;
    border-left-color: #4ade80;
}

.dark .profile-info h3 {
    color: #4ade80;
}

.dark .profile-info p {
    color: #b0b0b0;
}

/*
==================================================
RESPONSIVE DESIGN
==================================================
*/

@media(max-width:900px) {
    header {
        flex-direction: row;
        gap: 15px;
        padding: 15px 20px;
        justify-content: space-between;
        width: 96%;
    }

    .logo h2 {
        font-size: 20px;
    }

    .logo img {
        width: 50px;
        height: 50px;
    }

    .container {
        margin: 120px 20px 50px;
        padding: 30px;
    }

    .page-title {
        font-size: 32px;
    }
}

@media(max-width:480px) {
    .logo h2 {
        font-size: 16px;
    }

    .logo img {
        width: 45px;
        height: 45px;
    }

    .container {
        margin: 110px 15px 30px;
        padding: 20px;
    }

    .page-title {
        font-size: 24px;
        margin-bottom: 30px;
    }
}

</style>

</head>


<body>


<!-- ==================================================
HEADER
================================================== -->

<header>

    <div class="logo">

        <img
            src="tolobaimageslogo.jpeg"
            alt="Toloba Logo"
        >

        <div>

            <h2>
                TOLOBA QUTBI MOHALLA
            </h2>

        </div>

    </div>

    <nav>

        <a href="index.php">
            Home
        </a>

        <a
            href="website_logout.php"
            class="logout-btn"
        >
            Logout
        </a>

    </nav>

</header>


<!-- ==================================================
MAIN CONTENT
================================================== -->

<div class="container">

    <a href="index.php" class="back-link">
        ← Back to Home
    </a>

    <h1 class="page-title">
        My Profile
    </h1>

    <div class="profile-info">
        <h3>Full Name</h3>
        <p><?php echo htmlspecialchars($member["full_name"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>ITS ID</h3>
        <p><?php echo htmlspecialchars($member["its_id"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Designation</h3>
        <p><?php echo htmlspecialchars($member["designation"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Contact Number</h3>
        <p><?php echo htmlspecialchars($member["contact_no"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Age</h3>
        <p><?php echo htmlspecialchars($member["age"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Jamiat</h3>
        <p><?php echo htmlspecialchars($member["jamiat"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Jamaat</h3>
        <p><?php echo htmlspecialchars($member["jamaat"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Zone</h3>
        <p><?php echo htmlspecialchars($member["zone"]); ?></p>
    </div>

    <div class="profile-info">
        <h3>Status</h3>
        <p><?php echo htmlspecialchars($member["status"]); ?></p>
    </div>

</div>


<!-- ==================================================
DARK MODE BUTTON
================================================== -->

<button id="themeBtn" style="position: fixed; left: 25px; bottom: 25px; width: 55px; height: 55px; border: none; border-radius: 50%; background: white; font-size: 22px; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,.25); z-index: 999;">
    🌙
</button>


<script>

const themeBtn = document.getElementById("themeBtn");

themeBtn.onclick = function() {

    document.body.classList.toggle("dark");

    if (
        document.body.classList.contains("dark")
    ) {

        themeBtn.innerHTML = "☀️";

    } else {

        themeBtn.innerHTML = "🌙";

    }

};

</script>


</body>

</html>

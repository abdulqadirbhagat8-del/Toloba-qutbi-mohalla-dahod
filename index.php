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


/*
==================================================
DATABASE DATA
==================================================
*/


/* Upcoming Miqat */

$miqat = [];

$result = mysqli_query(
    $conn,
    "SELECT * FROM miqat WHERE id=1"
);

if ($result) {
    $miqat = mysqli_fetch_assoc($result);
}


/* Contact */

$contact = [];

$result = mysqli_query(
    $conn,
    "SELECT * FROM contact WHERE id=1"
);

if ($result) {
    $contact = mysqli_fetch_assoc($result);
}


/* Miqat Updates */

$updates = [];

$result = mysqli_query(
    $conn,
    "SELECT * FROM miqat_updates WHERE id=1"
);

if ($result) {
    $updates = mysqli_fetch_assoc($result);
}


/* Statistics */

$memberCount = 0;
$postholderCount = 0;


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM members"
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $memberCount = $row["total"];
}


$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM postholders"
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $postholderCount = $row["total"];
}


/* Gallery */

$photos = mysqli_query(
    $conn,
    "
    SELECT *
    FROM photos
    ORDER BY id DESC
    LIMIT 6
    "
);

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
TOLOBA QUTBI MOHALLA
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
HAMBURGER MENU (MOBILE)
==================================================
*/

.hamburger {

    display: none;

    flex-direction: column;

    cursor: pointer;

    gap: 6px;
}


.hamburger span {

    width: 25px;

    height: 3px;

    background: #0b5d1e;

    border-radius: 2px;

    transition: .3s;
}


.hamburger.active span:nth-child(1) {

    transform: rotate(45deg) translate(10px, 10px);
}


.hamburger.active span:nth-child(2) {

    opacity: 0;
}


.hamburger.active span:nth-child(3) {

    transform: rotate(-45deg) translate(8px, -8px);
}


/*
==================================================
HERO
==================================================
*/

.hero {

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    text-align: center;

    padding: 130px 20px 80px;

    background:
        radial-gradient(
            circle at top,
            #dff7e5 0%,
            #f5f7f8 65%
        );
}


.hero-content {

    max-width: 950px;
}


.hero-logo {

    width: 190px;

    height: 190px;

    border-radius: 50%;

    background: white;

    padding: 12px;

    box-shadow:
        0 20px 50px rgba(0,0,0,.12);

    animation:
        float 4s ease-in-out infinite;
}


@keyframes float {

    0% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-12px);
    }

    100% {
        transform: translateY(0);
    }
}


.hero h1 {

    margin-top: 40px;

    font-size: 64px;

    font-weight: 800;

    line-height: 1.1;
}


.hero h1 span {

    color: #0b5d1e;
}


.hero p {

    margin: 30px auto;

    max-width: 760px;

    font-size: 21px;

    line-height: 1.8;

    color: #666;
}


.hero-buttons {

    margin-top: 45px;

    display: flex;

    justify-content: center;

    gap: 20px;

    flex-wrap: wrap;
}


.hero-buttons a {

    text-decoration: none;

    padding: 18px 40px;

    border-radius: 50px;

    font-weight: 700;

    transition: .3s;
}


.btn-primary {

    background: #0b5d1e;

    color: white;
}


.btn-primary:hover {

    transform: translateY(-6px);
}


.btn-secondary {

    border: 2px solid #0b5d1e;

    color: #0b5d1e;

    background: white;
}


.btn-secondary:hover {

    background: #0b5d1e;

    color: white;
}


/*
==================================================
MEMBER WELCOME
==================================================
*/

.member-welcome {

    margin-top: 25px;

    color: #555;

    font-size: 16px;
}


.member-welcome strong {

    color: #0b5d1e;
}


/*
==================================================
NOTICE BAR
==================================================
*/

.notice {

    background: #0b5d1e;

    color: white;

    padding: 15px;

    font-weight: 600;

    font-size: 17px;

    margin-top: -5px;
}


.notice marquee {

    letter-spacing: .5px;
}


/*
==================================================
STATISTICS
==================================================
*/

.stats-section {

    padding: 110px 8%;
}


.section-title {

    text-align: center;

    font-size: 48px;

    font-weight: 800;

    margin-bottom: 60px;

    color: #111;
}


.stats-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(240px, 1fr));

    gap: 30px;
}


.stat-card {

    background: white;

    padding: 45px;

    border-radius: 28px;

    text-align: center;

    box-shadow:
        0 20px 45px rgba(0,0,0,.08);

    transition: .35s;
}


.stat-card:hover {

    transform: translateY(-12px);
}


.stat-card h1 {

    font-size: 60px;

    color: #0b5d1e;

    margin-bottom: 15px;
}


.stat-card p {

    font-size: 18px;

    color: #666;
}


/*
==================================================
MIQAT
==================================================
*/

.miqat-section {

    padding: 110px 8%;

    background: white;
}


.miqat-card {

    max-width: 900px;

    margin: auto;

    background: #fff;

    border-radius: 30px;

    padding: 45px;

    box-shadow:
        0 20px 45px rgba(0,0,0,.08);
}


.miqat-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 22px 0;

    border-bottom: 1px solid #ececec;
}


.miqat-row span {

    font-size: 18px;

    font-weight: 600;

    color: #666;
}


.miqat-row strong {

    font-size: 19px;

    color: #0b5d1e;
}


.view-miqat {

    display: block;

    width: 260px;

    margin: 45px auto 0;

    text-align: center;

    background: #0b5d1e;

    color: white;

    padding: 18px;

    text-decoration: none;

    font-weight: 700;

    border-radius: 50px;

    transition: .3s;
}


.view-miqat:hover {

    transform: translateY(-5px);

    box-shadow:
        0 15px 30px
        rgba(11,93,30,.25);
}


/*
==================================================
SERVICES
==================================================
*/

.services-section {

    padding: 110px 8%;

    background: #f5f7f8;
}


.services-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(270px, 1fr));

    gap: 30px;
}


.service-card {

    background: white;

    border-radius: 28px;

    padding: 40px 30px;

    text-align: center;

    text-decoration: none;

    color: #222;

    box-shadow:
        0 15px 40px rgba(0,0,0,.08);

    transition: .35s;

    position: relative;

    overflow: hidden;
}


.service-card::before {

    content: "";

    position: absolute;

    left: 0;

    top: 0;

    width: 100%;

    height: 6px;

    background: #0b5d1e;

    transform: scaleX(0);

    transition: .35s;
}


.service-card:hover::before {

    transform: scaleX(1);
}


.service-card:hover {

    transform: translateY(-12px);

    box-shadow:
        0 25px 50px rgba(0,0,0,.12);
}


.service-icon {

    font-size: 50px;

    margin-bottom: 20px;

    color: #0b5d1e;
}


.service-card h3 {

    color: #0b5d1e;

    font-size: 26px;

    margin-bottom: 15px;
}


.service-card p {

    color: #666;

    line-height: 1.8;

    font-size: 16px;
}


/*
==================================================
CONTACT
==================================================
*/

.contact-section {

    padding: 110px 8%;

    background: #f5f7f8;
}


.contact-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(280px, 1fr));

    gap: 30px;
}


.contact-card {

    background: white;

    padding: 35px;

    border-radius: 22px;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

    text-align: center;

    transition: .35s;
}


.contact-card:hover {

    transform: translateY(-8px);
}


.contact-card h3 {

    color: #0b5d1e;

    margin-bottom: 15px;
}


.contact-card h4 {

    margin-bottom: 15px;

    font-size: 22px;
}


.contact-card p {

    margin: 8px 0;

    color: #666;
}


.instagram-box {

    margin-top: 50px;

    text-align: center;
}


.instagram-box a {

    display: inline-block;

    padding: 18px 40px;

    background: #E1306C;

    color: white;

    text-decoration: none;

    border-radius: 50px;

    font-size: 18px;

    font-weight: bold;

    transition: .3s;
}


.instagram-box a:hover {

    transform: translateY(-5px);
}


/*
==================================================
FOOTER
==================================================
*/

.footer {

    background: #0b5d1e;

    color: white;

    padding: 70px 20px 40px;

    text-align: center;
}


.footer-logo {

    width: 100px;

    height: 100px;

    border-radius: 50%;

    background: white;

    padding: 8px;

    margin-bottom: 20px;
}


.footer h2 {

    font-size: 32px;

    margin-bottom: 15px;
}


.footer p {

    line-height: 1.8;
}


.footer-links {

    margin: 35px 0;

    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 25px;
}


.footer-links a {

    color: white;

    text-decoration: none;

    font-weight: 600;

    transition: .3s;
}


.footer-links a:hover {

    color: #ffe082;
}


.footer-bottom {

    margin-top: 35px;

    border-top:
        1px solid rgba(255,255,255,.2);

    padding-top: 20px;

    font-size: 15px;

    opacity: .9;
}


/*
==================================================
SCROLL BUTTON
==================================================
*/

#topBtn {

    position: fixed;

    right: 25px;

    bottom: 25px;

    width: 55px;

    height: 55px;

    border: none;

    border-radius: 50%;

    background: #0b5d1e;

    color: white;

    font-size: 24px;

    cursor: pointer;

    display: none;

    box-shadow:
        0 10px 25px rgba(0,0,0,.25);

    z-index: 999;
}


/*
==================================================
THEME BUTTON
==================================================
*/

#themeBtn {

    position: fixed;

    left: 25px;

    bottom: 25px;

    width: 55px;

    height: 55px;

    border: none;

    border-radius: 50%;

    background: white;

    font-size: 22px;

    cursor: pointer;

    box-shadow:
        0 10px 25px rgba(0,0,0,.25);

    z-index: 999;
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


.dark .stat-card,
.dark .service-card,
.dark .contact-card,
.dark .miqat-card {

    background: #1f1f1f;

    color: white;
}


.dark .section-title {

    color: white;
}


.dark .footer {

    background: #000;
}


.dark header {

    background: rgba(30,30,30,.9);
}


.dark nav a {

    color: white;
}


/*
==================================================
RESPONSIVE DESIGN - MOBILE FRIENDLY NAV
==================================================
*/

@media(max-width:1200px) {

    header {

        padding: 12px 25px;
    }

    nav {

        gap: 16px;
    }

    nav a {

        font-size: 14px;
    }
}


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

    .hamburger {

        display: flex;
    }

    nav {

        position: absolute;

        top: 100%;

        left: 0;

        right: 0;

        background: rgba(255,255,255,.95);

        backdrop-filter: blur(18px);

        flex-direction: column;

        gap: 8px;

        padding: 20px;

        margin-top: 12px;

        border-radius: 20px;

        box-shadow: 0 15px 35px rgba(0,0,0,.12);

        max-height: 0;

        overflow: hidden;

        transition: max-height .3s ease-out;

        width: 95%;

        left: 50%;

        transform: translateX(-50%);

        display: none;
    }

    nav.active {

        display: flex;

        max-height: 500px;
    }

    nav a {

        padding: 12px 0;

        text-align: center;

        border-bottom: 1px solid #f0f0f0;

        color: #0b5d1e;

        font-weight: 600;
    }

    nav a:last-child {

        border-bottom: none;
    }

    .profile-btn,
    .logout-btn {

        padding: 12px 16px;

        font-size: 14px;
    }

    .hero h1 {

        font-size: 42px;
    }

    .hero-logo {

        width: 150px;

        height: 150px;
    }
}


@media(max-width:768px) {

    .section-title {

        font-size: 36px;
    }

    .miqat-row {

        flex-direction: column;

        align-items: flex-start;

        gap: 8px;
    }

    .stat-card h1 {

        font-size: 44px;
    }

    .services-grid {

        grid-template-columns: 1fr;
    }

    .contact-grid {

        grid-template-columns: 1fr;
    }

    header {

        top: 10px;

        width: 96%;

        padding: 12px 15px;
    }

    nav {

        width: calc(100% - 30px);
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

    .hero h1 {

        font-size: 32px;
    }

    .hero p {

        font-size: 16px;
    }

    .hero-buttons {

        flex-direction: column;

        gap: 12px;
    }

    .hero-buttons a {

        padding: 14px 28px;

        font-size: 14px;
    }

    .section-title {

        font-size: 28px;

        margin-bottom: 40px;
    }

    .stat-card {

        padding: 30px;
    }

    .stat-card h1 {

        font-size: 36px;
    }

    .miqat-card {

        padding: 25px;
    }

    .service-card {

        padding: 25px 20px;
    }

    .contact-card {

        padding: 25px;
    }

    nav a {

        padding: 10px 0;

        font-size: 14px;
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

    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <nav id="navMenu">

        <a href="#miqat">
            Miqat
        </a>

        <a href="#services">
            Services
        </a>

        <a href="#contact">
            Contact
        </a>

        <a
            href="profile.php"
            class="profile-btn"
        >
            My Profile
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
HERO
================================================== -->

<section class="hero">

    <div class="hero-content">

        <img
            src="tolobaimageslogo.jpeg"
            class="hero-logo"
            alt="Toloba Qutbi Mohalla Logo"
        >


        <h1>

            Welcome to

            <span>
                TOLOBA QUTBI MOHALLA
            </span>

        </h1>


        <div class="member-welcome">

            Welcome,
            <strong>
                <?php
                echo htmlspecialchars(
                    $member["full_name"]
                );
                ?>
            </strong>

        </div>


        <p>

            Serving the Dawoodi Bohra Community
            with Unity, Dedication and Transparency
            through a modern digital platform.

        </p>


        <div class="hero-buttons">

            <a
                href="#miqat"
                class="btn-primary"
            >
                Upcoming Miqat
            </a>


            <a
                href="#services"
                class="btn-secondary"
            >
                Explore Services
            </a>

        </div>

    </div>

</section>


<!-- ==================================================
NOTICE
================================================== -->

<div class="notice">

    <marquee>

        📢

        <?php
        echo htmlspecialchars(
            $updates["message"] ?? "Welcome to TOLOBA QUTBI MOHALLA"
        );
        ?>

        |

        Upcoming Miqat :

        <?php
        echo htmlspecialchars(
            $miqat["miqat_name"] ?? "To be announced"
        );
        ?>

        |

        <?php
        echo htmlspecialchars(
            $miqat["english_date"] ?? ""
        );
        ?>

        |

        <?php
        echo htmlspecialchars(
            $miqat["venue"] ?? ""
        );
        ?>

    </marquee>

</div>


<!-- ==================================================
STATISTICS
================================================== -->

<section class="stats-section">

    <h2 class="section-title">
        Community Statistics
    </h2>


    <div class="stats-grid">


        <div class="stat-card">

            <h1>
                <?php echo $memberCount; ?>
            </h1>

            <p>
                Total Members
            </p>

        </div>


        <div class="stat-card">

            <h1>
                <?php echo $postholderCount; ?>
            </h1>

            <p>
                Post Holders
            </p>

        </div>


        <div class="stat-card">

            <h1>
                1
            </h1>

            <p>
                Upcoming Miqat
            </p>

        </div>

    </div>

</section>


<!-- ==================================================
UPCOMING MIQAT
================================================== -->

<section
    class="miqat-section"
    id="miqat"
>

    <h2 class="section-title">

        Upcoming Miqat

    </h2>


    <div class="miqat-card">


        <div class="miqat-row">

            <span>
                Miqat Name
            </span>

            <strong>

                <?php
                echo htmlspecialchars(
                    $miqat["miqat_name"] ?? "-"
                );
                ?>

            </strong>

        </div>


        <div class="miqat-row">

            <span>
                Hijri Date
            </span>

            <strong>

                <?php
                echo htmlspecialchars(
                    $miqat["hijri_date"] ?? "-"
                );
                ?>

            </strong>

        </div>


        <div class="miqat-row">

            <span>
                English Date
            </span>

            <strong>

                <?php
                echo htmlspecialchars(
                    $miqat["english_date"] ?? "-"
                );
                ?>

            </strong>

        </div>


        <div class="miqat-row">

            <span>
                Venue
            </span>

            <strong>

                <?php
                echo htmlspecialchars(
                    $miqat["venue"] ?? "-"
                );
                ?>

            </strong>

        </div>


        <div class="miqat-row">

            <span>
                Miqat Incharge
            </span>

            <strong>

                <?php
                echo htmlspecialchars(
                    $miqat["incharge"] ?? "-"
                );
                ?>

            </strong>

        </div>


        <a
            href="miqat.php"
            class="view-miqat"
        >
            View Complete Miqat →
        </a>

    </div>

</section>


<!-- ==================================================
SERVICES
================================================== -->

<section
    class="services-section"
    id="services"
>

    <h2 class="section-title">
        Explore Our Services
    </h2>


    <div class="services-grid">


        <a
            href="members.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-users"></i>
            </div>

            <h3>
                Members
            </h3>

            <p>
                Community member information.
            </p>

        </a>


        <a
            href="postholders.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-medal"></i>
            </div>

            <h3>
                Post Holders
            </h3>

            <p>
                Meet the office bearers of
                Toloba Qutbi Mohalla.
            </p>

        </a>


        <a
            href="miqat.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>

            <h3>
                Upcoming Miqat
            </h3>

            <p>
                Latest Miqat schedule and details.
            </p>

        </a>


        <a
            href="miqatkhidmat.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-bullhorn"></i>
            </div>

            <h3>
                Miqat Khidmat
            </h3>

            <p>
                Latest Khidmat announcements and duties.
            </p>

        </a>


        <a
            href="ashara.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-mosque"></i>
            </div>

            <h3>
                Ashara 1449
            </h3>

            <p>
                Ashara information and programme details.
            </p>

        </a>


        <a
            href="contact.php"
            class="service-card"
        >

            <div class="service-icon">
                <i class="fas fa-phone"></i>
            </div>

            <h3>
                Contact
            </h3>

            <p>
                Secretary, Treasurer and office contacts.
            </p>

        </a>


    </div>

</section>


<!-- ==================================================
CONTACT
================================================== -->

<section
    class="contact-section"
    id="contact"
>

    <h2 class="section-title">
        Contact Us
    </h2>


    <div class="contact-grid">


        <div class="contact-card">

            <h3>
                Secretary
            </h3>

            <h4>

                <?php
                echo htmlspecialchars(
                    $contact["secretary_name"] ?? "-"
                );
                ?>

            </h4>

            <p>
                📞
                <?php
                echo htmlspecialchars(
                    $contact["secretary_contact"] ?? "-"
                );
                ?>
            </p>

            <p>
                ✉
                <?php
                echo htmlspecialchars(
                    $contact["secretary_email"] ?? "-"
                );
                ?>
            </p>

        </div>


        <div class="contact-card">

            <h3>
                Joint Secretary
            </h3>

            <h4>

                <?php
                echo htmlspecialchars(
                    $contact["joint_secretary_name"] ?? "-"
                );
                ?>

            </h4>

            <p>
                📞
                <?php
                echo htmlspecialchars(
                    $contact["joint_secretary_contact"] ?? "-"
                );
                ?>
            </p>

            <p>
                ✉ kutubuddinkagdi65@gmail.com
            </p>

        </div>


        <div class="contact-card">

            <h3>
                Treasurer
            </h3>

            <h4>

                <?php
                echo htmlspecialchars(
                    $contact["treasurer_name"] ?? "-"
                );
                ?>

            </h4>

            <p>
                📞
                <?php
                echo htmlspecialchars(
                    $contact["treasurer_contact"] ?? "-"
                );
                ?>
            </p>

            <p>
                ✉
                <?php
                echo htmlspecialchars(
                    $contact["treasurer_email"] ?? "-"
                );
                ?>
            </p>

        </div>


        <div class="contact-card">

            <h3>
                Joint Treasurer
            </h3>

            <h4>

                <?php
                echo htmlspecialchars(
                    $contact["it_admin_name"] ?? "-"
                );
                ?>

            </h4>

            <p>
                📞
                <?php
                echo htmlspecialchars(
                    $contact["it_admin_contact"] ?? "-"
                );
                ?>
            </p>

            <p>
                ✉
                <?php
                echo htmlspecialchars(
                    $contact["it_admin_email"] ?? "-"
                );
                ?>
            </p>

        </div>

    </div>


    <div class="instagram-box">

        <a
            href="https://instagram.com/tkm_dohad_jamiat"
            target="_blank"
        >

            📸 @tkm_dohad_jamiat

        </a>

    </div>

</section>


<!-- ==================================================
FOOTER
================================================== -->

<footer class="footer">

    <div class="footer-content">


        <img
            src="tolobaimageslogo.jpeg"
            class="footer-logo"
            alt="Toloba Logo"
        >


        <h2>
            TOLOBA QUTBI MOHALLA
        </h2>


        <p>

            Serving the Dawoodi Bohra Community
            with Unity, Dedication and Transparency.

        </p>


        <div class="footer-links">

            <a href="index.php">
                Home
            </a>

            <a href="profile.php">
                My Profile
            </a>

            <a href="miqat.php">
                Miqat
            </a>

            <a href="ashara.php">
                Ashara
            </a>

            <a href="contact.php">
                Contact
            </a>

            <a href="logout.php">
                Logout
            </a>

        </div>


        <div class="footer-bottom">

            <p>
                © <?php echo date("Y"); ?>
                TOLOBA QUTBI MOHALLA DAHOD
            </p>

            <p>
                All Rights Reserved
            </p>

            <p>
                Developed by IT Department
            </p>

        </div>

    </div>

</footer>


<!-- ==================================================
SCROLL TO TOP
================================================== -->

<button id="topBtn">
    ↑
</button>


<!-- ==================================================
DARK MODE
================================================== -->

<button id="themeBtn">
    🌙
</button>


<script>

/* Hamburger Menu Toggle - Only opens on click */

const hamburger = document.getElementById("hamburger");
const navMenu = document.getElementById("navMenu");

hamburger.addEventListener("click", function(e) {
    e.stopPropagation();
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
});

/* Close menu when link is clicked */

const navLinks = navMenu.querySelectorAll("a");

navLinks.forEach(link => {
    link.addEventListener("click", function() {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    });
});

/* Close menu when clicking outside */

document.addEventListener("click", function(event) {
    const header = document.querySelector("header");
    const isClickInsideHeader = header.contains(event.target);
    
    if (!isClickInsideHeader) {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    }
});


/* Scroll Button */

const topBtn = document.getElementById("topBtn");

window.addEventListener(
    "scroll",
    function() {

        if (window.scrollY > 300) {

            topBtn.style.display = "block";

        } else {

            topBtn.style.display = "none";

        }

    }
);

topBtn.onclick = function() {

    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

};


/* Dark Mode */

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
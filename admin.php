<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include("config.php");

$memberCount = 0;
$photoCount = 0;
$postholderCount = 0;

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM members");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $memberCount = $row['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM photos");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $photoCount = $row['total'];
}

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM postholders");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $postholderCount = $row['total'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Admin Panel | TOLOBA QUTBI MOHALLA
</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,Helvetica,sans-serif;
}

body{
    background:#f1f5f2;
    color:#222;
}


/* HEADER */

header{

    background:#0b5d1e;

    color:white;

    padding:25px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:20px;
}

.logo h1{
    font-size:28px;
}

.logo p{
    margin-top:5px;
    opacity:.8;
}

.header-buttons{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.header-buttons a{

    color:white;

    text-decoration:none;

    padding:11px 18px;

    border-radius:8px;

    font-weight:bold;
}

.website-btn{
    background:#ffffff22;
}

.logout-btn{
    background:#c62828;
}

.header-buttons a:hover{
    opacity:.85;
}


/* MAIN */

.container{

    width:92%;

    max-width:1400px;

    margin:35px auto 60px;
}


/* WELCOME */

.welcome{

    background:white;

    padding:25px;

    border-radius:15px;

    box-shadow:0 5px 15px rgba(0,0,0,.08);

    margin-bottom:30px;
}

.welcome h2{
    color:#0b5d1e;
    margin-bottom:8px;
}

.welcome p{
    color:#666;
}


/* STATISTICS */

.stats{

    display:grid;

    grid-template-columns:
        repeat(auto-fit,minmax(200px,1fr));

    gap:20px;

    margin-bottom:35px;
}

.stat{

    background:white;

    padding:25px;

    border-radius:15px;

    text-align:center;

    box-shadow:0 5px 15px rgba(0,0,0,.08);

    border-top:5px solid #0b5d1e;
}

.stat h3{

    font-size:40px;

    color:#0b5d1e;

}

.stat p{

    color:#666;

    margin-top:5px;
}


/* SECTION */

.section-title{

    color:#0b5d1e;

    margin:35px 0 18px;

    font-size:25px;
}


/* CARDS */

.grid{

    display:grid;

    grid-template-columns:
        repeat(auto-fit,minmax(260px,1fr));

    gap:20px;
}

.card{

    background:white;

    padding:25px;

    border-radius:15px;

    box-shadow:
        0 5px 15px rgba(0,0,0,.08);

    transition:.3s;
}

.card:hover{

    transform:translateY(-5px);

    box-shadow:
        0 10px 25px rgba(0,0,0,.12);
}

.icon{

    font-size:38px;

    margin-bottom:15px;
}

.card h3{

    color:#0b5d1e;

    margin-bottom:8px;

    font-size:21px;
}

.card p{

    color:#666;

    line-height:1.6;

    margin-bottom:18px;
}

.card a{

    display:inline-block;

    background:#0b5d1e;

    color:white;

    text-decoration:none;

    padding:11px 20px;

    border-radius:8px;

    font-weight:bold;
}

.card a:hover{

    background:#084617;
}


/* DANGER CARD */

.password-card{

    border-top:5px solid #c62828;
}

.password-card h3{

    color:#c62828;
}


/* FOOTER */

footer{

    background:#0b5d1e;

    color:white;

    text-align:center;

    padding:20px;
}


/* MOBILE */

@media(max-width:700px){

    header{

        flex-direction:column;

        text-align:center;

    }

    .header-buttons{

        justify-content:center;

    }

}

</style>

</head>


<body>


<header>

<div class="logo">

<h1>
TOLOBA QUTBI MOHALLA
</h1>

<p>
Administration Control Panel
</p>

</div>


<div class="header-buttons">

<a
href="index.php"
class="website-btn">

🌐 Website

</a>

<a
href="logout.php"
class="logout-btn">

🚪 Logout

</a>

</div>

</header>


<div class="container">


<div class="welcome">

<h2>
Welcome to the Admin Panel
</h2>

<p>
From here you can manage the content and member accounts
of TOLOBA QUTBI MOHALLA.
</p>

</div>


<!-- STATISTICS -->

<div class="stats">

<div class="stat">

<h3>
<?php echo $memberCount; ?>
</h3>

<p>
Members
</p>

</div>


<div class="stat">

<h3>
<?php echo $postholderCount; ?>
</h3>

<p>
Post Holders
</p>

</div>


<div class="stat">

<h3>
<?php echo $photoCount; ?>
</h3>

<p>
Gallery Photos
</p>

</div>

</div>


<!-- WEBSITE CONTENT -->

<h2 class="section-title">
🌐 Website Content
</h2>


<div class="grid">


<div class="card">

<div class="icon">
📅
</div>

<h3>
Manage Miqat
</h3>

<p>
Edit upcoming Miqat date, venue, name and incharge.
</p>

<a href="edit_miqat.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
🏅
</div>

<h3>
Manage Post Holders
</h3>

<p>
Update community office bearer information.
</p>

<a href="edit_postholders.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
📸
</div>

<h3>
Manage Gallery
</h3>

<p>
Upload and delete community photographs.
</p>

<a href="edit_photos.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
📞
</div>

<h3>
Manage Contact
</h3>

<p>
Update secretary, treasurer, IT and contact information.
</p>

<a href="edit_contact.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
🕌
</div>

<h3>
Manage Ashara
</h3>

<p>
Edit Ashara programme and information.
</p>

<a href="edit_ashara.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
📢
</div>

<h3>
Miqat Khidmat
</h3>

<p>
Manage important Miqat Khidmat announcements.
</p>

<a href="edit_miqatkhidmat.php">
Open
</a>

</div>

</div>


<!-- MEMBERS -->

<h2 class="section-title">
👥 Member Management
</h2>


<div class="grid">


<div class="card">

<div class="icon">
👥
</div>

<h3>
Manage Members
</h3>

<p>
View and edit registered member information.
</p>

<a href="edit_members.php">
Open
</a>

</div>


<div class="card password-card">

<div class="icon">
🔑
</div>

<h3>
Member Password Reset
</h3>

<p>
Reset a member's website password if they forget it.
</p>

<a href="admin_member_password.php">
Open
</a>

</div>

</div>


<!-- WEBSITE SETTINGS -->

<h2 class="section-title">
⚙️ Website Settings
</h2>


<div class="grid">


<div class="card">

<div class="icon">
🧭
</div>

<h3>
Navigation Settings
</h3>

<p>
Manage the navigation links displayed on the website.
</p>

<a href="edit_navigation.php">
Open
</a>

</div>


<div class="card">

<div class="icon">
🏠
</div>

<h3>
View Website
</h3>

<p>
Open the public community website.
</p>

<a href="index.php">
Open Website
</a>

</div>


</div>


</div>


<footer>

All Rights Reserved ©
<?php echo date("Y"); ?>
TOLOBA QUTBI MOHALLA DAHOD

</footer>


</body>

</html>
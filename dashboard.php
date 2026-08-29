
<?php

session_start();

require_once "config.php";

/*
=========================================================
TOLOBA QUTBI MOHALLA
MEMBER PROFILE DASHBOARD
=========================================================
*/


/*
---------------------------------------------------------
CHECK LOGIN
---------------------------------------------------------
*/

if (!isset($_SESSION["member_id"])) {

    header("Location: member_login.php");
    exit();

}

$member_id = (int) $_SESSION["member_id"];


/*
---------------------------------------------------------
GET MEMBER DATA
---------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        its_id,
        full_name,
        profile_photo
        designation,
        contact_no,
        age,
        jamiat,
        jamaat,
        zone,
        status,
        points,
        qr_token,
        must_change_password
    FROM member_accounts
    WHERE id = ?
    LIMIT 1
");


if (!$stmt) {

    die("Database error: " . $conn->error);

}


$stmt->bind_param("i", $member_id);

$stmt->execute();

$result = $stmt->get_result();


if (!$result || $result->num_rows !== 1) {

    session_unset();
    session_destroy();

    header("Location: member_login.php");

    exit();

}


$member = $result->fetch_assoc();

$stmt->close();


/*
---------------------------------------------------------
FORCE PASSWORD CHANGE
---------------------------------------------------------
*/

if ((int)$member["must_change_password"] === 1) {

    header("Location: change_password.php");

    exit();

}


/*
---------------------------------------------------------
SAFE DISPLAY VALUES
---------------------------------------------------------
*/

$full_name = trim($member["full_name"] ?? "");

$designation = trim($member["designation"] ?? "");

$its_id = trim($member["its_id"] ?? "");

$contact = trim($member["contact_no"] ?? "");

$age = trim((string)($member["age"] ?? ""));

$jamiat = trim($member["jamiat"] ?? "");

$jamaat = trim($member["jamaat"] ?? "");

$zone = trim($member["zone"] ?? "");

$status = trim($member["status"] ?? "Active");

$points = (int)($member["points"] ?? 0);


/*
---------------------------------------------------------
INITIALS
---------------------------------------------------------
*/

$words = preg_split(
    '/\s+/',
    $full_name,
    -1,
    PREG_SPLIT_NO_EMPTY
);

$initials = "";

if (isset($words[0])) {

    $initials .= strtoupper(
        substr($words[0], 0, 1)
    );

}

if (isset($words[1])) {

    $initials .= strtoupper(
        substr($words[1], 0, 1)
    );

}

if ($initials === "") {

    $initials = "M";

}


/*
---------------------------------------------------------
HELPER
---------------------------------------------------------
*/

function showValue($value)
{

    $value = trim((string)$value);

    if ($value === "") {

        return "Not Available";

    }

    return htmlspecialchars($value);

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
My Profile | TOLOBA QUTBI MOHALLA
</title>


<style>

/* ======================================================
   RESET
====================================================== */

* {

    box-sizing: border-box;

    margin: 0;

    padding: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* ======================================================
   BODY
====================================================== */

body {

    min-height: 100vh;

    background:
        linear-gradient(
            135deg,
            #eef8f0 0%,
            #ffffff 50%,
            #eef8f0 100%
        );

    color: #222;

}


/* ======================================================
   HEADER
====================================================== */

.header {

    background: #0b5d1e;

    color: white;

    padding:
        16px
        5%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    box-shadow:
        0 3px 15px
        rgba(0,0,0,0.15);

}


.header-brand {

    display: flex;

    align-items: center;

    gap: 12px;

}


.header-logo {

    width: 45px;

    height: 45px;

    border-radius: 50%;

    background: white;

    color: #0b5d1e;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: bold;

    font-size: 16px;

}


.header-title {

    font-size: 18px;

    font-weight: bold;

}


.header-subtitle {

    font-size: 11px;

    opacity: .85;

    margin-top: 3px;

}


.logout {

    color: white;

    text-decoration: none;

    border:
        1px solid
        rgba(255,255,255,.7);

    padding:
        9px
        16px;

    border-radius: 8px;

    font-size: 13px;

    transition: .2s;

}


.logout:hover {

    background: white;

    color: #0b5d1e;

}


/* ======================================================
   MAIN
====================================================== */

.container {

    width: 92%;

    max-width: 1050px;

    margin:
        35px auto
        55px;

}


/* ======================================================
   WELCOME
====================================================== */

.welcome {

    margin-bottom: 25px;

}


.welcome small {

    color: #777;

    font-size: 13px;

}


.welcome h1 {

    color: #0b5d1e;

    font-size: 31px;

    margin-top: 5px;

}


.welcome p {

    color: #666;

    margin-top: 6px;

    font-size: 14px;

}


/* ======================================================
   PROFILE MAIN CARD
====================================================== */

.profile-card {

    background: white;

    border-radius: 20px;

    padding: 30px;

    display: grid;

    grid-template-columns:
        240px
        1fr;

    gap: 32px;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,.09);

    margin-bottom: 25px;

}


/* ======================================================
   PROFILE LEFT
====================================================== */

.profile-left {

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    text-align: center;

    border-right:
        1px solid #e6eee8;

    padding-right: 30px;

}

```php
<?php

$photo = trim(
    $member["profile_photo"] ?? ""
);

$photo_exists = false;

if ($photo !== "") {

    $photo_file =
        __DIR__ . "/" . $photo;

    if (file_exists($photo_file)) {

        $photo_exists = true;

    }

}

?>

<?php if ($photo_exists): ?>

<img
    src="<?php echo htmlspecialchars($photo); ?>"
    alt="Profile Photo"
    class="avatar"
    style="object-fit:cover;"
>

<?php else: ?>

<div class="avatar">

<?php
echo htmlspecialchars($initials);
?>

</div>

<?php endif; ?>


<form
    action="upload_photo.php"
    method="POST"
    enctype="multipart/form-data"
    style="margin-top:15px;"
>

<label
    for="profile_photo"
    style="
        display:inline-block;
        padding:9px 15px;
        background:#0b5d1e;
        color:white;
        border-radius:8px;
        cursor:pointer;
        font-size:12px;
    "
>
    📷
    <?php
    echo $photo_exists
        ? "Change Photo"
        : "Add Photo";
    ?>
</label>


<input
    type="file"
    id="profile_photo"
    name="profile_photo"
    accept="image/jpeg,image/png,image/webp"
    style="display:none;"
    onchange="this.form.submit();"
>

</form>
```



/* ======================================================
   DETAILS
====================================================== */

.profile-details {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 14px;

}


.detail {

    background: #f7fbf8;

    border:
        1px solid
        #e4eee6;

    padding: 15px;

    border-radius: 11px;

}


.detail-label {

    display: block;

    color: #7a7a7a;

    font-size: 11px;

    text-transform: uppercase;

    letter-spacing: .6px;

    margin-bottom: 6px;

}


.detail-value {

    color: #222;

    font-size: 14px;

    font-weight: bold;

    word-break: break-word;

}


/* ======================================================
   STATUS
====================================================== */

.status {

    display: inline-block;

    padding:
        5px
        10px;

    border-radius: 20px;

    background: #e2f5e6;

    color: #176b2b;

    font-size: 12px;

}


/* ======================================================
   POINTS
====================================================== */

.points-card {

    background: white;

    border-radius: 20px;

    padding:
        25px
        30px;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,.09);

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;

}


.points-heading {

    color: #777;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: 1px;

}


.points-number {

    color: #0b5d1e;

    font-size: 46px;

    font-weight: bold;

    margin-top: 3px;

}


.points-text {

    color: #888;

    font-size: 13px;

}


.points-icon {

    width: 70px;

    height: 70px;

    border-radius: 50%;

    background: #f0f8f1;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 34px;

}


/* ======================================================
   QR CARD
====================================================== */

.qr-card {

    background: white;

    border-radius: 20px;

    padding: 30px;

    text-align: center;

    box-shadow:
        0 7px 25px
        rgba(0,0,0,.09);

    margin-bottom: 25px;

}


.qr-card h2 {

    color: #0b5d1e;

    font-size: 22px;

    margin-bottom: 7px;

}


.qr-card p {

    color: #777;

    font-size: 14px;

    margin-bottom: 20px;

}


.qr-box {

    width: 190px;

    height: 190px;

    margin:
        0 auto;

    border:
        3px dashed
        #0b5d1e;

    border-radius: 15px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #0b5d1e;

    font-weight: bold;

}


.qr-note {

    color: #999;

    font-size: 12px;

    margin-top: 15px;

}


/* ======================================================
   ACTIONS
====================================================== */

.actions {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 15px;

    margin-bottom: 25px;

}


.action {

    background: white;

    border-radius: 15px;

    padding: 18px;

    text-align: center;

    text-decoration: none;

    color: #0b5d1e;

    font-weight: bold;

    box-shadow:
        0 5px 18px
        rgba(0,0,0,.08);

    transition: .2s;

}


.action:hover {

    transform: translateY(-2px);

    box-shadow:
        0 8px 20px
        rgba(0,0,0,.12);

}


.action-icon {

    display: block;

    font-size: 25px;

    margin-bottom: 7px;

}


/* ======================================================
   FOOTER
====================================================== */

footer {

    background: #0b5d1e;

    color: white;

    text-align: center;

    padding: 17px;

    font-size: 12px;

}


/* ======================================================
   MOBILE
====================================================== */

@media (max-width: 700px) {

    .header {

        padding:
            13px
            4%;

    }


    .header-title {

        font-size: 15px;

    }


    .header-subtitle {

        font-size: 9px;

    }


    .logout {

        padding:
            8px
            11px;

        font-size: 12px;

    }


    .container {

        width: 94%;

        margin-top: 25px;

    }


    .welcome h1 {

        font-size: 25px;

    }


    .profile-card {

        grid-template-columns: 1fr;

        padding: 22px;

    }


    .profile-left {

        border-right: none;

        border-bottom:
            1px solid #e6eee8;

        padding-right: 0;

        padding-bottom: 25px;

    }


    .profile-details {

        grid-template-columns: 1fr;

    }


    .points-card {

        padding: 22px;

    }


    .points-number {

        font-size: 38px;

    }


    .actions {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- ====================================================
     HEADER
===================================================== -->

<header class="header">

<div class="header-brand">


<div class="header-logo">
TQ
</div>


<div>

<div class="header-title">
TOLOBA QUTBI MOHALLA
</div>

<div class="header-subtitle">
MEMBER PORTAL
</div>

</div>


</div>


<a
    href="member_logout.php"
    class="logout"
>
Logout
</a>


</header>



<!-- ====================================================
     MAIN
===================================================== -->

<main class="container">


<!-- WELCOME -->

<section class="welcome">

<small>
MEMBER PROFILE
</small>

<h1>

Welcome,
<?php echo htmlspecialchars($full_name); ?>

</h1>

<p>
Your personal Toloba Qutbi Mohalla member account
</p>

</section>



<!-- ====================================================
     PROFILE
===================================================== -->

<section class="profile-card">


<div class="profile-left">


<div class="avatar">

<?php echo htmlspecialchars($initials); ?>

</div>


<h2>

<?php echo htmlspecialchars($full_name); ?>

</h2>


<div class="designation">

<?php echo showValue($designation); ?>

</div>


</div>



<div class="profile-details">


<div class="detail">

<span class="detail-label">
ITS Number
</span>

<span class="detail-value">

<?php echo showValue($its_id); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Contact Number
</span>

<span class="detail-value">

<?php echo showValue($contact); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Age
</span>

<span class="detail-value">

<?php echo showValue($age); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Jamiat
</span>

<span class="detail-value">

<?php echo showValue($jamiat); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Jamaat
</span>

<span class="detail-value">

<?php echo showValue($jamaat); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Zone
</span>

<span class="detail-value">

<?php echo showValue($zone); ?>

</span>

</div>



<div class="detail">

<span class="detail-label">
Account Status
</span>

<span class="detail-value">

<span class="status">

<?php echo showValue($status); ?>

</span>

</span>

</div>



<div class="detail">

<span class="detail-label">
Member ID
</span>

<span class="detail-value">

#<?php echo (int)$member["id"]; ?>

</span>

</div>


</div>

</section>



<!-- ====================================================
     POINTS
===================================================== -->

<section class="points-card">


<div>

<div class="points-heading">
My Points
</div>


<div class="points-number">

<?php echo $points; ?>

</div>


<div class="points-text">
Total Points Earned
</div>

</div>


<div class="points-icon">
🏆
</div>


</section>



<!-- ====================================================
     QR CODE
===================================================== -->

<section class="qr-card">


<h2>
My Member QR
</h2>


<p>
Show your personal QR code to earn points.
</p>


<div
    id="qrcode"
    style="
        width:190px;
        height:190px;
        margin:0 auto;
        display:flex;
        align-items:center;
        justify-content:center;
    "
></div>

<div class="qr-note">

Your unique QR code and 5-point
scanning system will be activated next.

</div>


</section>



<!-- ====================================================
     ACTIONS
===================================================== -->

<section class="actions">


<a
    href="change_password.php"
    class="action"
>

<span class="action-icon">
🔐
</span>

Change Password

</a>

<a
    href="points_history.php"
    class="action"
>
    <span class="action-icon">🏆</span>
    Points History
</a><a
    href="scan.php"
    class="action"
>
    <span class="action-icon">📷</span>
    Scan Member QR
</a><a
    href="member_card.php"
    class="action"
>
    <span class="action-icon">🪪</span>
    Digital Member Card
</a>
<a
    href="member_logout.php"
    class="action"
>

<span class="action-icon">
🚪
</span>

Logout

</a>


</section>


</main>



<!-- ====================================================
     FOOTER
===================================================== -->

<footer>

© TOLOBA QUTBI MOHALLA DAHOD

<br>

All Rights Reserved

</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>

new QRCode(
    document.getElementById("qrcode"),
    {
        text:
            window.location.origin +
            "/scan_qr.php?token=<?php echo urlencode($member['qr_token']); ?>",

        width: 190,

        height: 190,

        correctLevel: QRCode.CorrectLevel.H
    }
);

</script>

</body>

</html>
```

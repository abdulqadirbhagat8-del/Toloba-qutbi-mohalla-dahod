<?php
include("config.php");

$result = mysqli_query($conn,"SELECT * FROM contact WHERE id=1");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact | TOLOBA QUTBI MOHALLA</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#e8f5e9;
}

header{
background:#0b5d1e;
color:white;
text-align:center;
padding:25px;
font-size:34px;
font-weight:bold;
}

.title{
text-align:center;
margin:30px;
font-size:30px;
font-weight:bold;
color:#0b5d1e;
}

.container{
width:90%;
max-width:900px;
margin:auto;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
gap:25px;
}

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
border-top:8px solid #0b5d1e;
}

.card h2{
color:#0b5d1e;
margin-bottom:15px;
text-align:center;
}

.card p{
margin:10px 0;
font-size:16px;
line-height:1.6;
}

.instagram{
margin:40px auto;
width:90%;
max-width:500px;
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
text-align:center;
border-top:8px solid #0b5d1e;
}

.instagram h2{
color:#0b5d1e;
margin-bottom:15px;
}

.instagram a{
text-decoration:none;
color:#0b5d1e;
font-size:18px;
font-weight:bold;
}

.back{
text-align:center;
margin:35px;
}

.back a{
background:#0b5d1e;
color:white;
padding:12px 25px;
text-decoration:none;
border-radius:8px;
font-size:17px;
}

.back a:hover{
background:#146c2d;
}

footer{
margin-top:40px;
background:#0b5d1e;
color:white;
text-align:center;
padding:18px;
}

</style>

</head>

<body>

<header>
TOLOBA QUTBI MOHALLA
</header>

<div class="title">
CONTACT US
</div>

<div class="container">

<div class="card">
<h2>Secretary</h2>

<p><strong>Name:</strong><br><?php echo htmlspecialchars($row['secretary_name']); ?></p>

<p><strong>Contact:</strong><br><?php echo htmlspecialchars($row['secretary_contact']); ?></p>

<p><strong>Email:</strong><br><?php echo htmlspecialchars($row['secretary_email']); ?></p>
</div>

<div class="card">
<h2>Joint Secretary</h2>

<p><strong>Name:</strong><br><?php echo htmlspecialchars($row['joint_secretary_name']); ?></p>

<p><strong>Contact:</strong><br><?php echo htmlspecialchars($row['joint_secretary_contact']); ?></p>

<p><strong>Email:</strong><br><?php echo htmlspecialchars($row['joint_secretary_email']); ?></p>
</div>

<div class="card">
<h2>Treasurer</h2>

<p><strong>Name:</strong><br><?php echo htmlspecialchars($row['treasurer_name']); ?></p>

<p><strong>Contact:</strong><br><?php echo htmlspecialchars($row['treasurer_contact']); ?></p>

<p><strong>Email:</strong><br><?php echo htmlspecialchars($row['treasurer_email']); ?></p>
</div>

<div class="card">
<h2>Joint Treasurer</h2>

<p><strong>Name:</strong><br><?php echo htmlspecialchars($row['it_admin_name']); ?></p>

<p><strong>Contact:</strong><br><?php echo htmlspecialchars($row['it_admin_contact']); ?></p>

<p><strong>Email:</strong><br><?php echo htmlspecialchars($row['it_admin_email']); ?></p>
</div>

</div>

<div class="instagram">

<h2>Instagram</h2>

<a href="https://instagram.com/<?php echo htmlspecialchars($row['instagram']); ?>" target="_blank">

@<?php echo htmlspecialchars($row['instagram']); ?>

</a>

</div>

<div class="back">

<a href="index.php">

← Back to Home

</a>

</div>

<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>
</html>
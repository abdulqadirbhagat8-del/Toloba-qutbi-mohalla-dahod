<?php
include("config.php");

$result=mysqli_query($conn,"SELECT * FROM miqat_updates WHERE id=1");
$row=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Miqat Khidmat Updates</title>

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
color:#0b5d1e;
font-weight:bold;
}

.container{
width:90%;
max-width:1000px;
margin:auto;
}

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
margin-bottom:25px;
}

.card h2{
color:#0b5d1e;
margin-bottom:15px;
border-bottom:2px solid #0b5d1e;
padding-bottom:10px;
}

.card p{
white-space:pre-wrap;
line-height:1.8;
font-size:17px;
}

.back{
text-align:center;
margin:35px;
}

.back a{
background:#0b5d1e;
color:white;
padding:12px 30px;
text-decoration:none;
border-radius:8px;
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

MIQAT KHIDMAT UPDATES

</div>

<div class="container">

<div class="card">
<h2>Important Message</h2>
<p><?php echo htmlspecialchars($row['important_message']); ?></p>
</div>

<div class="card">
<h2>Jaman Posting</h2>
<p><?php echo htmlspecialchars($row['jaman_posting']); ?></p>
</div>

<div class="card">
<h2>Duty Posting</h2>
<p><?php echo htmlspecialchars($row['duty_posting']); ?></p>
</div>

</div>

<div class="back">
<a href="index.php">← Back to Home</a>
</div>

<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>
</html>
<?php
include("config.php");

$result = mysqli_query($conn,"SELECT * FROM postholders ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post Holders | TOLOBA QUTBI MOHALLA</title>

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
margin:auto;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:25px;
padding-bottom:40px;
}

.card{
background:white;
padding:20px;
border-radius:15px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,.15);
border-top:8px solid #0b5d1e;
}

.card img{
width:130px;
height:150px;
object-fit:cover;
border-radius:10px;
border:3px solid #0b5d1e;
margin-bottom:15px;
}

.card h2{
color:#0b5d1e;
margin-bottom:10px;
font-size:22px;
}

.card p{
margin:8px 0;
font-size:15px;
}

.back{
text-align:center;
margin:30px;
}

.back a{
background:#0b5d1e;
color:white;
padding:12px 25px;
text-decoration:none;
border-radius:8px;
}

footer{
background:#0b5d1e;
color:white;
text-align:center;
padding:18px;
margin-top:30px;
}

</style>

</head>

<body>

<header>

TOLOBA QUTBI MOHALLA

</header>

<div class="title">

POST HOLDERS

</div>

<div class="container">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="card">

<?php if($row['photo']!=""){ ?>

<img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>">

<?php }else{ ?>

<img src="https://via.placeholder.com/130x150?text=Photo">

<?php } ?>

<h2><?php echo htmlspecialchars($row['name']); ?></h2>

<p><b>Designation:</b><br><?php echo htmlspecialchars($row['designation']); ?></p>

<p><b>ITS No:</b><br><?php echo htmlspecialchars($row['its_no']); ?></p>

<p><b>Contact:</b><br><?php echo htmlspecialchars($row['contact_no']); ?></p>

<p><b>WhatsApp:</b><br><?php echo htmlspecialchars($row['whatsapp_no']); ?></p>

</div>

<?php } ?>

</div>

<div class="back">

<a href="index.php">← Back to Home</a>

</div>

<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>
</html>
<?php
include("config.php");

$result = mysqli_query($conn,"SELECT * FROM ashara WHERE id=1");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ashara 1449H</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#eef7ee;
}

/* HEADER */

header{

background:#0b5d1e;
color:white;
padding:20px;
text-align:center;
font-size:32px;
font-weight:bold;

}

/* TITLE */

.title{

text-align:center;
font-size:34px;
margin:30px 0;
color:#0b5d1e;
font-weight:bold;

}

/* CARD */

.card{

width:90%;
max-width:900px;
margin:auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);

}

table{

width:100%;
border-collapse:collapse;

}

td{

padding:16px;
border-bottom:1px solid #ddd;
font-size:18px;

}

td:first-child{

font-weight:bold;
width:35%;
color:#0b5d1e;
background:#f3fff3;

}

/* NOTES */

.notes{

margin-top:30px;
padding:20px;
background:#f9fff9;
border-left:6px solid #0b5d1e;
line-height:1.8;
font-size:17px;

}

/* BUTTON */

.back{

text-align:center;
margin:40px;

}

.back a{

background:#0b5d1e;
color:white;
padding:14px 30px;
text-decoration:none;
border-radius:8px;
font-weight:bold;

}

.back a:hover{

background:#15742d;

}

/* FOOTER */

footer{

background:#0b5d1e;
color:white;
text-align:center;
padding:20px;
margin-top:40px;

}

</style>

</head>

<body>

<header>

TOLOBA QUTBI MOHALLA

</header>

<div class="title">

<?php echo $row['title']; ?>

</div>

<div class="card">

<table>

<tr>
<td>Hijri Date</td>
<td><?php echo $row['hijri_date']; ?></td>
</tr>

<tr>
<td>English Date</td>
<td><?php echo $row['english_date']; ?></td>
</tr>

<tr>
<td>Venue</td>
<td><?php echo $row['venue']; ?></td>
</tr>

<tr>
<td>Waaz Time</td>
<td><?php echo $row['waaz_time']; ?></td>
</tr>

<tr>
<td>Breakfast</td>
<td><?php echo $row['breakfast']; ?></td>
</tr>

<tr>
<td>Lunch</td>
<td><?php echo $row['lunch']; ?></td>
</tr>

<tr>
<td>Dinner</td>
<td><?php echo $row['dinner']; ?></td>
</tr>

</table>

<div class="notes">

<?php echo nl2br($row['notes']); ?>

</div>

</div>

<div class="back">

<a href="index.php">← Back to Home</a>

</div>

<footer>

All Rights Reserved © <?php echo date("Y"); ?> TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>

</html>
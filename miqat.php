<?php
include("config.php");

$result = mysqli_query($conn,"SELECT * FROM miqat WHERE id=1");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Miqat - TOLOBA QUTBI MOHALLA</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial, Helvetica, sans-serif;
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
letter-spacing:2px;
box-shadow:0 4px 10px rgba(0,0,0,.3);
}

.page-title{
text-align:center;
margin:30px 0 20px;
color:#0b5d1e;
font-size:30px;
font-weight:bold;
}

.table-container{
width:90%;
max-width:900px;
margin:auto;
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 6px 15px rgba(0,0,0,.15);
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#0b5d1e;
color:white;
padding:15px;
font-size:18px;
}

td{
border:1px solid #cfcfcf;
padding:15px;
font-size:17px;
}

tr:nth-child(even){
background:#f4fff4;
}

.label{
font-weight:bold;
width:35%;
background:#eef8ee;
}

.back{
text-align:center;
margin:30px;
}

.back a{
text-decoration:none;
background:#0b5d1e;
color:white;
padding:12px 30px;
border-radius:8px;
font-size:18px;
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
font-size:15px;
}

@media(max-width:768px){

.page-title{
font-size:24px;
}

td,th{
font-size:15px;
padding:12px;
}

}

</style>

</head>

<body>

<header>
TOLOBA QUTBI MOHALLA
</header>

<div class="page-title">
UPCOMING MIQAT
</div>

<div class="table-container">

<table>

<tr>
<th colspan="2">Upcoming Miqat Details</th>
</tr>

<tr>
<td class="label">Miqat Name</td>
<td><?php echo htmlspecialchars($row['miqat_name']); ?></td>
</tr>

<tr>
<td class="label">Hijri Date</td>
<td><?php echo htmlspecialchars($row['hijri_date']); ?></td>
</tr>

<tr>
<td class="label">English Date</td>
<td><?php echo htmlspecialchars($row['english_date']); ?></td>
</tr>

<tr>
<td class="label">Venue</td>
<td><?php echo htmlspecialchars($row['venue']); ?></td>
</tr>

<tr>
<td class="label">Miqat Incharge</td>
<td><?php echo htmlspecialchars($row['incharge']); ?></td>
</tr>

</table>

</div>

<div class="back">
<a href="index.php">← Back to Home</a>
</div>

<footer>
All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD
</footer>

</body>
</html>
<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message="";

// SAVE DATA
if(isset($_POST['save']))
{
    $miqat_name=$_POST['miqat_name'];
    $hijri_date=$_POST['hijri_date'];
    $english_date=$_POST['english_date'];
    $venue=$_POST['venue'];
    $incharge=$_POST['incharge'];

    mysqli_query($conn,"
    UPDATE miqat SET
    miqat_name='$miqat_name',
    hijri_date='$hijri_date',
    english_date='$english_date',
    venue='$venue',
    incharge='$incharge'
    WHERE id=1
    ");

    $message="Miqat Updated Successfully!";
}

// FETCH DATA

$result=mysqli_query($conn,"SELECT * FROM miqat WHERE id=1");
$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Manage Miqat</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#e8f5e9;
}

header{

background:#0b5d1e;
color:white;
padding:22px;
text-align:center;
font-size:32px;
font-weight:bold;

}

.container{

width:70%;
margin:40px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 0 15px rgba(0,0,0,.2);

}

.success{

background:#d4edda;
color:#155724;
padding:12px;
border-radius:8px;
margin-bottom:20px;
text-align:center;
font-weight:bold;

}

label{

display:block;
margin-top:18px;
font-weight:bold;
color:#0b5d1e;

}

input{

width:100%;
padding:12px;
margin-top:8px;
font-size:16px;
border:1px solid #ccc;
border-radius:8px;

}button{

margin-top:30px;
width:100%;
padding:15px;
background:#0b5d1e;
color:white;
border:none;
font-size:18px;
font-weight:bold;
border-radius:8px;
cursor:pointer;

}

button:hover{

background:#146c2d;

}

.back{

display:inline-block;
margin-top:20px;
text-decoration:none;
background:#444;
color:white;
padding:10px 20px;
border-radius:8px;

}

footer{

margin-top:40px;
background:#0b5d1e;
color:white;
text-align:center;
padding:15px;

}

@media(max-width:768px){

.container{

width:95%;

}

}

</style>

</head>

<body>

<header>

MANAGE UPCOMING MIQAT

</header>

<div class="container">

<?php

if($message!="")
{

echo "<div class='success'>$message</div>";

}

?>

<form method="POST">

<label>Miqat Name</label>

<input
type="text"
name="miqat_name"
value="<?php echo $row['miqat_name']; ?>"
required>

<label>Hijri Date</label>

<input
type="text"
name="hijri_date"
value="<?php echo $row['hijri_date']; ?>"
required>

<label>English Date</label>

<input
type="text"
name="english_date"
value="<?php echo $row['english_date']; ?>"
required>

<label>Venue</label>

<input
type="text"
name="venue"
value="<?php echo $row['venue']; ?>"
required>

<label>Miqat Incharge</label>

<input
type="text"
name="incharge"
value="<?php echo $row['incharge']; ?>"
required><button type="submit" name="save">
SAVE CHANGES
</button>

</form>

<br>

<a href="admin.php" class="back">
← Back to Admin Dashboard
</a>

</div>

<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>

</html>
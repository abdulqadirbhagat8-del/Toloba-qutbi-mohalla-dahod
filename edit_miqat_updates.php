<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
header("Location:login.php");
exit();
}

$message="";

if(isset($_POST['save'])){

$important=mysqli_real_escape_string($conn,$_POST['important']);

$jaman=mysqli_real_escape_string($conn,$_POST['jaman']);

$duty=mysqli_real_escape_string($conn,$_POST['duty']);

mysqli_query($conn,"
UPDATE miqat_updates

SET

important_message='$important',

jaman_posting='$jaman',

duty_posting='$duty'

WHERE id=1
");

$message="Miqat Updates Saved Successfully.";

}

$result=mysqli_query($conn,"SELECT * FROM miqat_updates WHERE id=1");

$row=mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Manage Miqat Updates</title>

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
width:90%;
max-width:1000px;
margin:30px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.2);
}

.success{
background:#d4edda;
color:#155724;
padding:12px;
margin-bottom:20px;
border-radius:8px;
text-align:center;
font-weight:bold;
}

label{
display:block;
margin-top:20px;
font-weight:bold;
color:#0b5d1e;
}

textarea{

width:100%;

height:250px;

padding:15px;

font-size:16px;

border-radius:8px;

border:1px solid #ccc;

margin-top:8px;

resize:vertical;

}button{
width:100%;
margin-top:30px;
padding:15px;
background:#0b5d1e;
color:white;
border:none;
border-radius:8px;
font-size:18px;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#146c2d;
}

.back{
display:inline-block;
margin-top:25px;
padding:10px 20px;
background:#444;
color:white;
text-decoration:none;
border-radius:8px;
}

footer{
margin-top:40px;
background:#0b5d1e;
color:white;
text-align:center;
padding:15px;
}

</style>

</head>

<body>

<header>

MANAGE MIQAT KHIDMAT UPDATES

</header>

<div class="container">

<?php
if($message!=""){
echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<label>Important Message</label>

<textarea
name="important"
required><?php echo htmlspecialchars($row['important_message']); ?></textarea>

<label>Jaman Posting</label>

<textarea
name="jaman"
required><?php echo htmlspecialchars($row['jaman_posting']); ?></textarea>

<label>Duty Posting</label>

<textarea
name="duty"
required><?php echo htmlspecialchars($row['duty_posting']); ?></textarea>

<button type="submit" name="save">

SAVE MIQAT UPDATES

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
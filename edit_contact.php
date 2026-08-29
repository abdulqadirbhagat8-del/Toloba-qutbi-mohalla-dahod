<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message="";

if(isset($_POST['save'])){

$secretary_name=mysqli_real_escape_string($conn,$_POST['secretary_name']);
$secretary_contact=mysqli_real_escape_string($conn,$_POST['secretary_contact']);
$secretary_email=mysqli_real_escape_string($conn,$_POST['secretary_email']);

$joint_secretary_name=mysqli_real_escape_string($conn,$_POST['joint_secretary_name']);
$joint_secretary_contact=mysqli_real_escape_string($conn,$_POST['joint_secretary_contact']);
$joint_secretary_email=mysqli_real_escape_string($conn,$_POST['joint_secretary_email']);

$treasurer_name=mysqli_real_escape_string($conn,$_POST['treasurer_name']);
$treasurer_contact=mysqli_real_escape_string($conn,$_POST['treasurer_contact']);
$treasurer_email=mysqli_real_escape_string($conn,$_POST['treasurer_email']);

$it_admin_name=mysqli_real_escape_string($conn,$_POST['it_admin_name']);
$it_admin_contact=mysqli_real_escape_string($conn,$_POST['it_admin_contact']);
$it_admin_email=mysqli_real_escape_string($conn,$_POST['it_admin_email']);

$instagram=mysqli_real_escape_string($conn,$_POST['instagram']);

mysqli_query($conn,"
UPDATE contact SET

secretary_name='$secretary_name',
secretary_contact='$secretary_contact',
secretary_email='$secretary_email',

joint_secretary_name='$joint_secretary_name',
joint_secretary_contact='$joint_secretary_contact',
joint_secretary_email='$joint_secretary_email',

treasurer_name='$treasurer_name',
treasurer_contact='$treasurer_contact',
treasurer_email='$treasurer_email',

it_admin_name='$it_admin_name',
it_admin_contact='$it_admin_contact',
it_admin_email='$it_admin_email',

instagram='$instagram'

WHERE id=1
");

$message="Contact Details Updated Successfully.";

}

$result=mysqli_query($conn,"SELECT * FROM contact WHERE id=1");
$row=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Manage Contact</title>

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
padding:22px;
text-align:center;
font-size:32px;
font-weight:bold;
}

.container{
width:90%;
max-width:900px;
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
border-radius:8px;
margin-bottom:20px;
text-align:center;
font-weight:bold;
}

label{
display:block;
margin-top:15px;
font-weight:bold;
color:#0b5d1e;
}

input{
width:100%;
padding:12px;
margin-top:6px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;
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

MANAGE CONTACT DETAILS

</header>

<div class="container">

<?php
if($message!=""){
echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<label>Secretary Name</label>
<input type="text" name="secretary_name" value="<?php echo htmlspecialchars($row['secretary_name']); ?>" required>

<label>Secretary Contact</label>
<input type="text" name="secretary_contact" value="<?php echo htmlspecialchars($row['secretary_contact']); ?>" required>

<label>Secretary Email</label>
<input type="email" name="secretary_email" value="<?php echo htmlspecialchars($row['secretary_email']); ?>" required>

<label>Joint Secretary Name</label>
<input type="text" name="joint_secretary_name" value="<?php echo htmlspecialchars($row['joint_secretary_name']); ?>" required>

<label>Joint Secretary Contact</label>
<input type="text" name="joint_secretary_contact" value="<?php echo htmlspecialchars($row['joint_secretary_contact']); ?>" required>

<label>Joint Secretary Email</label>
<input type="email" name="joint_secretary_email" value="<?php echo htmlspecialchars($row['joint_secretary_email']); ?>" required>

<label>Treasurer Name</label>
<input type="text" name="treasurer_name" value="<?php echo htmlspecialchars($row['treasurer_name']); ?>" required>

<label>Treasurer Contact</label>
<input type="text" name="treasurer_contact" value="<?php echo htmlspecialchars($row['treasurer_contact']); ?>" required>

<label>Treasurer Email</label>
<input type="email" name="treasurer_email" value="<?php echo htmlspecialchars($row['treasurer_email']); ?>" required>

<label>IT Admin Name</label>
<input type="text" name="it_admin_name" value="<?php echo htmlspecialchars($row['it_admin_name']); ?>" required>

<label>IT Admin Contact</label>
<input type="text" name="it_admin_contact" value="<?php echo htmlspecialchars($row['it_admin_contact']); ?>" required>

<label>IT Admin Email</label>
<input type="email" name="it_admin_email" value="<?php echo htmlspecialchars($row['it_admin_email']); ?>" required>

<label>Instagram Username</label>
<input type="text" name="instagram" value="<?php echo htmlspecialchars($row['instagram']); ?>" required>

<button type="submit" name="save">

SAVE CONTACT DETAILS

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
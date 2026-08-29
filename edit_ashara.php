<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message="";

if(isset($_POST['save']))
{
    $title=$_POST['title'];
    $hijri_date=$_POST['hijri_date'];
    $english_date=$_POST['english_date'];
    $venue=$_POST['venue'];
    $waaz_time=$_POST['waaz_time'];
    $breakfast=$_POST['breakfast'];
    $lunch=$_POST['lunch'];
    $dinner=$_POST['dinner'];
    $notes=$_POST['notes'];

    mysqli_query($conn,"
    UPDATE ashara SET
    title='$title',
    hijri_date='$hijri_date',
    english_date='$english_date',
    venue='$venue',
    waaz_time='$waaz_time',
    breakfast='$breakfast',
    lunch='$lunch',
    dinner='$dinner',
    notes='$notes'
    WHERE id=1
    ");

    $message="Ashara Details Updated Successfully!";
}

$result=mysqli_query($conn,"SELECT * FROM ashara WHERE id=1");
$row=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<title>Edit Ashara</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#eef7ee;
}

header{
background:#0b5d1e;
color:white;
padding:22px;
text-align:center;
font-size:30px;
font-weight:bold;
}

.container{
width:80%;
max-width:900px;
margin:40px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 0 15px rgba(0,0,0,.15);
}

.success{
background:#d4edda;
color:#155724;
padding:12px;
margin-bottom:20px;
border-radius:8px;
font-weight:bold;
text-align:center;
}

label{
display:block;
margin-top:18px;
font-weight:bold;
color:#0b5d1e;
}

input,
textarea{
width:100%;
padding:12px;
margin-top:8px;
font-size:16px;
border:1px solid #ccc;
border-radius:8px;
}

textarea{
height:150px;
resize:vertical;
}

button{
margin-top:25px;
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
background:#15742d;
}

.back{
display:inline-block;
margin-top:20px;
background:#555;
color:white;
text-decoration:none;
padding:12px 22px;
border-radius:8px;
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

EDIT ASHARA DETAILS

</header>

<div class="container">

<?php
if($message!=""){
echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<label>Title</label>
<input type="text" name="title" value="<?php echo $row['title']; ?>" required>

<label>Hijri Date</label>
<input type="text" name="hijri_date" value="<?php echo $row['hijri_date']; ?>" required>

<label>English Date</label>
<input type="text" name="english_date" value="<?php echo $row['english_date']; ?>" required>

<label>Venue</label>
<input type="text" name="venue" value="<?php echo $row['venue']; ?>" required>

<label>Waaz Time</label>
<input type="text" name="waaz_time" value="<?php echo $row['waaz_time']; ?>" required>

<label>Breakfast</label>
<input type="text" name="breakfast" value="<?php echo $row['breakfast']; ?>" required>

<label>Lunch</label>
<input type="text" name="lunch" value="<?php echo $row['lunch']; ?>" required>

<label>Dinner</label>
<input type="text" name="dinner" value="<?php echo $row['dinner']; ?>" required>

<label>Notes</label>
<textarea name="notes"><?php echo $row['notes']; ?></textarea>

<button type="submit" name="save">
SAVE CHANGES
</button>

</form>

<br>

<a class="back" href="admin.php">
← Back to Admin Dashboard
</a>

</div>

<footer>

All Rights Reserved © <?php echo date("Y"); ?> TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>
</html>
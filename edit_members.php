<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message="";

// Delete Member
if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];

    $res=mysqli_query($conn,"SELECT photo FROM members WHERE id=$id");
    if($r=mysqli_fetch_assoc($res)){
        if($r['photo']!="" && file_exists("uploads/".$r['photo'])){
            unlink("uploads/".$r['photo']);
        }
    }

    mysqli_query($conn,"DELETE FROM members WHERE id=$id");
    $message="Member deleted successfully.";
}

// Add Member
if(isset($_POST['save'])){

    $name=mysqli_real_escape_string($conn,$_POST['name']);
    $designation=mysqli_real_escape_string($conn,$_POST['designation']);
    $its_no=mysqli_real_escape_string($conn,$_POST['its_no']);
    $contact_no=mysqli_real_escape_string($conn,$_POST['contact_no']);
    $whatsapp_no=mysqli_real_escape_string($conn,$_POST['whatsapp_no']);

    $photo="";

    if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

        $photo=time()."_".basename($_FILES['photo']['name']);

        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "uploads/".$photo
        );
    }

    mysqli_query($conn,"
    INSERT INTO members
    (photo,name,designation,its_no,contact_no,whatsapp_no)
    VALUES
    ('$photo','$name','$designation','$its_no','$contact_no','$whatsapp_no')
    ");

    $message="Member added successfully.";
}

$result=mysqli_query($conn,"SELECT * FROM members ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Manage Members</title>

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
padding:22px;
font-size:32px;
font-weight:bold;
}

.container{
width:92%;
max-width:1100px;
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
}

input[type=file]{
padding:8px;
}button{
width:100%;
margin-top:25px;
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

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:20px;
margin-top:40px;
}

.card{
background:#f8fff8;
border-radius:12px;
padding:20px;
text-align:center;
box-shadow:0 4px 10px rgba(0,0,0,.15);
}

.card img{
width:120px;
height:140px;
object-fit:cover;
border-radius:8px;
border:3px solid #0b5d1e;
margin-bottom:15px;
}

.card h3{
color:#0b5d1e;
margin-bottom:10px;
}

.card p{
margin:6px 0;
font-size:15px;
}

.delete{
display:inline-block;
margin-top:15px;
padding:10px 20px;
background:#c62828;
color:white;
text-decoration:none;
border-radius:8px;
font-weight:bold;
}

.delete:hover{
background:#a00000;
}

.back{
display:inline-block;
margin-top:30px;
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

MANAGE MEMBERS

</header>

<div class="container">

<?php
if($message!=""){
echo "<div class='success'>$message</div>";
}
?>

<form method="POST" enctype="multipart/form-data">

<label>Passport Size Photo</label>
<input type="file" name="photo" accept="image/*">

<label>Member Name</label>
<input type="text" name="name" required>

<label>Designation</label>
<input type="text" name="designation" required>

<label>ITS Number</label>
<input type="text" name="its_no" required>

<label>Contact Number</label>
<input type="text" name="contact_no" required>

<label>WhatsApp Number</label>
<input type="text" name="whatsapp_no" required>

<button type="submit" name="save">

ADD MEMBER

</button>

</form>

<h2 style="margin-top:40px;text-align:center;color:#0b5d1e;">

Current Members

</h2>

<div class="cards"><?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="card">

<?php if($row['photo']!=""){ ?>

<img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Member Photo">

<?php } else { ?>

<img src="https://via.placeholder.com/120x140?text=Photo" alt="No Photo">

<?php } ?>

<h3><?php echo htmlspecialchars($row['name']); ?></h3>

<p><strong>Designation:</strong><br>
<?php echo htmlspecialchars($row['designation']); ?></p>

<p><strong>ITS No.:</strong><br>
<?php echo htmlspecialchars($row['its_no']); ?></p>

<p><strong>Contact No.:</strong><br>
<?php echo htmlspecialchars($row['contact_no']); ?></p>

<p><strong>WhatsApp No.:</strong><br>
<?php echo htmlspecialchars($row['whatsapp_no']); ?></p>

<a class="delete"
href="edit_members.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this member?');">

Delete

</a>

</div>

<?php } ?>

</div>

<br><br>

<a href="admin.php" class="back">
← Back to Admin Dashboard
</a>

</div>

<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>
</html>
<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include("config.php");

$message = "";

/* UPDATE NAVIGATION */

if (isset($_POST['update'])) {

    $id = intval($_POST['id']);

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title']
    );

    $url = mysqli_real_escape_string(
        $conn,
        $_POST['url']
    );

    $status = isset($_POST['status']) ? 1 : 0;

    $sort_order = intval($_POST['sort_order']);

    mysqli_query(
        $conn,
        "UPDATE navigation SET
        title='$title',
        url='$url',
        status='$status',
        sort_order='$sort_order'
        WHERE id=$id"
    );

    $message = "Navigation updated successfully!";
}


/* DELETE NAVIGATION */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query(
        $conn,
        "DELETE FROM navigation WHERE id=$id"
    );

    header("Location: edit_navigation.php");
    exit();
}


/* GET NAVIGATION */

$result = mysqli_query(
    $conn,
    "SELECT * FROM navigation
     ORDER BY sort_order ASC, id ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Navigation Settings</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,Helvetica,sans-serif;
}

body{
    background:#e8f5e9;
    color:#222;
}

header{
    background:#0b5d1e;
    color:white;
    text-align:center;
    padding:25px;
    font-size:30px;
    font-weight:bold;
}

.container{
    width:92%;
    max-width:1100px;
    margin:35px auto;
}

.message{
    background:#d4edda;
    color:#155724;
    padding:15px;
    border-radius:8px;
    margin-bottom:25px;
    text-align:center;
    font-weight:bold;
}

.card{
    background:white;
    padding:25px;
    margin-bottom:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.12);
}

.card h2{
    color:#0b5d1e;
    margin-bottom:20px;
}

.form-grid{
    display:grid;
    grid-template-columns:
        1fr 1fr 120px 120px;
    gap:15px;
    align-items:end;
}

label{
    display:block;
    color:#0b5d1e;
    font-weight:bold;
    margin-bottom:7px;
}

input[type=text],
input[type=number]{
    width:100%;
    padding:11px;
    border:1px solid #ccc;
    border-radius:7px;
    font-size:15px;
}

.checkbox{
    display:flex;
    align-items:center;
    gap:8px;
    height:42px;
}

.checkbox input{
    width:18px;
    height:18px;
}

button{
    width:100%;
    padding:11px;
    background:#0b5d1e;
    color:white;
    border:none;
    border-radius:7px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#084617;
}

.delete{
    display:inline-block;
    margin-top:15px;
    background:#c62828;
    color:white;
    padding:9px 15px;
    border-radius:7px;
    text-decoration:none;
}

.delete:hover{
    background:#a00000;
}

.info{
    background:#f1f8f2;
    padding:18px;
    border-radius:10px;
    margin-bottom:25px;
    line-height:1.7;
}

.back{
    display:inline-block;
    margin-top:10px;
    margin-bottom:40px;
    padding:12px 22px;
    background:#444;
    color:white;
    text-decoration:none;
    border-radius:8px;
}

footer{
    background:#0b5d1e;
    color:white;
    text-align:center;
    padding:18px;
}

@media(max-width:800px){

    .form-grid{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<header>

TOLOBA QUTBI MOHALLA

<br>

<span style="font-size:17px;font-weight:normal;">
Navigation Settings
</span>

</header>


<div class="container">


<?php

if($message!=""){

    echo "
    <div class='message'>
        ".htmlspecialchars($message)."
    </div>
    ";

}

?>


<div class="info">

<strong>Navigation Manager</strong>

<br>

Here you can change the navigation
button name, URL, order and visibility.

<br><br>

<strong>Status ON</strong> =
navigation item is visible.

<br>

<strong>Status OFF</strong> =
navigation item is hidden.

</div>


<?php

while($row = mysqli_fetch_assoc($result)){

?>

<div class="card">

<h2>
Navigation Item #<?php echo $row['id']; ?>
</h2>


<form method="POST">

<input
type="hidden"
name="id"
value="<?php echo $row['id']; ?>"
>


<div class="form-grid">


<div>

<label>
Button Name
</label>

<input
type="text"
name="title"
value="<?php echo htmlspecialchars($row['title']); ?>"
required
>

</div>


<div>

<label>
URL
</label>

<input
type="text"
name="url"
value="<?php echo htmlspecialchars($row['url']); ?>"
required
>

</div>


<div>

<label>
Order
</label>

<input
type="number"
name="sort_order"
value="<?php echo $row['sort_order']; ?>"
min="1"
>

</div>


<div>

<label>
Status
</label>

<div class="checkbox">

<input
type="checkbox"
name="status"
<?php

if($row['status'] == 1){

    echo "checked";

}

?>
>

<span>
Visible
</span>

</div>

</div>


</div>


<button
type="submit"
name="update">

SAVE CHANGES

</button>


</form>


<a
class="delete"
href="edit_navigation.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this navigation item?');">

Delete

</a>


</div>

<?php

}

?>


<a
href="admin.php"
class="back">

← Back to Admin Dashboard

</a>


</div>


<footer>

All Rights Reserved ©
<?php echo date("Y"); ?>
TOLOBA QUTBI MOHALLA DAHOD

</footer>


</body>

</html>
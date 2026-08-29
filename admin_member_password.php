<?php

session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message = "";
$error = "";

$member = null;


/* SEARCH MEMBER */

if(isset($_POST['search_member'])){

    $its_id = mysqli_real_escape_string(
        $conn,
        $_POST['its_id']
    );

    $result = mysqli_query(
        $conn,
        "SELECT * FROM members
         WHERE its_id='$its_id'
         LIMIT 1"
    );

    if($result && mysqli_num_rows($result) > 0){

        $member = mysqli_fetch_assoc($result);

    }else{

        $error = "Member number not found.";

    }
}


/* CHANGE PASSWORD */

if(isset($_POST['change_password'])){

    $its_id = mysqli_real_escape_string(
        $conn,
        $_POST['its_id']
    );

    $password = $_POST['password'];

    if(strlen($password) < 4){

        $error = "Password must contain at least 4 characters.";

    }else{

        /*
        Password is securely hashed.
        */

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $query = mysqli_query(
            $conn,
            "UPDATE members
             SET password='$hashedPassword',
                 first_login=1
             WHERE its_id='$its_id'"
        );

        if($query){

            $message =
                "Member password changed successfully.";

        }else{

            $error =
                "Unable to change password.";

        }

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Member Password Reset
</title>

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

    font-size:30px;

    font-weight:bold;
}

.container{

    width:90%;

    max-width:600px;

    margin:40px auto;
}

.box{

    background:white;

    padding:30px;

    border-radius:15px;

    box-shadow:
        0 5px 20px rgba(0,0,0,.12);

}

h2{

    color:#0b5d1e;

    margin-bottom:20px;

    text-align:center;
}

label{

    display:block;

    margin-top:18px;

    font-weight:bold;

    color:#0b5d1e;
}

input{

    width:100%;

    padding:13px;

    margin-top:8px;

    border:1px solid #ccc;

    border-radius:8px;

    font-size:16px;
}

button{

    width:100%;

    margin-top:20px;

    padding:14px;

    border:none;

    border-radius:8px;

    background:#0b5d1e;

    color:white;

    font-size:17px;

    font-weight:bold;

    cursor:pointer;
}

button:hover{

    background:#084617;
}

.success{

    background:#d4edda;

    color:#155724;

    padding:12px;

    border-radius:8px;

    margin-bottom:15px;

    text-align:center;
}

.error{

    background:#f8d7da;

    color:#721c24;

    padding:12px;

    border-radius:8px;

    margin-bottom:15px;

    text-align:center;
}

.member-info{

    background:#f1f8f3;

    padding:20px;

    border-radius:10px;

    margin-top:20px;

}

.member-info p{

    margin:8px 0;
}

.back{

    display:block;

    text-align:center;

    margin-top:25px;

    color:#0b5d1e;

    text-decoration:none;

    font-weight:bold;
}

</style>

</head>

<body>

<header>

TOLOBA QUTBI MOHALLA

<br>

<span style="font-size:17px;font-weight:normal;">
MEMBER PASSWORD MANAGEMENT
</span>

</header>


<div class="container">

<div class="box">


<?php

if($message!=""){

echo "<div class='success'>";
echo htmlspecialchars($message);
echo "</div>";

}

if($error!=""){

echo "<div class='error'>";
echo htmlspecialchars($error);
echo "</div>";

}

?>


<h2>
Find Member
</h2>


<form method="POST">

<label>
Member ITS Number
</label>

<input
type="text"
name="its_id"
placeholder="Enter Member Number"
required>

<button
type="submit"
name="search_member">

Find Member

</button>

</form>


<?php if($member){ ?>


<div class="member-info">

<p>
<strong>Name:</strong>

<?php
echo htmlspecialchars(
    $member['full_name']
);
?>

</p>

<p>
<strong>ITS Number:</strong>

<?php
echo htmlspecialchars(
    $member['its_id']
);
?>

</p>

</div>


<h2 style="margin-top:30px;">

Change Password

</h2>


<form method="POST">

<input
type="hidden"
name="its_id"
value="<?php echo htmlspecialchars($member['its_id']); ?>"
>


<label>
New Password
</label>

<input
type="password"
name="password"
placeholder="Enter new password"
required
>


<button
type="submit"
name="change_password">

Change Password

</button>

</form>


<?php } ?>


<a
href="admin.php"
class="back">

← Back to Admin Panel

</a>


</div>

</div>

</body>

</html>
<?php
session_start();
include("config.php");

$message = "";

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {
        $_SESSION['admin']=$username;
        header("Location: admin.php");
        exit();
    }
    else
    {
        $message="Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Login</title>

<style>

body{
margin:0;
font-family:Arial;
background:#e8f5e9;
}

.login{

width:350px;
margin:120px auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0px 0px 15px rgba(0,0,0,.2);

}

h2{

text-align:center;
color:#0b5d1e;

}

input{

width:100%;
padding:12px;
margin:12px 0;
font-size:16px;
box-sizing:border-box;

}

button{

width:100%;
padding:12px;
background:#0b5d1e;
color:white;
border:none;
font-size:18px;
cursor:pointer;

}

button:hover{

background:#146c2d;

}

.error{

color:red;
text-align:center;

}

</style>

</head>

<body>

<div class="login">

<h2>ADMIN LOGIN</h2>

<div class="error">

<?php echo $message; ?>

</div>

<form method="post">

<input
type="text"
name="username"
placeholder="Username"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button
type="submit"
name="login">

LOGIN

</button>

</form>

</div>

</body>

</html>
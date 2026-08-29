<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location:login.php");
    exit();
}

$id=(int)$_GET['id'];

$result=mysqli_query($conn,"SELECT photo FROM postholders WHERE id=$id");

if($row=mysqli_fetch_assoc($result)){
    if($row['photo']!="" && file_exists("uploads/".$row['photo'])){
        unlink("uploads/".$row['photo']);
    }
}

mysqli_query($conn,"DELETE FROM postholders WHERE id=$id");

header("Location:edit_postholders.php");
exit();
?>
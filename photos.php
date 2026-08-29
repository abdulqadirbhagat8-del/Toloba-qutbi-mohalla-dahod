<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config.php");

$result = mysqli_query($conn, "SELECT * FROM photos ORDER BY id DESC");

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Photo Gallery | TOLOBA QUTBI MOHALLA</title>

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
}

.title{
    text-align:center;
    margin:30px;
    font-size:30px;
    font-weight:bold;
    color:#0b5d1e;
}

.gallery{
    width:92%;
    max-width:1400px;
    margin:0 auto 40px;

    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));

    gap:25px;
}

.card{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.photo-link{
    display:block;
    width:100%;
}

.card img{
    width:100%;
    height:240px;
    object-fit:cover;
    display:block;
    cursor:pointer;
}

.card h3{
    padding:15px;
    text-align:center;
    color:#0b5d1e;
}

.back{
    text-align:center;
    margin-bottom:35px;
}

.back a{
    text-decoration:none;
    background:#0b5d1e;
    color:white;
    padding:12px 30px;
    border-radius:8px;
    font-size:17px;
}

.back a:hover{
    background:#146c2d;
}

footer{
    background:#0b5d1e;
    color:white;
    text-align:center;
    padding:18px;
}

.no-photos{
    text-align:center;
    background:white;
    padding:40px;
    border-radius:15px;
    width:90%;
    max-width:600px;
    margin:30px auto;
}

</style>

</head>

<body>

<header>

TOLOBA QUTBI MOHALLA

</header>

<div class="title">

PHOTO GALLERY

</div>

<div class="gallery">

<?php

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

        $image = $row['image'];
        $title = $row['title'];

        $imagePath = "uploads/" . $image;

?>

<div class="card">

    <a
        href="<?php echo htmlspecialchars($imagePath); ?>"
        target="_blank"
        class="photo-link"
    >

        <img
            src="<?php echo htmlspecialchars($imagePath); ?>"
            alt="<?php echo htmlspecialchars($title); ?>"
        >

    </a>

    <h3>

        <?php echo htmlspecialchars($title); ?>

    </h3>

</div>

<?php

    }

}else{

?>

<div class="no-photos">

    <h2>No Photos Available</h2>

    <p>
        There are currently no photos in the gallery.
    </p>

</div>

<?php

}

?>

</div>

<div class="back">

<a href="index.php">

← Back to Home

</a>

</div>

<footer>

All Rights Reserved © <?php echo date("Y"); ?>
TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>

</html>
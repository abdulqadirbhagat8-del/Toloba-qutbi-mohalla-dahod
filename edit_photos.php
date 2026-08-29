<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message = "";
$error = "";

/* ==========================
   UPLOAD PHOTO
========================== */

if(isset($_POST['upload'])){

    $title = mysqli_real_escape_string($conn, $_POST['title']);

    if(!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK){

        $error = "Please select a valid image.";

    }else{

        $file = $_FILES['image'];

        /* Maximum file size = 10 MB */

        if($file['size'] > 10 * 1024 * 1024){

            $error = "Image is too large. Maximum size is 10 MB.";

        }else{

            /* Check actual image type */

            $imageInfo = getimagesize($file['tmp_name']);

            if($imageInfo === false){

                $error = "The uploaded file is not a valid image.";

            }else{

                /* Allowed MIME types */

                $allowedTypes = array(
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp',
                    'image/bmp'
                );

                if(!in_array($imageInfo['mime'], $allowedTypes)){

                    $error = "This image format is not supported.";

                }else{

                    /* Get extension */

                    $extension = strtolower(
                        pathinfo($file['name'], PATHINFO_EXTENSION)
                    );

                    /* Allowed extensions */

                    $allowedExtensions = array(
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        'webp',
                        'bmp'
                    );

                    if(!in_array($extension, $allowedExtensions)){

                        $error = "This file extension is not supported.";

                    }else{

                        /* Create unique filename */

                        $newname = uniqid('photo_', true) . '.' . $extension;

                        $uploadPath = "uploads/" . $newname;

                        /* Make sure uploads folder exists */

                        if(!is_dir("uploads")){

                            mkdir("uploads", 0755, true);

                        }

                        /* Move uploaded image */

                        if(move_uploaded_file(
                            $file['tmp_name'],
                            $uploadPath
                        )){

                            /* Save filename in database */

                            $query = mysqli_query($conn,"
                                INSERT INTO photos(title,image)
                                VALUES('$title','$newname')
                            ");

                            if($query){

                                $message = "Photo Uploaded Successfully!";

                            }else{

                                /* Delete uploaded file if database failed */

                                if(file_exists($uploadPath)){
                                    unlink($uploadPath);
                                }

                                $error = "Database error. Photo was not saved.";

                            }

                        }else{

                            $error = "Unable to upload the photo. Please check the uploads folder.";

                        }

                    }

                }

            }

        }

    }

}


/* ==========================
   DELETE PHOTO
========================== */

if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    $result = mysqli_query(
        $conn,
        "SELECT image FROM photos WHERE id=$id"
    );

    if($result && mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        $filePath = "uploads/" . $row['image'];

        /* Delete physical image */

        if(file_exists($filePath)){
            unlink($filePath);
        }

        /* Delete database record */

        mysqli_query(
            $conn,
            "DELETE FROM photos WHERE id=$id"
        );
    }

    header("Location: edit_photos.php");
    exit();
}


/* ==========================
   FETCH PHOTOS
========================== */

$result = mysqli_query(
    $conn,
    "SELECT * FROM photos ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Photos</title>

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
letter-spacing:1px;
}

.container{
width:90%;
max-width:1000px;
margin:35px auto;
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
text-align:center;
font-weight:bold;
}

.error{
background:#f8d7da;
color:#721c24;
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

input[type=text],
input[type=file]{
width:100%;
padding:12px;
margin-top:8px;
border:1px solid #ccc;
border-radius:8px;
font-size:16px;
}

input[type=file]{
background:#fafafa;
cursor:pointer;
}

button{
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

.upload-info{
margin-top:12px;
font-size:14px;
color:#666;
text-align:center;
}

.gallery{
display:grid;
grid-template-columns:repeat(
auto-fill,
minmax(220px,1fr)
);
gap:20px;
margin-top:40px;
}

.photo-card{
background:#f8f8f8;
border-radius:12px;
overflow:hidden;
box-shadow:0 4px 10px rgba(0,0,0,.15);
text-align:center;
padding-bottom:15px;
}

.photo-card img{
width:100%;
height:200px;
object-fit:cover;
display:block;
}

.photo-card h3{
padding:15px 10px 10px;
font-size:18px;
color:#0b5d1e;
}

.delete-btn{
display:inline-block;
background:#c62828;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:6px;
font-size:15px;
}

.delete-btn:hover{
background:#a00000;
}

.view-btn{
display:inline-block;
background:#0b5d1e;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:6px;
font-size:15px;
margin-right:5px;
}

.view-btn:hover{
background:#146c2d;
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

.back:hover{
background:#222;
}

footer{
margin-top:40px;
background:#0b5d1e;
color:white;
text-align:center;
padding:15px;
}

@media(max-width:600px){

.container{
width:95%;
padding:20px;
}

.gallery{
grid-template-columns:1fr;
}

}

</style>

</head>

<body>

<header>

MANAGE PHOTO GALLERY

</header>

<div class="container">

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

<form
method="POST"
enctype="multipart/form-data">

<label>
Photo Title
</label>

<input
type="text"
name="title"
placeholder="Enter Photo Title"
required>

<label>
Select Photo
</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.gif,.webp,.bmp,image/jpeg,image/png,image/gif,image/webp,image/bmp"
required>

<div class="upload-info">

Allowed formats:
JPG, JPEG, PNG, GIF, WEBP and BMP
<br>
Maximum size: 10 MB

</div>

<button
type="submit"
name="upload">

UPLOAD PHOTO

</button>

</form>


<h2 style="
margin-top:40px;
color:#0b5d1e;
text-align:center;
">

Uploaded Photos

</h2>


<div class="gallery">

<?php

while($photo=mysqli_fetch_assoc($result))
{

?>

<div class="photo-card">

<img
src="uploads/<?php echo htmlspecialchars($photo['image']); ?>"
alt="<?php echo htmlspecialchars($photo['title']); ?>"
loading="lazy"
>

<h3>

<?php
echo htmlspecialchars($photo['title']);
?>

</h3>


<a
class="view-btn"
href="uploads/<?php echo htmlspecialchars($photo['image']); ?>"
target="_blank">

View

</a>


<a
class="delete-btn"
href="edit_photos.php?delete=<?php echo intval($photo['id']); ?>"
onclick="return confirm('Are you sure you want to delete this photo?');">

Delete

</a>

</div>

<?php

}

?>

</div>


<br>
<br>


<a
href="admin.php"
class="back">

← Back to Admin Dashboard

</a>

</div>


<footer>

All Rights Reserved © TOLOBA QUTBI MOHALLA DAHOD

</footer>

</body>

</html>
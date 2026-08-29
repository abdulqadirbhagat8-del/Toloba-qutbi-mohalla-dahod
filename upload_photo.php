```php
<?php

session_start();

require_once "config.php";


/*
=========================================================
TOLOBA QUTBI MOHALLA
MEMBER PROFILE PHOTO UPLOAD
=========================================================
*/


/*
---------------------------------------------------------
CHECK LOGIN
---------------------------------------------------------
*/

if (!isset($_SESSION["member_id"])) {

    header("Location: member_login.php");

    exit();

}


$member_id = (int)$_SESSION["member_id"];


/*
---------------------------------------------------------
ONLY ACCEPT POST
---------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: dashboard.php");

    exit();

}


/*
---------------------------------------------------------
CHECK FILE
---------------------------------------------------------
*/

if (
    !isset($_FILES["profile_photo"]) ||
    $_FILES["profile_photo"]["error"] !== UPLOAD_ERR_OK
) {

    header(
        "Location: dashboard.php?photo_error=file"
    );

    exit();

}


$file = $_FILES["profile_photo"];


/*
---------------------------------------------------------
MAXIMUM SIZE
5 MB
---------------------------------------------------------
*/

$max_size = 5 * 1024 * 1024;

if ($file["size"] > $max_size) {

    header(
        "Location: dashboard.php?photo_error=size"
    );

    exit();

}


/*
---------------------------------------------------------
CHECK MIME TYPE
---------------------------------------------------------
*/

$finfo = new finfo(FILEINFO_MIME_TYPE);

$mime = $finfo->file($file["tmp_name"]);


$allowed = [

    "image/jpeg" => "jpg",

    "image/png"  => "png",

    "image/webp" => "webp"

];


if (!isset($allowed[$mime])) {

    header(
        "Location: dashboard.php?photo_error=type"
    );

    exit();

}


$extension = $allowed[$mime];


/*
---------------------------------------------------------
CREATE UPLOAD DIRECTORY
---------------------------------------------------------
*/

$upload_dir =
    __DIR__ .
    "/member_uploads";


if (!is_dir($upload_dir)) {

    if (!mkdir(
        $upload_dir,
        0755,
        true
    )) {

        header(
            "Location: dashboard.php?photo_error=folder"
        );

        exit();

    }

}


/*
---------------------------------------------------------
CREATE RANDOM FILENAME
---------------------------------------------------------
*/

$random_name =
    bin2hex(
        random_bytes(16)
    );


$file_name =
    $member_id .
    "_" .
    $random_name .
    "." .
    $extension;


$destination =
    $upload_dir .
    "/" .
    $file_name;


/*
---------------------------------------------------------
MOVE FILE
---------------------------------------------------------
*/

if (!move_uploaded_file(
    $file["tmp_name"],
    $destination
)) {

    header(
        "Location: dashboard.php?photo_error=upload"
    );

    exit();

}


/*
---------------------------------------------------------
DATABASE PATH
---------------------------------------------------------
*/

$db_path =
    "member_uploads/" .
    $file_name;


/*
---------------------------------------------------------
GET OLD PHOTO
---------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT profile_photo
    FROM member_accounts
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $member_id
);

$stmt->execute();

$result =
    $stmt->get_result();

$old_photo = "";

if (
    $result &&
    $result->num_rows === 1
) {

    $old = $result->fetch_assoc();

    $old_photo =
        trim(
            $old["profile_photo"] ?? ""
        );

}

$stmt->close();


/*
---------------------------------------------------------
UPDATE DATABASE
---------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE member_accounts
    SET profile_photo = ?
    WHERE id = ?
");

$stmt->bind_param(
    "si",
    $db_path,
    $member_id
);


if (!$stmt->execute()) {

    /*
    If database update fails,
    delete uploaded file.
    */

    @unlink($destination);

    $stmt->close();

    header(
        "Location: dashboard.php?photo_error=database"
    );

    exit();

}


$stmt->close();


/*
---------------------------------------------------------
DELETE OLD PHOTO
---------------------------------------------------------
*/

if ($old_photo !== "") {

    $old_file =
        __DIR__ .
        "/" .
        $old_photo;

    /*
    Only delete files inside our
    member_uploads directory.
    */

    if (
        strpos(
            $old_photo,
            "member_uploads/"
        ) === 0
        &&
        file_exists($old_file)
    ) {

        @unlink($old_file);

    }

}


/*
---------------------------------------------------------
SUCCESS
---------------------------------------------------------
*/

header(
    "Location: dashboard.php?photo_success=1"
);

exit();

?>
```

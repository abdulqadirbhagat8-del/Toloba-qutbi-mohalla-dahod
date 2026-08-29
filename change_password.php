<?php

session_start();

require_once "config.php";


/*
==================================================
CHECK MEMBER LOGIN
==================================================
*/

if (!isset($_SESSION["member_id"])) {

    header("Location: websitelogin.php");

    exit;
}


$member_id = $_SESSION["member_id"];

$error = "";
$success = "";


/*
==================================================
GET MEMBER
==================================================
*/

$stmt = $conn->prepare("
    SELECT id, full_name, first_login
    FROM members
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $member_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    session_unset();
    session_destroy();

    header("Location: websitelogin.php");

    exit;
}

$member = $result->fetch_assoc();

$stmt->close();


/*
==================================================
IF PASSWORD ALREADY CHANGED
==================================================
*/

if ((int)$member["first_login"] !== 1) {

    header("Location: index.php");

    exit;
}


/*
==================================================
CHANGE PASSWORD
==================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_password =
        $_POST["new_password"] ?? "";

    $confirm_password =
        $_POST["confirm_password"] ?? "";


    /*
    ==============================================
    BASIC VALIDATION
    ==============================================
    */

    if (
        $new_password === "" ||
        $confirm_password === ""
    ) {

        $error =
            "Please enter your new password.";

    } elseif (
        $new_password !== $confirm_password
    ) {

        $error =
            "Passwords do not match.";

    } elseif (
        $new_password === "5253"
    ) {

        $error =
            "Please choose a password different from 5253.";

    } elseif (
        strlen($new_password) < 6
    ) {

        $error =
            "Password must be at least 6 characters long.";

    } else {


        /*
        ==========================================
        SECURE PASSWORD HASH
        ==========================================
        */

        $hashed_password =
            password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );


        /*
        ==========================================
        SAVE NEW PASSWORD
        ==========================================
        */

        $update = $conn->prepare("
            UPDATE members
            SET password = ?, first_login = 0
            WHERE id = ?
        ");

        $update->bind_param(
            "si",
            $hashed_password,
            $member_id
        );


        if ($update->execute()) {

            /*
            ======================================
            PASSWORD SUCCESSFULLY CHANGED
            ======================================
            */

            header("Location: index.php");

            exit;

        } else {

            $error =
                "Unable to change password. Please try again.";

        }


        $update->close();

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
Change Password | TOLOBA QUTBI MOHALLA
</title>


<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet">


<style>

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

    font-family: 'Poppins', sans-serif;

}


body {

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 30px 15px;

    background:
        radial-gradient(
            circle at top,
            #dff7e5 0%,
            #f5f7f8 65%
        );

}


.container {

    width: 100%;

    max-width: 480px;

}


.card {

    background: white;

    padding: 45px;

    border-radius: 30px;

    box-shadow:
        0 25px 60px rgba(0,0,0,.12);

    text-align: center;

}


.logo {

    width: 100px;

    height: 100px;

    border-radius: 50%;

    padding: 7px;

    background: white;

    box-shadow:
        0 10px 30px rgba(0,0,0,.10);

    margin-bottom: 20px;

}


h1 {

    color: #0b5d1e;

    font-size: 29px;

    margin-bottom: 10px;

}


.welcome {

    color: #666;

    font-size: 15px;

    line-height: 1.7;

    margin-bottom: 30px;

}


.input-group {

    text-align: left;

    margin-bottom: 20px;

}


.input-group label {

    display: block;

    margin-bottom: 8px;

    color: #444;

    font-size: 14px;

    font-weight: 600;

}


.input-group input {

    width: 100%;

    padding: 15px 17px;

    border: 1px solid #ddd;

    border-radius: 14px;

    outline: none;

    font-size: 16px;

}


.input-group input:focus {

    border-color: #0b5d1e;

    box-shadow:
        0 0 0 3px rgba(11,93,30,.10);

}


.info {

    background: #f0f7f2;

    color: #555;

    padding: 15px;

    border-radius: 14px;

    font-size: 13px;

    line-height: 1.6;

    margin-bottom: 25px;

}


.button {

    width: 100%;

    border: none;

    padding: 16px;

    border-radius: 40px;

    background: #0b5d1e;

    color: white;

    font-size: 17px;

    font-weight: 700;

    cursor: pointer;

}


.button:hover {

    background: #084617;

}


.error {

    background: #ffe7e7;

    color: #a30000;

    padding: 13px;

    border-radius: 12px;

    margin-bottom: 20px;

    font-size: 14px;

    font-weight: 600;

}


.footer {

    margin-top: 20px;

    text-align: center;

    color: #777;

    font-size: 12px;

}


@media(max-width:500px) {

    .card {

        padding: 30px 22px;

    }

    h1 {

        font-size: 25px;

    }

}

</style>

</head>


<body>


<div class="container">


    <div class="card">


        <img
            src="tolobaimageslogo.jpeg"
            class="logo"
            alt="Toloba Qutbi Mohalla"
        >


        <h1>
            Create Your Password
        </h1>


        <div class="welcome">

            Welcome,

            <strong>
                <?php
                echo htmlspecialchars(
                    $member["full_name"]
                );
                ?>
            </strong>

            <br>

            This is your first login.

        </div>


        <?php if ($error !== "") { ?>

            <div class="error">

                <?php
                echo htmlspecialchars($error);
                ?>

            </div>

        <?php } ?>


        <div class="info">

            Your temporary password <strong>5253</strong>
            is only for your first login.

            <br>

            Please create your own password below.

        </div>


        <form method="POST">


            <div class="input-group">

                <label>
                    New Password
                </label>

                <input
                    type="password"
                    name="new_password"
                    placeholder="Enter your new password"
                    minlength="6"
                    required
                >

            </div>


            <div class="input-group">

                <label>
                    Confirm New Password
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Re-enter your new password"
                    minlength="6"
                    required
                >

            </div>


            <button
                type="submit"
                class="button"
            >
                Save New Password
            </button>


        </form>


    </div>


    <div class="footer">

        © <?php echo date("Y"); ?>

        TOLOBA QUTBI MOHALLA DAHOD

    </div>


</div>


</body>

</html>
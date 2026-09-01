<?php

session_start();

require_once "config.php";


/*
==================================================
IF ALREADY LOGGED IN
==================================================
*/

if (isset($_SESSION["member_id"])) {

    header("Location: index.php");

    exit;
}


$error = "";


/*
==================================================
MEMBER LOGIN
==================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");

    $password = $_POST["password"] ?? "";


    if ($username === "" || $password === "") {

        $error = "Please enter your ITS No. and Password.";

    } else {


        /*
        ==========================================
        FIND MEMBER
        ==========================================
        */

        $stmt = $conn->prepare("
            SELECT *
            FROM members
            WHERE its_id = ?
            LIMIT 1
        ");

        $stmt->bind_param(
            "s",
            $username
        );

        $stmt->execute();

        $result = $stmt->get_result();


        /*
        ==========================================
        MEMBER FOUND
        ==========================================
        */

        if ($result->num_rows === 1) {

            $member = $result->fetch_assoc();


            /*
            ======================================
            CHECK FIRST LOGIN
            ======================================
            */

            $isFirstLogin =
                ((int)$member["first_login"] === 1);


            $passwordCorrect = false;


            /*
            ======================================
            FIRST LOGIN
            ======================================

            Temporary password is 5253.
            ======================================
            */

            if ($isFirstLogin) {

                if ($password === "5253") {

                    $passwordCorrect = true;

                }

            } else {


                /*
                ==================================
                NORMAL LOGIN
                ==================================

                After the member changes their
                password, only the saved password
                is accepted.
                ==================================
                */

                if (
                    !empty($member["password"]) &&
                    password_verify(
                        $password,
                        $member["password"]
                    )
                ) {

                    $passwordCorrect = true;

                }

            }


            /*
            ======================================
            PASSWORD CORRECT
            ======================================
            */

            if ($passwordCorrect) {


                /*
                ==================================
                CREATE SECURE SESSION
                ==================================
                */

                session_regenerate_id(true);


                $_SESSION["member_id"] =
                    $member["id"];


                $_SESSION["member_its_id"] =
                    $member["its_id"];


                $_SESSION["member_name"] =
                    $member["full_name"];


                $_SESSION["member_logged_in"] =
                    true;


                /*
                ==================================
                FIRST LOGIN
                ==================================
                */

                if ($isFirstLogin) {

                    header(
                        "Location: change_password.php"
                    );

                    exit;

                }


                /*
                ==================================
                NORMAL LOGIN
                ==================================
                */

                header("Location: index.php");

                exit;

            } else {

                $error = "Incorrect password.";

            }


        } else {

            $error = "ITS No. not found.";

        }


        $stmt->close();

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
Member Login | TOLOBA QUTBI MOHALLA
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


.login-container {

    width: 100%;

    max-width: 460px;

}


.login-card {

    background: rgba(255,255,255,.95);

    padding: 45px;

    border-radius: 30px;

    box-shadow:
        0 25px 60px rgba(0,0,0,.12);

    text-align: center;

}


.logo {

    width: 105px;

    height: 105px;

    border-radius: 50%;

    background: white;

    padding: 7px;

    box-shadow:
        0 10px 30px rgba(0,0,0,.10);

    margin-bottom: 20px;

}


h1 {

    color: #0b5d1e;

    font-size: 30px;

    margin-bottom: 8px;

}


.subtitle {

    color: #777;

    font-size: 14px;

    margin-bottom: 35px;

}


.input-group {

    text-align: left;

    margin-bottom: 22px;

}


.input-group label {

    display: block;

    margin-bottom: 8px;

    font-size: 14px;

    font-weight: 600;

    color: #444;

}


.input-group input {

    width: 100%;

    padding: 15px 17px;

    border: 1px solid #ddd;

    border-radius: 14px;

    outline: none;

    font-size: 16px;

    transition: .3s;

}


.input-group input:focus {

    border-color: #0b5d1e;

    box-shadow:
        0 0 0 3px rgba(11,93,30,.10);

}


.login-button {

    width: 100%;

    border: none;

    padding: 16px;

    border-radius: 40px;

    background: #0b5d1e;

    color: white;

    font-size: 17px;

    font-weight: 700;

    cursor: pointer;

    transition: .3s;

}


.login-button:hover {

    background: #084617;

    transform: translateY(-2px);

}


.error {

    background: #ffe7e7;

    color: #a30000;

    padding: 13px 15px;

    border-radius: 12px;

    margin-bottom: 22px;

    font-size: 14px;

    font-weight: 600;

}


.initial-password {

    margin-top: 25px;

    padding: 15px;

    background: #f0f7f2;

    border-radius: 14px;

    color: #555;

    font-size: 13px;

}


.initial-password strong {

    color: #0b5d1e;

}


.back-home {

    display: inline-block;

    margin-top: 25px;

    text-decoration: none;

    color: #0b5d1e;

    font-size: 14px;

    font-weight: 600;

}


.footer {

    text-align: center;

    margin-top: 20px;

    color: #777;

    font-size: 12px;

}


@media(max-width:500px) {

    .login-card {

        padding: 30px 22px;

    }

    h1 {

        font-size: 25px;

    }

}

</style>

</head>


<body>


<div class="login-container">


    <div class="login-card">


        <img
            src="tolobaimageslogo.jpeg"
            class="logo"
            alt="Toloba Qutbi Mohalla"
        >


        <h1>
            Member Login
        </h1>


        <p class="subtitle">
            TOLOBA QUTBI MOHALLA
        </p>


        <?php if ($error !== "") { ?>

            <div class="error">

                <?php

                echo htmlspecialchars($error);

                ?>

            </div>

        <?php } ?>


        <form
            method="POST"
            action=""
        >


            <div class="input-group">

                <label>
                    ITS No.
                </label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter your ITS No."
                    autocomplete="username"
                    required
                >

            </div>


            <div class="input-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your Password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-button"
            >
                Login
            </button>


        </form>


        <div class="initial-password">

            First-time login password:
            <strong>5253</strong>

        </div>


        <a
            href="index.php"
            class="back-home"
        >
            ← Back to Website
        </a>


    </div>


    <div class="footer">

        © <?php echo date("Y"); ?>

        TOLOBA QUTBI MOHALLA DAHOD

    </div>


</div>


</body>

</html>

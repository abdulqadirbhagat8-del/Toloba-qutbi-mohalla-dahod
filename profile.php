```php
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


/*
==================================================
GET LOGGED-IN MEMBER
==================================================
*/

$member_id = $_SESSION["member_id"];


$stmt = $conn->prepare("
    SELECT
        id,
        its_id,
        full_name,
        designation,
        contact_no,
        age,
        jamiat,
        jamaat,
        zone,
        status
    FROM members
    WHERE id = ?
    LIMIT 1
");


$stmt->bind_param(
    "i",
    $member_id
);


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

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
My Profile | TOLOBA QUTBI MOHALLA
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

    background:
        linear-gradient(
            135deg,
            #e8f5eb,
            #f5f7f8
        );

    min-height: 100vh;

    color: #222;

}


/*
==================================================
HEADER
==================================================
*/

header {

    width: 94%;

    max-width: 1400px;

    margin: 20px auto;

    padding: 15px 30px;

    background: rgba(255,255,255,.9);

    backdrop-filter: blur(15px);

    border-radius: 22px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    box-shadow:
        0 10px 30px rgba(0,0,0,.08);

}


.logo {

    display: flex;

    align-items: center;

    gap: 15px;

}


.logo img {

    width: 60px;

    height: 60px;

    border-radius: 50%;

    background: white;

    padding: 4px;

}


.logo h2 {

    color: #0b5d1e;

    font-size: 23px;

}


.logo span {

    display: block;

    color: #777;

    font-size: 12px;

}


nav {

    display: flex;

    gap: 15px;

    align-items: center;

}


nav a {

    text-decoration: none;

    font-weight: 600;

    color: #333;

    padding: 10px 16px;

    border-radius: 25px;

}


nav a:hover {

    color: #0b5d1e;

}


.logout {

    background: #8b1e1e;

    color: white !important;

}


/*
==================================================
PROFILE CONTAINER
==================================================
*/

.profile-container {

    width: 92%;

    max-width: 1000px;

    margin: 70px auto 100px;

}


/*
==================================================
PROFILE HEADER
==================================================
*/

.profile-header {

    background: #0b5d1e;

    color: white;

    padding: 50px 30px;

    border-radius: 30px 30px 0 0;

    text-align: center;

}


.profile-icon {

    width: 110px;

    height: 110px;

    border-radius: 50%;

    background: white;

    color: #0b5d1e;

    display: flex;

    justify-content: center;

    align-items: center;

    margin: 0 auto 20px;

    font-size: 50px;

    font-weight: 700;

}


.profile-header h1 {

    font-size: 36px;

    margin-bottom: 8px;

}


.profile-header p {

    opacity: .9;

    font-size: 16px;

}


/*
==================================================
PROFILE DETAILS
==================================================
*/

.profile-card {

    background: white;

    padding: 45px;

    border-radius: 0 0 30px 30px;

    box-shadow:
        0 20px 50px rgba(0,0,0,.10);

}


.profile-grid {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 25px;

}


.profile-item {

    background: #f7f9f8;

    padding: 22px;

    border-radius: 18px;

    border-left: 5px solid #0b5d1e;

}


.profile-item label {

    display: block;

    color: #777;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 7px;

    text-transform: uppercase;

    letter-spacing: .5px;

}


.profile-item strong {

    font-size: 18px;

    color: #222;

    word-break: break-word;

}


/*
==================================================
BUTTONS
==================================================
*/

.profile-actions {

    display: flex;

    justify-content: center;

    gap: 15px;

    margin-top: 40px;

    flex-wrap: wrap;

}


.profile-actions a {

    text-decoration: none;

    padding: 15px 30px;

    border-radius: 40px;

    font-weight: 700;

}


.home-btn {

    background: #0b5d1e;

    color: white;

}


.logout-btn {

    background: #8b1e1e;

    color: white;

}


/*
==================================================
FOOTER
==================================================
*/

footer {

    background: #0b5d1e;

    color: white;

    text-align: center;

    padding: 30px 20px;

}


footer p {

    margin: 5px 0;

    font-size: 14px;

}


@media(max-width: 768px) {

    header {

        flex-direction: column;

        gap: 15px;

        text-align: center;

    }


    nav {

        flex-wrap: wrap;

        justify-content: center;

    }


    .profile-container {

        margin-top: 40px;

    }


    .profile-grid {

        grid-template-columns: 1fr;

    }


    .profile-card {

        padding: 25px;

    }


    .profile-header h1 {

        font-size: 28px;

    }

}

</style>

</head>


<body>


<!-- ==================================================
HEADER
==================================================
-->

<header>


    <div class="logo">

        <img
            src="tolobaimageslogo.jpeg"
            alt="Toloba Logo"
        >


        <div>

            <h2>
                TOLOBA QUTBI MOHALLA
            </h2>

            <span>
                Dahod Community Portal
            </span>

        </div>

    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="profile.php">
            My Profile
        </a>

        <a
            href="logout.php"
            class="logout"
        >
            Logout
        </a>

    </nav>

</header>


<!-- ==================================================
PROFILE
==================================================
-->

<main class="profile-container">


    <div class="profile-header">


        <div class="profile-icon">

            <?php

            echo strtoupper(
                substr(
                    $member["full_name"],
                    0,
                    1
                )
            );

            ?>

        </div>


        <h1>

            <?php

            echo htmlspecialchars(
                $member["full_name"]
            );

            ?>

        </h1>


        <p>

            Member ID:
            <?php

            echo htmlspecialchars(
                $member["its_id"]
            );

            ?>

        </p>


    </div>


    <div class="profile-card">


        <div class="profile-grid">


            <div class="profile-item">

                <label>
                    ITS Number
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["its_id"]
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Full Name
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["full_name"]
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Designation
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["designation"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Contact Number
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["contact_no"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Age
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["age"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Jamiat
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["jamiat"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Jamaat
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["jamaat"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Zone
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["zone"] ?: "-"
                    );

                    ?>

                </strong>

            </div>


            <div class="profile-item">

                <label>
                    Account Status
                </label>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $member["status"] ?: "Active"
                    );

                    ?>

                </strong>

            </div>


        </div>


        <div class="profile-actions">


            <a
                href="index.php"
                class="home-btn"
            >
                ← Back to Home
            </a>


            <a
                href="logout.php"
                class="logout-btn"
            >
                Logout
            </a>


        </div>


    </div>

</main>


<!-- ==================================================
FOOTER
==================================================
-->

<footer>

    <p>
        © <?php echo date("Y"); ?>
        TOLOBA QUTBI MOHALLA DAHOD
    </p>

    <p>
        All Rights Reserved
    </p>

</footer>


</body>

</html>
```

<?php
include("config.php");

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $result = mysqli_query($conn, "
        SELECT *
        FROM members
        WHERE
            full_name LIKE '%$search%'
            OR its_id LIKE '%$search%'
        ORDER BY full_name ASC
    ");

} else {

    $result = mysqli_query($conn, "
        SELECT *
        FROM members
        ORDER BY full_name ASC
    ");
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Members | TOLOBA QUTBI MOHALLA
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
    background: #f5f7f8;
    color: #222;
}


/* HEADER */

header {

    background: #0b5d1e;

    color: white;

    padding: 25px;

    text-align: center;

    box-shadow:
        0 5px 20px rgba(0,0,0,.15);
}

header h1 {

    font-size: 30px;

}

header p {

    margin-top: 5px;

    opacity: .85;

}


/* TITLE */

.title {

    text-align: center;

    margin: 45px 20px 30px;

    font-size: 40px;

    font-weight: 800;

    color: #0b5d1e;
}


/* SEARCH */

.search-box {

    width: 90%;

    max-width: 650px;

    margin: auto;

    display: flex;

    gap: 10px;
}

.search-box input {

    flex: 1;

    padding: 15px 18px;

    border: 1px solid #ddd;

    border-radius: 12px;

    outline: none;

    font-size: 16px;

    background: white;
}

.search-box input:focus {

    border-color: #0b5d1e;

    box-shadow:
        0 0 0 3px rgba(11,93,30,.1);
}

.search-box button {

    padding: 15px 28px;

    background: #0b5d1e;

    color: white;

    border: none;

    border-radius: 12px;

    cursor: pointer;

    font-size: 16px;

    font-weight: 600;

}

.search-box button:hover {

    background: #084617;

}


/* MEMBER COUNT */

.member-count {

    text-align: center;

    margin: 25px 0;

    color: #666;

    font-size: 15px;
}


/* MEMBER GRID */

.container {

    width: 90%;

    max-width: 1400px;

    margin: 35px auto 60px;

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(280px, 1fr));

    gap: 30px;
}


/* MEMBER CARD */

.card {

    background: white;

    padding: 30px 25px;

    border-radius: 24px;

    text-align: center;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

    border-top: 6px solid #0b5d1e;

    transition: .3s;
}

.card:hover {

    transform: translateY(-8px);

    box-shadow:
        0 20px 45px rgba(0,0,0,.12);
}


/* AVATAR */

.avatar {

    width: 105px;

    height: 105px;

    margin: 0 auto 20px;

    border-radius: 50%;

    background: #e8f5e9;

    color: #0b5d1e;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 40px;

    font-weight: 700;

    border: 4px solid #0b5d1e;
}


/* NAME */

.card h2 {

    color: #0b5d1e;

    margin-bottom: 18px;

    font-size: 21px;

}


/* DETAILS */

.detail {

    padding: 11px 0;

    border-bottom: 1px solid #eee;

}

.detail:last-child {

    border-bottom: none;

}

.detail strong {

    display: block;

    color: #777;

    font-size: 13px;

    margin-bottom: 3px;

}

.detail span {

    font-size: 15px;

    font-weight: 500;

}


/* STATUS */

.status {

    display: inline-block;

    margin-top: 18px;

    padding: 6px 15px;

    border-radius: 30px;

    background: #e8f5e9;

    color: #0b5d1e;

    font-size: 13px;

    font-weight: 700;
}


/* NO RESULTS */

.no-results {

    grid-column: 1 / -1;

    background: white;

    padding: 50px;

    text-align: center;

    border-radius: 20px;

    box-shadow:
        0 10px 30px rgba(0,0,0,.06);

}

.no-results h2 {

    color: #0b5d1e;

    margin-bottom: 10px;

}


/* BACK BUTTON */

.back {

    text-align: center;

    margin: 20px 0 50px;
}

.back a {

    display: inline-block;

    background: #0b5d1e;

    color: white;

    padding: 14px 30px;

    text-decoration: none;

    border-radius: 50px;

    font-weight: 600;

    transition: .3s;
}

.back a:hover {

    background: #084617;

    transform: translateY(-3px);
}


/* FOOTER */

footer {

    background: #0b5d1e;

    color: white;

    text-align: center;

    padding: 25px;

    font-size: 14px;
}


/* MOBILE */

@media(max-width:650px) {

    .title {

        font-size: 30px;

    }

    .search-box {

        flex-direction: column;

    }

    .search-box button {

        width: 100%;

    }

    .container {

        width: 92%;

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<header>

    <h1>
        TOLOBA QUTBI MOHALLA
    </h1>

    <p>
        Dahod Community Portal
    </p>

</header>


<div class="title">

    MEMBERS DIRECTORY

</div>


<form method="GET">

    <div class="search-box">

        <input
            type="text"
            name="search"
            placeholder="Search by Name or ITS Number"
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">
            Search
        </button>

    </div>

</form>


<?php

if ($result) {

    $total = mysqli_num_rows($result);

} else {

    $total = 0;

}

?>

<div class="member-count">

    <?php echo $total; ?> member(s) found

</div>


<div class="container">


<?php

if ($result && mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        /*
        Get initials for avatar
        */

        $name = $row['full_name'];

        $words = explode(" ", trim($name));

        $initials = "";

        foreach ($words as $word) {

            if ($word !== "") {

                $initials .= strtoupper(
                    substr($word, 0, 1)
                );

            }

            if (strlen($initials) >= 2) {
                break;
            }

        }

?>

<div class="card">


    <div class="avatar">

        <?php echo htmlspecialchars($initials); ?>

    </div>


    <h2>

        <?php
        echo htmlspecialchars(
            $row['full_name']
        );
        ?>

    </h2>


    <div class="detail">

        <strong>
            Designation
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['designation'] ?? 'Member'
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            ITS Number
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['its_id']
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            Contact Number
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['contact_no'] ?? '-'
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            Age
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['age'] ?? '-'
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            Jamiat
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['jamiat'] ?? '-'
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            Jamaat
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['jamaat'] ?? '-'
            );

            ?>

        </span>

    </div>


    <div class="detail">

        <strong>
            Zone
        </strong>

        <span>

            <?php

            echo htmlspecialchars(
                $row['zone'] ?? '-'
            );

            ?>

        </span>

    </div>


    <div class="status">

        <?php

        echo htmlspecialchars(
            $row['status'] ?? 'Active'
        );

        ?>

    </div>


</div>


<?php

    }

} else {

?>

<div class="no-results">

    <h2>
        No Members Found
    </h2>

    <p>
        Try searching with another name or ITS number.
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

    All Rights Reserved ©
    <?php echo date("Y"); ?>
    TOLOBA QUTBI MOHALLA DAHOD

</footer>


</body>

</html>
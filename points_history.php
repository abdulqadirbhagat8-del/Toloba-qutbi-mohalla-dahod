```php
<?php

session_start();

require_once "config.php";

/* Check login */
if (!isset($_SESSION["member_id"])) {
    header("Location: member_login.php");
    exit();
}

$member_id = (int) $_SESSION["member_id"];

/* Get member */
$stmt = $conn->prepare("
    SELECT full_name, points
    FROM member_accounts
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $member_id);
$stmt->execute();

$result = $stmt->get_result();

if (!$result || $result->num_rows !== 1) {
    session_destroy();
    header("Location: member_login.php");
    exit();
}

$member = $result->fetch_assoc();

$stmt->close();

/* Get point history */
$history_stmt = $conn->prepare("
    SELECT
        points,
        reason,
        created_at
    FROM member_points
    WHERE member_id = ?
    ORDER BY created_at DESC
");

$history_stmt->bind_param("i", $member_id);
$history_stmt->execute();

$history = $history_stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
Points History | TOLOBA QUTBI MOHALLA
</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #f1f8f3;
    min-height: 100vh;
}

.header {
    background: #0b5d1e;
    color: white;
    padding: 18px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    font-size: 19px;
}

.back {
    color: white;
    text-decoration: none;
    border: 1px solid white;
    padding: 8px 14px;
    border-radius: 7px;
    font-size: 13px;
}

.container {
    width: 92%;
    max-width: 850px;
    margin: 30px auto;
}

.summary {
    background: white;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
    margin-bottom: 20px;
}

.summary p {
    color: #777;
    font-size: 13px;
}

.points {
    color: #0b5d1e;
    font-size: 42px;
    font-weight: bold;
    margin-top: 5px;
}

.card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,.08);
}

.card-title {
    padding: 20px;
    font-size: 19px;
    font-weight: bold;
    color: #0b5d1e;
    border-bottom: 1px solid #eee;
}

.history {
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.reason {
    font-weight: bold;
    color: #333;
}

.date {
    color: #888;
    font-size: 12px;
    margin-top: 5px;
}

.points-earned {
    color: #0b5d1e;
    font-size: 18px;
    font-weight: bold;
}

.empty {
    text-align: center;
    padding: 40px 20px;
    color: #777;
}

</style>

</head>

<body>

<header class="header">

<h1>
TOLOBA QUTBI MOHALLA
</h1>

<a
    href="dashboard.php"
    class="back"
>
← Profile
</a>

</header>

<main class="container">

<div class="summary">

<p>
TOTAL POINTS
</p>

<div class="points">

<?php echo (int)$member["points"]; ?>

</div>

</div>

<div class="card">

<div class="card-title">
Points History
</div>

<?php

if ($history && $history->num_rows > 0):

    while ($row = $history->fetch_assoc()):

?>

<div class="history">

<div>

<div class="reason">

<?php
echo htmlspecialchars(
    $row["reason"] ?? "Points"
);
?>

</div>

<div class="date">

<?php
echo htmlspecialchars(
    $row["created_at"]
);
?>

</div>

</div>

<div class="points-earned">

+<?php echo (int)$row["points"]; ?>

</div>

</div>

<?php

    endwhile;

else:

?>

<div class="empty">

No points activity yet.

</div>

<?php endif; ?>

</div>

</main>

</body>

</html>
```

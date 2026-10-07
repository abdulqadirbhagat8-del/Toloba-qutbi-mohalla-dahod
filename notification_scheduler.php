<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/notification_helpers.php";

if (!isset($conn)) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_lavazim_reminders'])) {
    $sent = send_weekly_lavazim_reminders($conn);
    echo "Sent " . $sent . " Lavazim reminder notifications.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_miqat_notifications'])) {
    $sent = send_miqat_notifications($conn, 'A new Miqat announcement has been published.');
    echo "Sent " . $sent . " Miqat notifications.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_all'])) {
    $lavazim_count = send_weekly_lavazim_reminders($conn);
    $miqat_count = send_miqat_notifications($conn, 'A new Miqat update is available for the community.');
    echo "Lavazim reminders sent: " . $lavazim_count . ". Miqat notifications sent: " . $miqat_count . ".";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Scheduler</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7f8; padding: 40px; }
        .box { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        form { display: grid; gap: 12px; margin-top: 20px; }
        button { background: #0b5d1e; color: white; border: none; padding: 12px 18px; border-radius: 8px; cursor: pointer; font-weight: 700; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Notification Scheduler</h2>
        <form method="POST">
            <button type="submit" name="run_lavazim_reminders">Send Weekly Lavazim Reminders</button>
            <button type="submit" name="run_miqat_notifications">Send Miqat Notifications</button>
            <button type="submit" name="run_all">Run All Notification Jobs</button>
        </form>
    </div>
</body>
</html>

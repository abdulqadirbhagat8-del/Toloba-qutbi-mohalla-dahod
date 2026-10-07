<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/notification_helpers.php";

if (!isset($conn)) {
    exit;
}

function send_weekly_lavazim_reminders($conn)
{
    if (!$conn || !table_exists($conn, 'members') || !table_exists($conn, 'lavazim_payments')) {
        return 0;
    }

    $sql = "
        SELECT id, full_name
        FROM members
        WHERE status != 'Inactive' OR status IS NULL
    ";
    $result = $conn->query($sql);

    if (!$result) {
        return 0;
    }

    $sent = 0;

    while ($member = $result->fetch_assoc()) {
        $member_id = (int) $member['id'];
        $status = get_lavazim_status($conn, $member_id);

        $title = $status['is_paid'] ? 'Lavazim Payment Status' : 'Lavazim Payment Pending';
        $message = $status['is_paid']
            ? 'Your Lavazim payment has been marked as paid.'
            : 'Your Lavazim payment is still pending. Please complete it as soon as possible.';

        if (create_notification($conn, $member_id, 'lavazim_reminder', $title, $message, $member_id)) {
            $sent++;
        }
    }

    return $sent;
}

function send_miqat_notifications($conn, $message = null)
{
    if (!$conn || !table_exists($conn, 'members')) {
        return 0;
    }

    $default_message = $message ?: 'A new Miqat update is available for the community.';
    $result = $conn->query("SELECT id FROM members");
    if (!$result) {
        return 0;
    }

    $sent = 0;
    while ($member = $result->fetch_assoc()) {
        if (create_notification($conn, (int) $member['id'], 'miqat', 'Miqat Update', $default_message, (int) $member['id'])) {
            $sent++;
        }
    }

    return $sent;
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

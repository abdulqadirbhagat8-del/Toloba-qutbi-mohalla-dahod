<?php
session_start();

require_once "config.php";

if (!isset($_SESSION["member_id"])) {
    header("Location: websitelogin.php");
    exit;
}

$member_id = (int) $_SESSION["member_id"];

$stmt = $conn->prepare("
    SELECT id, title, message, notification_type, is_read, created_at
    FROM notifications
    WHERE member_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt = $conn->prepare("
    UPDATE notifications
    SET is_read = 1, read_at = NOW()
    WHERE member_id = ? AND is_read = 0
");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f8f4; margin: 0; padding: 30px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        h1 { color: #0b5d1e; margin-bottom: 20px; }
        .notification-list { display: grid; gap: 18px; }
        .item {
            border: 1px solid #e5ece7;
            border-left: 6px solid #0b5d1e;
            background: #f9fbfa;
            border-radius: 12px;
            padding: 18px;
        }
        .item.unread { border-left-color: #d32f2f; }
        .title { font-weight: 700; font-size: 18px; color: #0b5d1e; }
        .type { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; }
        .message { color: #444; margin-top: 8px; line-height: 1.6; }
        .date { color: #777; font-size: 13px; margin-top: 8px; }
        .empty { padding: 25px; text-align: center; background: #f0f6f1; border-radius: 10px; color: #555; }
        .back-link { display: inline-block; margin-top: 30px; color: #0b5d1e; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notifications</h1>

        <?php if (empty($notifications)): ?>
            <div class="empty">No notifications yet.</div>
        <?php else: ?>
            <div class="notification-list">
                <?php foreach ($notifications as $item): ?>
                    <div class="item <?php echo $item["is_read"] ? "" : "unread"; ?>">
                        <div class="type">
                            <?php echo htmlspecialchars(str_replace("_", " ", $item["notification_type"])); ?>
                        </div>
                        <div class="title"><?php echo htmlspecialchars($item["title"]); ?></div>
                        <div class="message"><?php echo htmlspecialchars($item["message"]); ?></div>
                        <div class="date">
                            <?php echo htmlspecialchars(date("d M Y, h:i A", strtotime($item["created_at"]))); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>

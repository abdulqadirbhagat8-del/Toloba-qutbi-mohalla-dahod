<?php
session_start();

require_once "config.php";
require_once "notification_helpers.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $member_id = (int) ($_POST["member_id"] ?? 0);
    $payment_year = (int) ($_POST["payment_year"] ?? date("Y"));
    $is_paid = isset($_POST["is_paid"]) ? 1 : 0;
    $amount = (float) ($_POST["amount"] ?? 0);
    $payment_date = trim($_POST["payment_date"] ?? "");

    if ($member_id > 0) {
        $check = $conn->prepare("
            SELECT id
            FROM lavazim_payments
            WHERE member_id = ? AND payment_year = ?
        ");
        $check->bind_param("ii", $member_id, $payment_year);
        $check->execute();
        $exists = $check->get_result();

        if ($exists->num_rows > 0) {
            $stmt = $conn->prepare("
                UPDATE lavazim_payments
                SET is_paid = ?, amount = ?, payment_date = ?, updated_at = NOW()
                WHERE member_id = ? AND payment_year = ?
            ");
            $stmt->bind_param("idsii", $is_paid, $amount, $payment_date, $member_id, $payment_year);
        } else {
            $stmt = $conn->prepare("
                INSERT INTO lavazim_payments (member_id, payment_year, is_paid, payment_date, amount, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->bind_param("iiids", $member_id, $payment_year, $is_paid, $payment_date, $amount);
        }

        if ($stmt->execute()) {
            $message = "Lavazim status updated successfully.";

            $title = $is_paid ? "Lavazim Paid" : "Lavazim Pending";
            $body = $is_paid
                ? "Your Lavazim payment has been marked as paid."
                : "Your Lavazim payment is still pending. Please complete it as soon as possible.";

            create_notification($conn, $member_id, "lavazim_reminder", $title, $body, $member_id);
        } else {
            $message = "Failed to update Lavazim status.";
        }

        $stmt->close();
    } else {
        $message = "Please select a valid member.";
    }
}

$members = $conn->query("
    SELECT id, full_name, its_id
    FROM members
    ORDER BY full_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lavazim Payments</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #eef8f1; padding: 30px; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; border-radius: 14px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        h1 { color: #0b5d1e; margin-bottom: 20px; }
        label { display: block; margin-top: 18px; font-weight: 700; color: #0b5d1e; }
        input, select, button { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-size: 16px; }
        button { background: #0b5d1e; color: white; border: none; cursor: pointer; font-weight: 700; margin-top: 20px; }
        .success { background: #dff7e9; color: #155724; border: 1px solid #bfe9cb; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; }
        .back-link { display: inline-block; margin-top: 20px; color: #0b5d1e; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Lavazim Payments</h1>

        <?php if ($message !== ""): ?>
            <div class="success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Member</label>
            <select name="member_id" required>
                <option value="">Select member</option>
                <?php while ($row = $members->fetch_assoc()): ?>
                    <option value="<?php echo (int)$row["id"]; ?>">
                        <?php echo htmlspecialchars($row["full_name"] . " (" . $row["its_id"] . ")"); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Payment Year</label>
            <input type="number" name="payment_year" value="<?php echo date("Y"); ?>" required>

            <label>Lavazim Status</label>
            <select name="is_paid">
                <option value="1">Paid</option>
                <option value="0">Pending</option>
            </select>

            <label>Amount</label>
            <input type="number" step="0.01" name="amount" value="0.00">

            <label>Payment Date</label>
            <input type="date" name="payment_date">

            <button type="submit">Save Lavazim Status</button>
        </form>

        <a href="admin.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>

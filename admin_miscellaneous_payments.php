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
    $type_id = (int) ($_POST["type_id"] ?? 0);
    $amount = (float) ($_POST["amount"] ?? 0);
    $payment_date = trim($_POST["payment_date"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($member_id > 0 && $type_id > 0 && $amount > 0) {
        $stmt = $conn->prepare("
            INSERT INTO miscellaneous_contributions (member_id, contribution_type_id, amount, payment_date, description, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param("iidss", $member_id, $type_id, $amount, $payment_date, $description);

        if ($stmt->execute()) {
            $message = "Miscellaneous payment recorded successfully.";
        } else {
            $message = "Failed to record payment.";
        }

        $stmt->close();
    } else {
        $message = "Please fill all required details.";
    }
}

$members = $conn->query("
    SELECT id, full_name, its_id
    FROM members
    ORDER BY full_name ASC
");

$types = $conn->query("
    SELECT id, name
    FROM miscellaneous_contribution_types
    ORDER BY name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Miscellaneous Payments</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #eef8f1; padding: 30px; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; border-radius: 14px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,.08); }
        h1 { color: #0b5d1e; margin-bottom: 20px; }
        label { display: block; margin-top: 18px; font-weight: 700; color: #0b5d1e; }
        input, select, button, textarea { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-size: 16px; }
        button { background: #0b5d1e; color: white; border: none; cursor: pointer; font-weight: 700; margin-top: 20px; }
        .success { background: #dff7e9; color: #155724; border: 1px solid #bfe9cb; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; }
        .back-link { display: inline-block; margin-top: 20px; color: #0b5d1e; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Miscellaneous Payments</h1>

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

            <label>Contribution Type</label>
            <select name="type_id" required>
                <option value="">Select type</option>
                <?php while ($row = $types->fetch_assoc()): ?>
                    <option value="<?php echo (int)$row["id"]; ?>">
                        <?php echo htmlspecialchars($row["name"]); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Amount</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Payment Date</label>
            <input type="date" name="payment_date">

            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Optional description"></textarea>

            <button type="submit">Save Miscellaneous Payment</button>
        </form>

        <a href="admin.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</body>
</html>

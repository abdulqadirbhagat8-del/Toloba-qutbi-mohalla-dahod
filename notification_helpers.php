<?php

function table_exists($conn, $table_name)
{
    $result = $conn->query("SHOW TABLES LIKE '" . $table_name . "'");
    return $result && $result->num_rows > 0;
}

function create_notification($conn, $member_id, $notification_type, $title, $message, $related_id = null)
{
    if (!$conn || !is_numeric($member_id)) {
        return false;
    }

    if (!table_exists($conn, 'notifications')) {
        return false;
    }

    $stmt = $conn->prepare(
        "INSERT INTO notifications (member_id, notification_type, title, message, related_id, is_read, read_at, created_at)
         VALUES (?, ?, ?, ?, ?, 0, NULL, NOW())"
    );

    if (!$stmt) {
        return false;
    }

    $related_value = $related_id;
    $stmt->bind_param("isssi", $member_id, $notification_type, $title, $message, $related_value);

    $success = $stmt->execute();
    $stmt->close();

    if (!$success) {
        return false;
    }

    if (table_exists($conn, 'notification_log')) {
        $today = date('Y-m-d');
        $check = $conn->prepare(
            "SELECT id FROM notification_log WHERE member_id = ? AND notification_type = ? AND sent_date = ? LIMIT 1"
        );
        $check->bind_param("iss", $member_id, $notification_type, $today);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows === 0) {
            $log_stmt = $conn->prepare(
                "INSERT INTO notification_log (member_id, notification_type, sent_date, created_at)
                 VALUES (?, ?, ?, NOW())"
            );
            $log_stmt->bind_param("iss", $member_id, $notification_type, $today);
            $log_stmt->execute();
            $log_stmt->close();
        }

        $check->close();
    }

    return true;
}

function get_lavazim_status($conn, $member_id)
{
    $status = [
        'is_paid' => false,
        'amount' => 0,
        'payment_year' => date('Y'),
        'payment_date' => null,
    ];

    if (!$conn || !is_numeric($member_id)) {
        return $status;
    }

    if (!table_exists($conn, 'lavazim_payments')) {
        return $status;
    }

    $stmt = $conn->prepare(
        "SELECT is_paid, amount, payment_year, payment_date
         FROM lavazim_payments
         WHERE member_id = ?
         ORDER BY payment_year DESC, payment_date DESC
         LIMIT 1"
    );

    if (!$stmt) {
        return $status;
    }

    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status['is_paid'] = (int) $row['is_paid'] === 1;
        $status['amount'] = (float) ($row['amount'] ?? 0);
        $status['payment_year'] = (int) ($row['payment_year'] ?? date('Y'));
        $status['payment_date'] = $row['payment_date'] ?? null;
    }

    $stmt->close();

    return $status;
}

function get_miscellaneous_payments($conn, $member_id)
{
    $items = [];

    if (!$conn || !is_numeric($member_id)) {
        return $items;
    }

    if (!table_exists($conn, 'miscellaneous_contribution_types') || !table_exists($conn, 'miscellaneous_contributions')) {
        return $items;
    }

    $stmt = $conn->prepare(
        "SELECT mct.name, COALESCE(SUM(mc.amount), 0) AS total_amount
         FROM miscellaneous_contribution_types mct
         LEFT JOIN miscellaneous_contributions mc
            ON mc.contribution_type_id = mct.id
           AND mc.member_id = ?
         WHERE mct.is_active = 1
         GROUP BY mct.id, mct.name
         ORDER BY mct.name ASC"
    );

    if (!$stmt) {
        return $items;
    }

    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $items[] = [
            'name' => $row['name'],
            'total_amount' => (float) ($row['total_amount'] ?? 0),
        ];
    }

    $stmt->close();

    return $items;
}

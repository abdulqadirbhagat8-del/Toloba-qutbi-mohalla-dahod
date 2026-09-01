<?php

/*
==================================================
DATABASE MIGRATION
==================================================
Creates necessary tables for new features:
- login_tokens (for persistent login)
- lavazim_payments (payment tracking)
- miscellaneous_contributions (flexible payment system)
- notifications (notification system)
- otp_verifications (OTP for first login)
==================================================
*/

require_once "config.php";

$migrations = [

    /*
    ==================================================
    LOGIN TOKENS TABLE
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS login_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        token_hash VARCHAR(255) NOT NULL UNIQUE,
        device_id VARCHAR(255) NOT NULL,
        is_revoked TINYINT(1) DEFAULT 0,
        expires_at DATETIME NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        INDEX idx_member_device (member_id, device_id),
        INDEX idx_expires (expires_at)
    )",

    /*
    ==================================================
    LAVAZIM PAYMENTS TABLE
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS lavazim_payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        payment_year INT NOT NULL,
        is_paid TINYINT(1) DEFAULT 0,
        payment_date DATETIME,
        amount DECIMAL(10, 2),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        UNIQUE KEY unique_member_year (member_id, payment_year),
        INDEX idx_payment_status (is_paid)
    )",

    /*
    ==================================================
    MISCELLANEOUS CONTRIBUTIONS TABLE
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS miscellaneous_contribution_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_name (name)
    )",

    "CREATE TABLE IF NOT EXISTS miscellaneous_contributions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        contribution_type_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_date DATETIME,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        FOREIGN KEY (contribution_type_id) REFERENCES miscellaneous_contribution_types(id) ON DELETE RESTRICT,
        INDEX idx_member (member_id),
        INDEX idx_type (contribution_type_id)
    )",

    /*
    ==================================================
    NOTIFICATIONS TABLE
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        notification_type ENUM('lavazim_reminder', 'miqat', 'photo_upload', 'other') NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        related_id INT,
        is_read TINYINT(1) DEFAULT 0,
        read_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        INDEX idx_member_read (member_id, is_read),
        INDEX idx_type (notification_type),
        INDEX idx_created (created_at)
    )",

    /*
    ==================================================
    OTP VERIFICATIONS TABLE
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS otp_verifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        otp_hash VARCHAR(255) NOT NULL,
        attempts INT DEFAULT 0,
        max_attempts INT DEFAULT 5,
        expires_at DATETIME NOT NULL,
        is_verified TINYINT(1) DEFAULT 0,
        verified_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        INDEX idx_member_email (member_id, email),
        INDEX idx_expires (expires_at)
    )",

    /*
    ==================================================
    NOTIFICATION LOG TABLE
    ==================================================
    Tracks when notifications were sent to prevent duplicates
    ==================================================
    */
    "CREATE TABLE IF NOT EXISTS notification_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        notification_type VARCHAR(100) NOT NULL,
        sent_date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
        UNIQUE KEY unique_member_type_date (member_id, notification_type, sent_date)
    )"

];

/*
==================================================
EXECUTE MIGRATIONS
==================================================
*/

$success = true;
$results = [];

foreach ($migrations as $index => $sql) {
    
    if ($conn->query($sql)) {
        $results[] = [
            'index' => $index + 1,
            'status' => 'success',
            'message' => 'Migration completed successfully'
        ];
    } else {
        $success = false;
        $results[] = [
            'index' => $index + 1,
            'status' => 'error',
            'message' => $conn->error
        ];
    }
}

/*
==================================================
INSERT DEFAULT MISCELLANEOUS CONTRIBUTION TYPES
==================================================
*/

$default_types = [
    'Coffee Unit',
    'Hasan Imam Shahadat',
    'Special Contribution',
    'Maintenance Fund',
    'Event Support'
];

foreach ($default_types as $type) {
    
    $check = $conn->prepare("
        SELECT id FROM miscellaneous_contribution_types 
        WHERE name = ?
    ");
    $check->bind_param("s", $type);
    $check->execute();
    $check_result = $check->get_result();
    
    if ($check_result->num_rows === 0) {
        $insert = $conn->prepare("
            INSERT INTO miscellaneous_contribution_types (name, is_active)
            VALUES (?, 1)
        ");
        $insert->bind_param("s", $type);
        $insert->execute();
        $insert->close();
    }
    $check->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration Results</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f5f7f8;
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
        }
        
        h1 {
            color: #0b5d1e;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .result-item {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .result-item.success {
            background: #e8f5e9;
            border-left-color: #4caf50;
            color: #2e7d32;
        }
        
        .result-item.error {
            background: #ffebee;
            border-left-color: #f44336;
            color: #c62828;
        }
        
        .result-item strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #0b5d1e;
        }
        
        .summary p {
            margin: 8px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Migration Results</h1>
        
        <?php foreach ($results as $result): ?>
            <div class="result-item <?php echo $result['status']; ?>">
                <strong>Migration #<?php echo $result['index']; ?>: <?php echo ucfirst($result['status']); ?></strong>
                <?php echo $result['message']; ?>
            </div>
        <?php endforeach; ?>
        
        <div class="summary">
            <p><strong>Summary:</strong></p>
            <p>Total Migrations: <?php echo count($results); ?></p>
            <p>Successful: <?php echo count(array_filter($results, function($r) { return $r['status'] === 'success'; })); ?></p>
            <p>Failed: <?php echo count(array_filter($results, function($r) { return $r['status'] === 'error'; })); ?></p>
            <p style="margin-top: 15px; color: #0b5d1e; font-weight: 600;">
                <?php echo $success ? '✓ All migrations completed successfully!' : '✗ Some migrations failed. Please review errors above.'; ?>
            </p>
        </div>
    </div>
</body>
</html>

<?php

/*
==================================================
SESSION MANAGER
==================================================
Handles persistent login and multi-device session
management for the Toloba Qutbi Mohalla website.
==================================================
*/

require_once "config.php";

class SessionManager {

    private $conn;
    private $sessionDuration = 2592000; // 30 days

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /*
    ==================================================
    CREATE PERSISTENT LOGIN TOKEN
    ==================================================
    */
    public function createLoginToken($member_id) {
        
        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        $device_id = $this->getDeviceId();

        $stmt = $this->conn->prepare("
            INSERT INTO login_tokens 
            (member_id, token_hash, device_id, expires_at, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param("isss", $member_id, $token_hash, $device_id, $expires);
        
        if ($stmt->execute()) {
            $stmt->close();
            return $token;
        }
        
        $stmt->close();
        return false;
    }

    /*
    ==================================================
    VERIFY LOGIN TOKEN
    ==================================================
    */
    public function verifyLoginToken($token, $member_id) {
        
        $token_hash = hash('sha256', $token);
        $device_id = $this->getDeviceId();

        $stmt = $this->conn->prepare("
            SELECT id, member_id
            FROM login_tokens
            WHERE token_hash = ?
            AND member_id = ?
            AND device_id = ?
            AND expires_at > NOW()
            AND is_revoked = 0
            LIMIT 1
        ");

        $stmt->bind_param("sis", $token_hash, $member_id, $device_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 1) {
            return true;
        }

        return false;
    }

    /*
    ==================================================
    GET DEVICE ID
    ==================================================
    Creates a unique identifier for the device
    ==================================================
    */
    private function getDeviceId() {
        
        $device_info = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $device_id = hash('sha256', $device_info . $ip_address);
        
        return $device_id;
    }

    /*
    ==================================================
    LOGOUT FROM OTHER DEVICES
    ==================================================
    Revokes all other login tokens for this member
    ==================================================
    */
    public function logoutOtherDevices($member_id) {
        
        $device_id = $this->getDeviceId();

        $stmt = $this->conn->prepare("
            UPDATE login_tokens
            SET is_revoked = 1
            WHERE member_id = ?
            AND device_id != ?
            AND is_revoked = 0
        ");

        $stmt->bind_param("is", $member_id, $device_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /*
    ==================================================
    REVOKE ALL TOKENS FOR MEMBER
    ==================================================
    Logs out member from all devices (logout action)
    ==================================================
    */
    public function revokeAllTokens($member_id) {
        
        $stmt = $this->conn->prepare("
            UPDATE login_tokens
            SET is_revoked = 1
            WHERE member_id = ?
            AND is_revoked = 0
        ");

        $stmt->bind_param("i", $member_id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /*
    ==================================================
    REFRESH LOGIN TOKEN
    ==================================================
    Extends token expiration on active use
    ==================================================
    */
    public function refreshLoginToken($token) {
        
        $token_hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

        $stmt = $this->conn->prepare("
            UPDATE login_tokens
            SET expires_at = ?, updated_at = NOW()
            WHERE token_hash = ?
            AND is_revoked = 0
        ");

        $stmt->bind_param("ss", $expires, $token_hash);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /*
    ==================================================
    CLEAN UP EXPIRED TOKENS
    ==================================================
    Removes old expired tokens from database
    ==================================================
    */
    public function cleanupExpiredTokens() {
        
        $stmt = $this->conn->prepare("
            DELETE FROM login_tokens
            WHERE expires_at < NOW()
            OR (is_revoked = 1 AND updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY))
        ");

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

}

?>

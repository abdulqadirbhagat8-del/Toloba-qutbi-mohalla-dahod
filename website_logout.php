<?php

session_start();

require_once "config.php";
require_once "session_manager.php";

/*
==================================================
WEBSITE MEMBER LOGOUT
==================================================
*/

/* Get member ID before destroying session */

$member_id = $_SESSION['member_id'] ?? null;

/* Remove member session */

unset($_SESSION['member_id']);
unset($_SESSION['member_its_id']);
unset($_SESSION['member_name']);
unset($_SESSION['member_logged_in']);

/* Destroy the complete session */

session_destroy();

/*
==================================================
REVOKE PERSISTENT LOGIN TOKEN
==================================================
*/

if ($member_id) {

    $sessionManager = new SessionManager($conn);
    
    /* Revoke all login tokens for this member */
    $sessionManager->revokeAllTokens($member_id);

}

/* Clear login token cookie */

setcookie(
    "login_token",
    "",
    time() - 3600,
    "/",
    "",
    isset($_SERVER["HTTPS"]),
    true
);

/* Redirect to Member Website Login */

header("Location: websitelogin.php");
exit();

?>

<?php

session_start();

/*
==================================================
WEBSITE MEMBER LOGOUT
==================================================
*/

/* Remove member session */

unset($_SESSION['member_id']);

/* Destroy the complete session */

session_destroy();

/* Redirect to Member Website Login */

header("Location: websitelogin.php");
exit();

?>
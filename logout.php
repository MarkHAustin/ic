<?php
session_start();


 session_destroy();
 unset($_SESSION['clientid']);
 unset($_SESSION['clientname']);
 header("Location: login.php");
?>
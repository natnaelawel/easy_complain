<?php


session_start();
$isLoggedIn = isset($_SESSION["id"]);
if (!$isLoggedIn) {
    $_SESSION['msg'] = "You must log in first";
    header("Location: login.php");
    exit();
}
$isAdmin = isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1;
$isActive = isset($_SESSION["is_active"]) && $_SESSION["is_active"] == 1;

error_reporting(E_ALL);
ini_set("display_errors", 1);

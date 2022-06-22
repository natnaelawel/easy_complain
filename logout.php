<?php
session_start();
if (session_destroy()) {
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    unset($_SESSION['is_active']);
    unset($_SESSION['is_admin']);

    header("Location: login.php");
}

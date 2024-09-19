<?php
session_start();

// Hardcoded credentials for demo purposes
$valid_username = 'helene';
$valid_password = 'helene';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = ucfirst($username);
        header('Location: index.php');
        exit;
    } else {
        header('Location: login.php?error=invalid');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>

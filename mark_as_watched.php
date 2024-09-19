<?php
session_start();

$type = $_GET['type']; // 'movie' or 'tv'
$title = $_GET['title'];

if ($type == 'movie') {
    foreach ($_SESSION['to_watch'] as $key => $movie) {
        if ($movie['title'] === $title) {
            $_SESSION['watched'][] = $movie;
            unset($_SESSION['to_watch'][$key]);
            break;
        }
    }
} elseif ($type == 'tv') {
    foreach ($_SESSION['to_watch_tv'] as $key => $tv_show) {
        if ($tv_show['name'] === $title) {
            $_SESSION['watched_tv'][] = $tv_show;
            unset($_SESSION['to_watch_tv'][$key]);
            break;
        }
    }
}

header('Location: index.php');
exit;
?>

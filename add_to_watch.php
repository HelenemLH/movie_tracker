<?php
session_start();

$type = $_GET['type']; // 'movie' or 'tv'
$title = $_GET['title'];
$poster_path = 'https://image.tmdb.org/t/p/w500' . $_GET['poster_path'];
$release_date = isset($_GET['release_date']) ? $_GET['release_date'] : null;
$first_air_date = isset($_GET['first_air_date']) ? $_GET['first_air_date'] : null;

$item = [
    'id' => $_GET['id'],
    'title' => $title,
    'poster_path' => $poster_path,
    'release_date' => $release_date,
    'first_air_date' => $first_air_date
];

if ($type == 'movie') {
    $_SESSION['to_watch'][] = $item;
} elseif ($type == 'tv') {
    $item['name'] = $title; // For TV shows, use 'name' instead of 'title'
    $_SESSION['to_watch_tv'][] = $item;
}

header('Location: index.php');
exit;
?>

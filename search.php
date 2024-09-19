<?php
session_start();

if (!isset($_GET['query']) || empty($_GET['query'])) {
    header('Location: index.php');
    exit;
}

$API_KEY = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzM2QyZWE4ODAyOWQwNzA1YWU2NDIyOTQwMmZiNWZmOCIsIm5iZiI6MTcyMTgyNDI0OS4zODA1NDksInN1YiI6IjY2OTU2NTc4M2NlMDlkZGVjNDRjMjY2YyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.QY0t-k0EQcIz0rEhakWKqpeqzD5rw4-YA9BpcikeoHs'; 
$API_URL = 'https://api.themoviedb.org/3';

$query = urlencode($_GET['query']);
$url = "$API_URL/search/movie?api_key=$API_KEY&query=$query";

$response = file_get_contents($url);
$movies = json_decode($response, true)['results'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Search Results - Movie Tracker</title>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</h1>
            <a href="index.php" class="btn">Back to Home</a>
        </header>
        <div id="content">
            <?php if (!empty($movies)) : ?>
                <ul>
                    <?php foreach ($movies as $movie) : ?>
                        <li>
                            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster" style="width:150px; height:auto;">
                            <strong>Title:</strong> <?php echo htmlspecialchars($movie['title']); ?><br>
                            <a href="add_to_watch.php?title=<?php echo urlencode($movie['title']); ?>&poster_path=<?php echo urlencode($movie['poster_path']); ?>" class="btn">Add to Watch List</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No results found for "<?php echo htmlspecialchars($_GET['query']); ?>".</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
session_start();

if (!isset($_GET['movie_id'])) {
    header('Location: index.php');
    exit;
}

$API_KEY = '33d2ea88029d0705ae64229402fb5ff8';
$API_URL = 'https://api.themoviedb.org/3';

$movie_id = intval($_GET['movie_id']);
$movie_url = "$API_URL/movie/$movie_id?api_key=$API_KEY&language=en-US";
$movie_response = file_get_contents($movie_url);
$movie = json_decode($movie_response, true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie & TV Tracker</title>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
            <a href="index.php" class="btn">Back to Home</a>
        </header>

        <div id="content">
            <div class="movie-details">
                <div class="movie-poster">
                    <?php if (!empty($movie['poster_path'])) : ?>
                        <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster">
                    <?php else : ?>
                        <img src="path/to/placeholder-image.jpg" alt="No Poster Available">
                    <?php endif; ?>
                </div>
                <div class="movie-info">
                    <h2>Overview</h2>
                    <p><?php echo htmlspecialchars($movie['overview']); ?></p>
                    <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                    <p><strong>Runtime:</strong> <?php echo htmlspecialchars($movie['runtime']); ?> minutes</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

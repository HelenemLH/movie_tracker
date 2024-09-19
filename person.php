<?php
session_start();

if (!isset($_GET['person_id'])) {
    header('Location: index.php');
    exit;
}

$API_KEY = '33d2ea88029d0705ae64229402fb5ff8';
$API_URL = 'https://api.themoviedb.org/3';

$person_id = intval($_GET['person_id']);

// Fetch person details
$person_url = "$API_URL/person/$person_id?api_key=$API_KEY&language=en-US";
$person_response = file_get_contents($person_url);
$person = json_decode($person_response, true);

// Fetch movie credits
$movie_credits_url = "$API_URL/person/$person_id/movie_credits?api_key=$API_KEY&language=en-US";
$movie_credits_response = file_get_contents($movie_credits_url);
$movie_credits = json_decode($movie_credits_response, true)['cast'] ?? [];

// Fetch TV credits
$tv_credits_url = "$API_URL/person/$person_id/tv_credits?api_key=$API_KEY&language=en-US";
$tv_credits_response = file_get_contents($tv_credits_url);
$tv_credits = json_decode($tv_credits_response, true)['cast'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($person['name']); ?> - Movie & TV Tracker</title>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1><?php echo htmlspecialchars($person['name']); ?></h1>
            <a href="index.php" class="btn">Back to Home</a>
        </header>

        <div id="content">
            <div class="person-details">
                <div class="person-photo">
                    <?php if (!empty($person['profile_path'])) : ?>
                        <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($person['profile_path']); ?>" alt="<?php echo htmlspecialchars($person['name']); ?> Photo">
                    <?php else : ?>
                        <img src="path/to/placeholder-image.jpg" alt="No Photo Available">
                    <?php endif; ?>
                </div>
                <div class="person-bio">
                    <h2>Biography</h2>
                    <p><?php echo nl2br(htmlspecialchars($person['biography'])); ?></p>
                </div>
            </div>

            <section class="filmography">
                <h2>Filmography</h2>

                <h3>Movies</h3>
                <?php if (!empty($movie_credits)) : ?>
                    <ul class="film-list">
                        <?php foreach ($movie_credits as $movie) : ?>
                            <li>
                                <?php if (!empty($movie['poster_path'])) : ?>
                                    <img src="https://image.tmdb.org/t/p/w200<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster">
                                <?php endif; ?>
                                <a href="movie_details.php?movie_id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                (<?php echo substr($movie['release_date'], 0, 4); ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No movie credits found.</p>
                <?php endif; ?>

                <h3>TV Shows</h3>
                <?php if (!empty($tv_credits)) : ?>
                    <ul class="film-list">
                        <?php foreach ($tv_credits as $tv_show) : ?>
                            <li>
                                <?php if (!empty($tv_show['poster_path'])) : ?>
                                    <img src="https://image.tmdb.org/t/p/w200<?php echo htmlspecialchars($tv_show['poster_path']); ?>" alt="<?php echo htmlspecialchars($tv_show['name']); ?> Poster">
                                <?php endif; ?>
                                <a href="tv_details.php?tv_id=<?php echo $tv_show['id']; ?>"><?php echo htmlspecialchars($tv_show['name']); ?></a>
                                (<?php echo substr($tv_show['first_air_date'], 0, 4); ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No TV show credits found.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</body>
</html>

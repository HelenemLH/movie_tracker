<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$API_KEY = '33d2ea88029d0705ae64229402fb5ff8';
$API_URL = 'https://api.themoviedb.org/3';

function getImdbId($id, $type, $API_KEY) {
    $external_ids_url = "https://api.themoviedb.org/3/$type/$id/external_ids?api_key=$API_KEY";
    $external_ids_response = file_get_contents($external_ids_url);
    $external_ids_data = json_decode($external_ids_response, true);
    return $external_ids_data['imdb_id'] ?? null;
}

// Fetch list of movie genres
$genres_url = "$API_URL/genre/movie/list?api_key=$API_KEY&language=en-US";
$genres_response = file_get_contents($genres_url);
$genres = json_decode($genres_response, true)['genres'] ?? [];

// Fetch list of TV show genres
$tv_genres_url = "$API_URL/genre/tv/list?api_key=$API_KEY&language=en-US";
$tv_genres_response = file_get_contents($tv_genres_url);
$tv_genres = json_decode($tv_genres_response, true)['genres'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Movie & TV Tracker</title>
    <style>
        .section-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
        }
        .section-content {
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1>MOVIE & TV TRACKER</h1>
            <div id="user-info">
                <span id="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </header>
        <div id="searchbar-container">
            <form id="search-form" action="search.php" method="GET">
                <div id="searchbar">
                    <input id="searchbartext" type="text" name="query" placeholder="Search for a movie or TV show..." required>
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>

        <div id="categories">
            <h3>Browse by Movie Genre</h3>
            <ul>
                <?php foreach ($genres as $genre) : ?>
                    <li><a href="categories.php?type=movie&genre_id=<?php echo $genre['id']; ?>&genre_name=<?php echo urlencode($genre['name']); ?>"><?php echo htmlspecialchars($genre['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <h3>Browse by TV Show Genre</h3>
            <ul>
                <?php foreach ($tv_genres as $genre) : ?>
                    <li><a href="categories.php?type=tv&genre_id=<?php echo $genre['id']; ?>&genre_name=<?php echo urlencode($genre['name']); ?>"><?php echo htmlspecialchars($genre['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div id="content">
            <div class="section">
                <div class="section-toggle" onclick="toggleSection('movies-to-watch')">Movies To Watch</div>
                <div id="movies-to-watch" class="section-content">
                    <?php if (!empty($_SESSION['to_watch'])) : ?>
                        <ul class="movie-list">
                            <?php foreach ($_SESSION['to_watch'] as $movie) : ?>
                                <?php $imdb_id = getImdbId($movie['id'], 'movie', $API_KEY); ?>
                                <li class="movie-item">
                                    <?php if (!empty($movie['poster_path'])) : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster" class="movie-poster">
                                        </a>
                                    <?php else : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="path/to/placeholder-image.jpg" alt="No Poster Available" class="movie-poster">
                                        </a>
                                    <?php endif; ?>
                                    <strong>Title:</strong> <?php echo htmlspecialchars($movie['title']); ?><br>
                                    <strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?><br>
                                    <a href="mark_as_watched.php?type=movie&title=<?php echo urlencode($movie['title']); ?>" class="btn">Mark as Watched</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Your Movies To Watch list is empty.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section">
                <div class="section-toggle" onclick="toggleSection('movies-watched')">Movies Watched</div>
                <div id="movies-watched" class="section-content">
                    <?php if (!empty($_SESSION['watched'])) : ?>
                        <ul class="movie-list">
                            <?php foreach ($_SESSION['watched'] as $movie) : ?>
                                <?php $imdb_id = getImdbId($movie['id'], 'movie', $API_KEY); ?>
                                <li class="movie-item">
                                    <?php if (!empty($movie['poster_path'])) : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster" class="movie-poster">
                                        </a>
                                    <?php else : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="path/to/placeholder-image.jpg" alt="No Poster Available" class="movie-poster">
                                        </a>
                                    <?php endif; ?>
                                    <strong>Title:</strong> <?php echo htmlspecialchars($movie['title']); ?><br>
                                    <strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?><br>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Your Movies Watched list is empty.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section">
                <div class="section-toggle" onclick="toggleSection('tv-to-watch')">TV Shows To Watch</div>
                <div id="tv-to-watch" class="section-content">
                    <?php if (!empty($_SESSION['to_watch_tv'])) : ?>
                        <ul class="movie-list">
                            <?php foreach ($_SESSION['to_watch_tv'] as $tv_show) : ?>
                                <?php $imdb_id = getImdbId($tv_show['id'], 'tv', $API_KEY); ?>
                                <li class="movie-item">
                                    <?php if (!empty($tv_show['poster_path'])) : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($tv_show['poster_path']); ?>" alt="<?php echo htmlspecialchars($tv_show['name']); ?> Poster" class="movie-poster">
                                        </a>
                                    <?php else : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="path/to/placeholder-image.jpg" alt="No Poster Available" class="movie-poster">
                                        </a>
                                    <?php endif; ?>
                                    <strong>Title:</strong> <?php echo htmlspecialchars($tv_show['name']); ?><br>
                                    <strong>First Air Date:</strong> <?php echo htmlspecialchars($tv_show['first_air_date']); ?><br>
                                    <a href="mark_as_watched.php?type=tv&title=<?php echo urlencode($tv_show['name']); ?>" class="btn">Mark as Watched</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Your TV Shows To Watch list is empty.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section">
                <div class="section-toggle" onclick="toggleSection('tv-watched')">TV Shows Watched</div>
                <div id="tv-watched" class="section-content">
                    <?php if (!empty($_SESSION['watched_tv'])) : ?>
                        <ul class="movie-list">
                            <?php foreach ($_SESSION['watched_tv'] as $tv_show) : ?>
                                <?php $imdb_id = getImdbId($tv_show['id'], 'tv', $API_KEY); ?>
                                <li class="movie-item">
                                    <?php if (!empty($tv_show['poster_path'])) : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($tv_show['poster_path']); ?>" alt="<?php echo htmlspecialchars($tv_show['name']); ?> Poster" class="movie-poster">
                                        </a>
                                    <?php else : ?>
                                        <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                            <img src="path/to/placeholder-image.jpg" alt="No Poster Available" class="movie-poster">
                                        </a>
                                    <?php endif; ?>
                                    <strong>Title:</strong> <?php echo htmlspecialchars($tv_show['name']); ?><br>
                                    <strong>First Air Date:</strong> <?php echo htmlspecialchars($tv_show['first_air_date']); ?><br>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Your TV Shows Watched list is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' || section.style.display === '' ? 'block' : 'none';
        }
    </script>
</body>
</html>

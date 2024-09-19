<?php
session_start();

if (!isset($_GET['type']) || !isset($_GET['genre_id']) || !isset($_GET['genre_name'])) {
    header('Location: index.php');
    exit;
}

$API_KEY = '33d2ea88029d0705ae64229402fb5ff8';
$API_URL = 'https://api.themoviedb.org/3';

$type = $_GET['type']; // 'movie' or 'tv'
$genre_id = intval($_GET['genre_id']);
$genre_name = htmlspecialchars($_GET['genre_name']);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$discover_url = "$API_URL/discover/$type?api_key=$API_KEY&with_genres=$genre_id&language=en-US&page=$page";
$discover_response = file_get_contents($discover_url);
$discover_data = json_decode($discover_response, true);
$items = $discover_data['results'] ?? [];
$total_pages = $discover_data['total_pages'] ?? 1;

function getCast($id, $type, $API_KEY) {
    $credits_url = "https://api.themoviedb.org/3/$type/$id/credits?api_key=$API_KEY&language=en-US";
    $credits_response = file_get_contents($credits_url);
    $credits_data = json_decode($credits_response, true);
    return $credits_data['cast'] ?? [];
}

function getImdbId($id, $type, $API_KEY) {
    $API_URL = 'https://api.themoviedb.org/3';
    $external_ids_url = "$API_URL/$type/$id/external_ids?api_key=$API_KEY";
    $external_ids_response = file_get_contents($external_ids_url);
    $external_ids_data = json_decode($external_ids_response, true);
    return $external_ids_data['imdb_id'] ?? null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $genre_name; ?> <?php echo ucfirst($type); ?>s - Movie & TV Tracker</title>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1><?php echo $genre_name; ?> <?php echo ucfirst($type); ?>s</h1>
            <a href="index.php" class="btn">Back to Home</a>
        </header>
        <div id="content">
            <?php if (!empty($items)) : ?>
                <ul class="movie-list">
                    <?php foreach ($items as $item) : ?>
                        <?php $imdb_id = getImdbId($item['id'], $type, $API_KEY); ?>
                        <li class="movie-item">
                            <?php if (!empty($item['poster_path'])) : ?>
                                <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                    <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($item['poster_path']); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? $item['name']); ?> Poster" class="movie-poster">
                                </a>
                            <?php else : ?>
                                <a href="https://www.imdb.com/title/<?php echo $imdb_id; ?>" target="_blank">
                                    <img src="path/to/placeholder-image.jpg" alt="No Poster Available" class="movie-poster">
                                </a>
                            <?php endif; ?>
                            <strong>Title:</strong> <?php echo htmlspecialchars($item['title'] ?? $item['name']); ?><br>
                            <strong><?php echo ($type == 'movie') ? 'Release Date' : 'First Air Date'; ?>:</strong> <?php echo htmlspecialchars($item['release_date'] ?? $item['first_air_date']); ?><br>
                            <strong>Cast:</strong>
                            <ul class="cast-list">
                                <?php $cast = getCast($item['id'], $type, $API_KEY); ?>
                                <?php foreach (array_slice($cast, 0, 5) as $actor) : ?>
                                    <li><a href="person.php?person_id=<?php echo $actor['id']; ?>"><?php echo htmlspecialchars($actor['name']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="add_to_watch.php?type=<?php echo $type; ?>&title=<?php echo urlencode($item['title'] ?? $item['name']); ?>&poster_path=<?php echo urlencode($item['poster_path']); ?>&release_date=<?php echo urlencode($item['release_date'] ?? ''); ?>&first_air_date=<?php echo urlencode($item['first_air_date'] ?? ''); ?>" class="btn">Add to Watch List</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?type=<?php echo $type; ?>&genre_id=<?php echo $genre_id; ?>&genre_name=<?php echo urlencode($genre_name); ?>&page=<?php echo $page - 1; ?>" class="btn">Previous</a>
                    <?php endif; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?type=<?php echo $type; ?>&genre_id=<?php echo $genre_id; ?>&genre_name=<?php echo urlencode($genre_name); ?>&page=<?php echo $page + 1; ?>" class="btn">Next</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <p>No <?php echo ucfirst($type); ?>s found in this genre.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

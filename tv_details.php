<?php
session_start();

if (!isset($_GET['tv_id'])) {
    header('Location: index.php');
    exit;
}

$API_KEY = '33d2ea88029d0705ae64229402fb5ff8';
$API_URL = 'https://api.themoviedb.org/3';

$tv_id = intval($_GET['tv_id']);

// Fetch TV show details
$tv_url = "$API_URL/tv/$tv_id?api_key=$API_KEY&language=en-US";
$tv_response = file_get_contents($tv_url);
$tv_show = json_decode($tv_response, true);

// Fetch TV show credits (main cast)
$credits_url = "$API_URL/tv/$tv_id/credits?api_key=$API_KEY&language=en-US";
$credits_response = file_get_contents($credits_url);
$credits = json_decode($credits_response, true)['cast'] ?? [];

// Fetch TV show seasons
$seasons = $tv_show['seasons'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo htmlspecialchars($tv_show['name']); ?> - TV Show Details</title>
</head>
<body>
    <div id="wrapper">
        <header id="header">
            <h1><?php echo htmlspecialchars($tv_show['name']); ?></h1>
            <a href="index.php" class="btn">Back to Home</a>
        </header>

        <div id="content">
            <div class="tv-show-details">
                <div class="tv-show-poster">
                    <?php if (!empty($tv_show['poster_path'])) : ?>
                        <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($tv_show['poster_path']); ?>" alt="<?php echo htmlspecialchars($tv_show['name']); ?> Poster">
                    <?php else : ?>
                        <img src="path/to/placeholder-image.jpg" alt="No Poster Available">
                    <?php endif; ?>
                </div>
                <div class="tv-show-info">
                    <h2>Overview</h2>
                    <p><?php echo htmlspecialchars($tv_show['overview']); ?></p>
                    <p><strong>First Air Date:</strong> <?php echo htmlspecialchars($tv_show['first_air_date']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($tv_show['status']); ?></p>
                    <p><strong>Seasons:</strong> <?php echo count($seasons); ?></p>
                </div>
            </div>

            <section class="seasons">
                <h2>Seasons</h2>
                <?php if (!empty($seasons)) : ?>
                    <ul class="season-list">
                        <?php foreach ($seasons as $season) : ?>
                            <li>
                                <strong><?php echo htmlspecialchars($season['name']); ?></strong>
                                <span>(<?php echo htmlspecialchars($season['air_date']); ?>) - <?php echo $season['episode_count']; ?> episodes</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No seasons information available.</p>
                <?php endif; ?>
            </section>

            <section class="cast">
                <h2>Main Cast</h2>
                <div class="cast-toggle" onclick="toggleCast()">Show Full Cast</div>
                <ul id="cast-list" class="cast-list">
                    <?php foreach ($credits as $actor) : ?>
                        <li>
                            <a href="person.php?person_id=<?php echo $actor['id']; ?>"><?php echo htmlspecialchars($actor['name']); ?></a>
                            as <em><?php echo htmlspecialchars($actor['character']); ?></em>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>
    </div>
    <script>
        function toggleCast() {
            const castList = document.getElementById('cast-list');
            const toggleButton = document.querySelector('.cast-toggle');
            if (castList.style.display === 'none' || castList.style.display === '') {
                castList.style.display = 'block';
                toggleButton.textContent = 'Hide Full Cast';
            } else {
                castList.style.display = 'none';
                toggleButton.textContent = 'Show Full Cast';
            }
        }

        // Initially hide the cast list
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('cast-list').style.display = 'none';
        });
    </script>
</body>
</html>

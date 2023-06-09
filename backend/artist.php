<?php
session_start();

// Проверка аутентификации
if (!isset($_SESSION['user_id'])) {
    // Перенаправление на страницу входа
    header('Location: backend/login.php');
    exit();
}

// Проверка уровня доступа пользователя
$role = $_SESSION['role'];

if ($role !== 'admin' && $role !== 'junior_admin') {
    // Перенаправление на страницу с сообщением о недостаточных правах доступа
    header('Location: backend/unauthorized.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Твоя.Музыка</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header main">
        <div id="inner-header">
            <div class="wrap">
                <a href="/" class="logo"><img src="../fonts/logo.png" alt="ТВОЯ МУЗЫКА"></a>
                <ul class="header-menu">
                    <li><a href="/rating.php"><span>Рейтинг</span> </a></li>
                    <li><a href="/artists.php"><span>Артисты</span> </a></li>
                    <?php
                        $role = $_SESSION['role'];
                        if ($role == 'admin') {
                    ?>
                    <li><a href="/add-artist.php"><span>Добавить артиста</span> </a></li>
                    <li><a href="/rate-song.php"><span>Оценить трек</span> </a></li>
                    <?php
                    }
                    else {
                    ?>
                    <style>
                        .header-menu {
                        grid-template-columns: 1fr 1fr;
                        }
                    </style>
                    <?php
                    }
                    ?>
                </ul>
                <a class="logout" href="logout.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="logout"><path d="M4,12a1,1,0,0,0,1,1h7.59l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l4-4a1,1,0,0,0,.21-.33,1,1,0,0,0,0-.76,1,1,0,0,0-.21-.33l-4-4a1,1,0,1,0-1.42,1.42L12.59,11H5A1,1,0,0,0,4,12ZM17,2H7A3,3,0,0,0,4,5V8A1,1,0,0,0,6,8V5A1,1,0,0,1,7,4H17a1,1,0,0,1,1,1V19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V16a1,1,0,0,0-2,0v3a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V5A3,3,0,0,0,17,2Z"></path></svg></a>
            </div>
        </div>
    </header>
    <main>
    <?php
// Подключение к базе данных
include 'connect.php';
$db = new mysqli($host, $user, $passw, $db_name);

// Получение идентификатора артиста из параметра URL
$artistId = $_GET['id'];

// Получение информации об артисте из таблицы "artists" по идентификатору
$query = "SELECT * FROM artists WHERE id = $artistId";
$result = $db->query($query);

if ($result && $result->num_rows > 0) {
    $artist = $result->fetch_assoc();
    $artistName = $artist['name'];
    $artistImage = "../images/" . strtolower($artistName) . ".jpg"; // Формирование пути к изображению на основе имени автора

    // Вывод информации об артисте
    echo '<div class="home-artists-section-grid artist-card-page">';
    echo '<div class="card-wrapper card-artist card-artist">';
    echo '<img src="' . $artistImage . '" class="card-blur">';
    echo '<div class="card">';
    echo '<div class="card-image"><img src="' . $artistImage . '"></div>';
    echo '<div class="card-content">';
    echo '<div class="card-header">';
    echo '<div class="card-header-info">';
    echo '<div class="card-title">' . $artistName . '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</a>';
    echo '</div>';
    echo '</div>';

    // Получение списка треков артиста из таблицы "songs" по идентификатору артиста
    $query = "SELECT songs.*, MAX(evaluations.rhymes) AS rhymes, MAX(evaluations.structure) AS structure, MAX(evaluations.realization) AS realization,
        MAX(evaluations.charisma) AS charisma, MAX(evaluations.atmosphere) AS atmosphere, MAX(evaluations.trendiness) AS trendiness
        FROM songs
        INNER JOIN evaluations ON songs.id = evaluations.song
        WHERE FIND_IN_SET('$artistId', songs.artist_name) > 0
        GROUP BY songs.id";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<div class="song-list">';
        while ($song = $result->fetch_assoc()) {
            $songName = $song['name'];
            $rhymes = $song['rhymes'];
            $structure = $song['structure'];
            $realization = $song['realization'];
            $charisma = $song['charisma'];
            $trendiness = $song['trendiness'];
            $atmosphere = $song['atmosphere'];
            $rating = $song['result'];
            $artistIds = explode(',', $song['artist_name']);

            // Получение имен артистов на основе их идентификаторов
            $artistNames = array();
            foreach ($artistIds as $artistId) {
                $artistQuery = "SELECT name FROM artists WHERE id = $artistId";
                $artistResult = $db->query($artistQuery);
                if ($artistResult && $artistResult->num_rows > 0) {
                    $artistData = $artistResult->fetch_assoc();
                    $artistNames[] = $artistData['name'];
                }
            }

            echo '<div class="wrap">';
            echo '<div class="rating-list-item rating-item-track">';
            echo '<div class="rating-list-item-cover">';
            echo '<img src="../images/' . $songName . '.jpg" alt="' . $songName . '">';
            echo '</div>';
            echo '<div class="item-track-names">';
            echo '<div class="item-track">' . $songName . '</div>';

            // Вывод имен авторов трека
            echo '<div class="item-artist">';
            $lastArtistIndex = count($artistNames) - 1;
            foreach ($artistNames as $index => $artistName) {
                echo '<p>' . $artistName . ($index !== $lastArtistIndex ? ',' : '') . '</p>' . '  ‌‌‍‍';              
            }
            echo '</div>';

            echo '</div>';
            $ratingClass = ($rating == 90) ? 'gold' : '';
            echo '<div class="rating-list-item-rating">';
            echo '<span class="' . $ratingClass . '">' . $rating . '</span>';
            echo '</div>';
            echo '<div class="rating-details">';
            echo '<span class="trending-rating-value">' . $trendiness . '</span>';
            echo '<span class="atmosphere-rating-value">' . $atmosphere . '</span>';
            echo '<span class="base-rating-value">' . $rhymes . '</span>';
            echo '<span class="base-rating-value">' . $structure . '</span>';
            echo '<span class="base-rating-value">' . $realization . '</span>';
            echo '<span class="base-rating-value">' . $charisma . '</span>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo 'Нет доступных треков для данного артиста.';
    }
} else {
    echo 'Артист не найден.';
}

// Закрытие соединения с базой данных
$db->close();
?>

    </main>
</body>

<script src="../js/jquery-3.5.1.js"></script>
<script src="../js/scripts.js"></script>

</html>
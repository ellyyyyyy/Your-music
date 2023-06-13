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
                    <li><a href="/add-artist.php"><span>Добавить артиста</span> </a></li>
                    <li><a href="/rate-song.php"><span>Оценить трек</span> </a></li>
                </ul>
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
    echo '</div>';
    echo '</a>';
    echo '</div>';

    // Получение списка треков артиста из таблицы "songs" по идентификатору артиста
    $query = "SELECT * FROM songs WHERE artist_name = '$artistId'";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        echo '<div class="song-list">';
        while ($song = $result->fetch_assoc()) {
            $songName = $song['name'];
            echo '<div class="wrap">';
            echo '<div class="rating-list-item rating-item-track">';
            echo '<div class="rating-list-item-cover">';
            echo '<img src="../images/' . $songName . '.jpg" alt="' . $songName . '">';
            echo '</div>';
            echo '<div class="item-track-names">';
            echo '<div class="item-track">' . $songName . '</div>';
            echo '<div class="item-artist">';
            echo '<p href="/"></p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="rating-list-item-rating"></div>';
            echo '<div class="rating-details">';
            echo '<span class="tranding-rating-value"></span>';
            echo '<span class="atmosphere-rating-value"></span>';
            echo '<span class="base-rating-value"></span>';
            echo '<span class="base-rating-value"></span>';
            echo '<span class="base-rating-value"></span>';
            echo '<span class="base-rating-value"></span>';
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
<?php
// Подключение к базе данных
include 'connect.php';
$db = new mysqli($host, $user, $passw, $db_name);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $authors = $_POST['authors'];
    $rhymes = $_POST['slider1'];
    $structure = $_POST['slider2'];
    $realization = $_POST['slider3'];
    $charisma = $_POST['slider4'];
    $atmospherePercentage = $_POST['slider5'] / 100;
    $trendinessPercentage = $_POST['slider6'] / 100;
    $songName = $_POST['song'];

    // Вставка данных в таблицу "artists" и получение идентификаторов авторов
    $artistIds = [];
    foreach ($authors as $authorId) {
        $artistIds[] = $authorId;
        $artistQuery = "INSERT INTO artists (`id`, `name`) VALUES ('$authorId', '') ON DUPLICATE KEY UPDATE `id` = `id`";
        $db->query($artistQuery);
    }

    // Вставка данных в таблицу "songs"
    $songQuery = "INSERT INTO songs (`name`, `artist_name`) VALUES ('$songName', '')";
    $db->query($songQuery);
    $songId = $db->insert_id;

    // Вставка данных в таблицу "evaluations"
    $result = $rhymes + $structure + $realization + $charisma + $atmospherePercentage + $trendinessPercentage;
    $evaluationQuery = "INSERT INTO evaluations (`song`, `rhymes`, `structure`, `realization`, `charisma`, `atmosphere`, `trendiness`, `result`) VALUES ('$songId', '$rhymes', '$structure', '$realization', '$charisma', '$atmospherePercentage', '$trendinessPercentage', '$result')";
    $db->query($evaluationQuery);

    // Присваивание авторов песне
    foreach ($artistIds as $artistId) {
        $assignQuery = "UPDATE songs SET `artist_name` = CONCAT(`artist_name`, '$artistId,') WHERE `id` = '$songId'";
        $db->query($assignQuery);
    }

    // Перенаправление на страницу добавления песни
    header('Location: ../add-song.php');
    exit;
}
?>
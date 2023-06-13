
<?php
// Подключение к базе данных
include 'connect.php';
$db = new mysqli($host, $user, $passw, $db_name);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $authors = $_POST['authors'];
    $selectedAuthors = implode(',', $authors); // Преобразование массива в строку, разделенную запятыми
    $rhymes = $_POST['slider1'];
    $structure = $_POST['slider2'];
    $realization = $_POST['slider3'];
    $charisma = $_POST['slider4'];
    $atmosphere = $_POST['slider5'];
    $trendiness = $_POST['slider6'];
    $atmospherePercentage = $_POST['slider5'] * 10;
    $trendinessPercentage = $_POST['slider6'] * 10;
    $songName = $_POST['song'];
    $releaseDate = $_POST['releaseDate'];

    // Вставка данных в таблицу "artists" и получение идентификаторов авторов
    $artistIds = [];
    foreach ($authors as $authorId) {
        $artistIds[] = $authorId;
        $artistQuery = "INSERT INTO artists (`id`, `name`) VALUES ('$authorId', '') ON DUPLICATE KEY UPDATE `id` = `id`";
        $db->query($artistQuery);
    }

    // Преобразование массива идентификаторов в строку
    $artistIdsString = implode(',', $artistIds);
    // Вставка данных в таблицу "songs"
    $result = $rhymes + $structure + $realization + $charisma + ($atmospherePercentage * 0.5) + ($trendinessPercentage * 0.5);
    $songQuery = "INSERT INTO songs (`name`, `artist_name`, `date`, `result`) VALUES ('$songName', '$artistIdsString', '$releaseDate', '$result')";
    if (!$db->query($songQuery)) {
        echo "Ошибка при выполнении запроса: " . $db->error;
        exit;
    }

    $songId = $db->insert_id;
    $image_name = $songName . '.jpg'; // Присваиваем имя изображению
    $target_dir = '../images/'; // Директория для сохранения изображения
    $target_file = $target_dir . $image_name;
    // Изменяем размер изображения
    $source_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
    $resized_image = imagescale($source_image, 300, 300);
    imagejpeg($resized_image, $target_file);
    // Очищаем память, освобождая ресурсы
    imagedestroy($source_image);
    imagedestroy($resized_image);

    // Вставка данных в таблицу "evaluations"
    $evaluationQuery = "INSERT INTO evaluations (`song`, `rhymes`, `structure`, `realization`, `charisma`, `atmosphere`, `trendiness`, `result`) VALUES ('$songId', '$rhymes', '$structure', '$realization', '$charisma', '$atmosphere', '$trendiness', '$result')";
    if (!$db->query($evaluationQuery)) {
        echo "Ошибка при выполнении запроса: " . $db->error;
        exit;
    }

    // Перенаправление на страницу добавления песни
    header('Location: ../rate-song.php');
    exit;
}
?>
<?php
// Подключение к базе данных
include 'connect.php';
$db = new mysqli($host, $user, $passw, $db_name);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $authors = isset($_POST['authors']) ? $_POST['authors'] : [];
    $selectedAuthors = implode(',', $authors); // Преобразование массива в строку, разделенную запятыми
    $rhymes = isset($_POST['slider1']) ? $_POST['slider1'] : 0;
    $structure = isset($_POST['slider2']) ? $_POST['slider2'] : 0;
    $realization = isset($_POST['slider3']) ? $_POST['slider3'] : 0;
    $charisma = isset($_POST['slider4']) ? $_POST['slider4'] : 0;
    $atmosphere = isset($_POST['slider5']) ? $_POST['slider5'] : 0;
    $trendiness = isset($_POST['slider6']) ? $_POST['slider6'] : 0;
    $atmospherePercentage = $atmosphere * 10;
    $trendinessPercentage = $trendiness * 10;
    $songName = isset($_POST['song']) ? $_POST['song'] : '';
    $releaseDate = date('Y-m-d');

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
    $image_name = $songName . '.png'; // Присваиваем имя изображению с расширением PNG
    $target_dir = '../images/'; // Директория для сохранения изображения
    $target_file = $target_dir . $image_name;
// Проверка типа загружаемого изображения
$image_info = getimagesize($_FILES['image']['tmp_name']);
$image_mime = $image_info['mime'];
if ($image_mime == 'image/jpeg' || $image_mime == 'image/png') {
    // Изменяем размер изображения
    if ($image_mime == 'image/jpeg') {
        $source_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
    } else {
        $source_image = imagecreatefrompng($_FILES['image']['tmp_name']);
    }
    $resized_image = imagescale($source_image, 300, 300);
    // Преобразование изображения PNG в JPEG
    $target_file_jpg = $target_dir . $songName . '.jpg';
    imagejpeg($resized_image, $target_file_jpg);
    // Очищаем память, освобождая ресурсы
    imagedestroy($source_image);
    imagedestroy($resized_image);
} else {
    echo "Ошибка: Загруженный файл должен быть изображением в формате JPEG или PNG.";
    exit;
}

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
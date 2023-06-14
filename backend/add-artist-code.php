<?php
// Подключаемся к базе данных
include 'connect.php';
$db = new mysqli($host, $user, $passw, $db_name);
// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];

    $result = $db->query("INSERT INTO artists (`name`) VALUES ('$name')");
    if ($result) {
        // Сохраняем изображение на диск
        $image_name = $name . '.jpg'; // Присваиваем имя изображению
        $target_dir = '../images/'; // Директория для сохранения изображения
        $target_file = $target_dir . $image_name;

        // Проверяем тип загружаемого изображения
        $image_info = getimagesize($_FILES['image']['tmp_name']);
        $image_mime = $image_info['mime'];
        if ($image_mime == 'image/jpeg' || $image_mime == 'image/png') {
            // Изменяем размер изображения
            if ($image_mime == 'image/png') {
                $source_image = imagecreatefrompng($_FILES['image']['tmp_name']);
                if (!$source_image) {
                    echo 'Ошибка при открытии файла изображения PNG.';
                    exit;
                }
                $resized_image = imagescale($source_image, 300, 300);
                if (!$resized_image) {
                    echo 'Ошибка при изменении размера изображения.';
                    imagedestroy($source_image);
                    exit;
                }
                // Преобразуем PNG в JPEG
                imagejpeg($resized_image, $target_file);
                // Очищаем память, освобождая ресурсы
                imagedestroy($source_image);
                imagedestroy($resized_image);
            } else {
                // Изображение уже в формате JPEG
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            }

            header('Location: ../add-artist.php');
            exit;
        } else {
            echo 'Ошибка: Загруженный файл должен быть изображением в формате JPEG или PNG.';
            exit;
        }
    } else {
        echo "Ошибка при выполнении запроса: " . $db->error;
    }
}
?>
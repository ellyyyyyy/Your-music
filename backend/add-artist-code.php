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

    // Изменяем размер изображения
    $source_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
    if ($source_image) {
      $resized_image = imagescale($source_image, 300, 300);
      if ($resized_image) {
        imagejpeg($resized_image, $target_file);

        // Очищаем память, освобождая ресурсы
        imagedestroy($source_image);
        imagedestroy($resized_image);

        header('Location: ../add-artist.php');
        exit;
      } else {
        echo 'Ошибка при изменении размера изображения.';
      }
    } else {
      echo 'Не удалось открыть файл изображения.';
    }
  }
}
?>
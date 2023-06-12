<?php 
$host = "localhost";
$user = "root";
$passw = "";
$db_name = "Your_music";

    $conn = new mysqli($host, $user, $passw, $db_name);

    if ($conn -> connect_error) {
        die("ОШИБКА ПОДКЛЮЧЕНИЯ: " . $conn -> connect_error);
    }
?>

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
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header main">
        <div id="inner-header">
            <div class="wrap">
                <a href="/" class="logo"><img src="fonts/logo.png" alt="ТВОЯ МУЗЫКА"></a>
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
                <a class="logout" href="backend/logout.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="logout"><path d="M4,12a1,1,0,0,0,1,1h7.59l-2.3,2.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0l4-4a1,1,0,0,0,.21-.33,1,1,0,0,0,0-.76,1,1,0,0,0-.21-.33l-4-4a1,1,0,1,0-1.42,1.42L12.59,11H5A1,1,0,0,0,4,12ZM17,2H7A3,3,0,0,0,4,5V8A1,1,0,0,0,6,8V5A1,1,0,0,1,7,4H17a1,1,0,0,1,1,1V19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V16a1,1,0,0,0-2,0v3a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V5A3,3,0,0,0,17,2Z"></path></svg></a>
            </div>
        </div>
    </header>
    <main>
    <div class="wrap">
    <h1>Артисты</h1>
        <section class="artists-list">
                    <div class="home-artists-section-grid">
                    <?php
                    // Подключение к базе данных
                    include 'backend/connect.php';
                    $db = new mysqli($host, $user, $passw, $db_name);

                    // Получение списка артистов из таблицы "artists"
                    $query = "SELECT * FROM artists";
                    $result = $db->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $artistName = $row['name'];
                            $artistId = $row['id'];
                            $artistImage = "images/" . strtolower($artistName) . ".jpg"; // Формирование пути к изображению на основе имени автора
                            echo '<a href="/backend/artist.php?id=' . $artistId . '">';
                            // Вывод карточки артиста
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

                        }
                    }

                    // Закрытие соединения с базой данных
                    $db->close();
                    ?>
                    </div>
            </div>
        </section>
    </main>
</body>

<script src="js/jquery-3.5.1.js"></script>
<script src="js/scripts.js"></script>

</html>
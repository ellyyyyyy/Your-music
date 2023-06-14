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
                    <li><a href="/add-artist.php"><span>Добавить артиста</span> </a></li>
                    <li><a href="/rate-song.php"><span>Оценить трек</span> </a></li>
                </ul>
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
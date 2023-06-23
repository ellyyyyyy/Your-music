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
    <h1>Рейтинг</h1>
    <div class="filter-buttons">
    <a class="filter-button" data-year="2021">2021</a>
    <a class="filter-button" data-year="2022">2022</a>
    <a class="filter-button" data-year="2023" data-default="true">2023</a>
</div>
    <div class="sort-container">
            <a class="reset-filters-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </a>
            <!-- Добавляем кнопки сортировки -->
            <div class="sort-buttons">
                <a class="sort-button asc">
                <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                        <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
                    </svg>
                </a>
                <a class="sort-button desc">
                    <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" fill="currentColor" class="bi bi-sort-down-alt" viewBox="0 0 16 16">
                        <path d="M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z"/>
                    </svg>
                </a>
            </div>
            <!-- Добавляем кнопку сброса фильтров -->
        </div>
    <section class="rating-list">
        <?php
            // Подключение к базе данных
            include 'backend/connect.php';
            $db = new mysqli($host, $user, $passw, $db_name);

            // Получение значения сортировки из GET-параметров
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
            
            $year = isset($_GET['year']) ? $_GET['year'] : '';

            // Формирование условия WHERE для фильтрации по году
            $whereClause = '';
            if (!empty($year)) {
                $startYear = $year;
                $endYear = intval($year) + 1;
                $whereClause = "WHERE songs.date >= '$startYear-01-01' AND songs.date < '$endYear-01-01'";
            }

            // Запрос для получения оцененных песен с сортировкой и фильтрацией
            $query = "SELECT songs.artist_name AS artist_name, songs.name AS song_name, songs.result AS rating,
                        MAX(evaluations.rhymes) AS rhymes, MAX(evaluations.structure) AS structure, MAX(evaluations.realization) AS realization,
                        MAX(evaluations.charisma) AS charisma, MAX(evaluations.atmosphere) AS atmosphere, MAX(evaluations.trendiness) AS trendiness
                        FROM songs
                        INNER JOIN evaluations ON songs.id = evaluations.song
                        $whereClause
                        GROUP BY songs.id";

                    $result = $db->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $songName = $row['song_name'];
                            $artistNames = $row['artist_name'];
                            $artistNames = explode(",", $artistNames);
                            $query = 'SELECT * FROM artists WHERE id=' . $artistNames[0];
                            foreach($artistNames as $id) {
                                $query.= ' or id=' . $id;
                            }
                            $names_result = $db->query($query); 
                            $artists_names = "";
                            if ($names_result->num_rows > 0) {
                                while ($artist = $names_result->fetch_assoc()) {
                                    $artists_names.= $artist['name'] . ', ';
                                }
                            }
                            
                            $artists_names = substr($artists_names, 0, strlen($artists_names) - 2);
                            $rating = $row['rating'];
                            $rhymes = $row['rhymes'];
                            $structure = $row['structure'];
                            $realization = $row['realization'];
                            $charisma = $row['charisma'];
                            $trendiness = $row['trendiness'];
                            $atmosphere = $row['atmosphere'];
                            // Вывод карточки оцененной песни

                            echo '<div class="rating-list-item rating-item-track">';
                            echo '<div class="rating-list-item-cover">';
                            echo '<img src="images/' . $songName . '.jpg" alt="' . $songName . '">';
                            echo '</div>';
                            echo '<div class="item-track-names">';
                            echo '<div class="item-track">' . $songName . '</div>';
                            echo '<div class="item-artist">';
                            echo '<p>' . $artists_names . '</p>';
                            echo '</div>';
                            echo '</div>';

                            $ratingClass = ($rating == 90) ? 'gold' : '';
                            echo '<div class="rating-list-item-rating">';
                            echo '<span class="' . $ratingClass . '">' . $rating . '</span>';
                            echo '</div>';

                            echo '<div class="rating-details">';
                            echo '<span class="tranding-rating-value">' . $trendiness . '</span>';
                            echo '<span class="atmosphere-rating-value">' . $atmosphere . '</span>';
                            echo '<span class="base-rating-value">' . $rhymes . '</span>';
                            echo '<span class="base-rating-value">' . $structure . '</span>';
                            echo '<span class="base-rating-value">' . $realization . '</span>';
                            echo '<span class="base-rating-value">' . $charisma . '</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

            // Закрытие соединения с базой данных
            $db->close();
        ?>

        </div>
    </section>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
<script>

    $(document).ready(function() {
        var originalItems; // Переменная для хранения исходного порядка элементов

        // Обработчик клика по кнопке сортировки
        $('.sort-button').click(function() {
            var sortType = $(this).hasClass('asc') ? 'asc' : 'desc';

            // Удаляем активный класс со всех кнопок сортировки
            $('.sort-button').removeClass('active');

            // Добавляем активный класс на текущую кнопку сортировки
            $(this).addClass('active');

            // Вызываем функцию для сортировки результатов
            sortResults(sortType);
        });

        // Обработчик клика по кнопке "Сбросить фильтры"
        $(".reset-filters-button").click(function() {
            // Удаляем активный класс со всех кнопок сортировки
            $('.sort-button').removeClass('active');

            // Плавно скрываем текущие элементы
            $('.rating-list').fadeOut(300, function() {
                // Восстанавливаем исходный порядок элементов
                $(this).empty().append(originalItems.clone());

                // Плавно отображаем восстановленные элементы
                $(this).fadeIn(100);

                // Плавно скрываем кнопку "Сбросить фильтры"
                $(".reset-filters-button").fadeOut(300);
            });
        });

        // Функция для сортировки результатов
        function sortResults(sortType) {
            // Получаем список элементов
            var items = $('.rating-list-item');

            // Если исходный порядок еще не сохранен, сохраняем его
            if (!originalItems) {
                originalItems = items.clone();
            }

            // Клонируем элементы для сортировки
            var sortedItems = items.clone();

            // Сортируем элементы в соответствии с выбранным типом сортировки
            sortedItems.sort(function(a, b) {
                var ratingA = parseInt($(a).find('.rating-list-item-rating').text());
                var ratingB = parseInt($(b).find('.rating-list-item-rating').text());

                if (sortType === 'asc') {
                    return ratingA - ratingB;
                } else {
                    return ratingB - ratingA;
                }
            });

            // Заменяем текущий список элементов отсортированным списком
            $('.rating-list').fadeOut(300, function() {
                $(this).empty().append(sortedItems).fadeIn(100);
            });

            // Плавно показываем кнопку "Сбросить фильтры"
            $(".reset-filters-button").fadeIn(300);
        }

        // Скрываем кнопку "Сбросить фильтры" при загрузке страницы
        $(".reset-filters-button").hide();
    });


    $(document).ready(function() {
    // Обработчик клика по кнопкам фильтрации
    $('.filter-button').click(function() {
        var year = $(this).data('year');

        // Удаляем класс "active" со всех кнопок фильтрации
        $('.filter-button').removeClass('active');

        // Добавляем класс "active" на нажатую кнопку
        $(this).addClass('active');

        // Перезагрузка страницы с выбранным годом в GET-параметрах
        window.location.href = window.location.pathname + '?year=' + year;
    });
});
</script>
<script src="js/scripts.js"></script>
</html>

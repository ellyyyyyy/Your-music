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
    <h1>Рейтинг</h1>
    <section class="rating-list">
        <?php
            // Подключение к базе данных
            include 'backend/connect.php';
            $db = new mysqli($host, $user, $passw, $db_name);

            // Запрос для получения оцененных песен
            $query = "SELECT songs.artist_name AS artist_name, songs.name AS song_name, songs.result AS rating,
                    MAX(evaluations.rhymes) AS rhymes, MAX(evaluations.structure) AS structure, MAX(evaluations.realization) AS realization,
                    MAX(evaluations.charisma) AS charisma, MAX(evaluations.atmosphere) AS atmosphere, MAX(evaluations.trendiness) AS trendiness
                    FROM songs
                    INNER JOIN evaluations ON songs.id = evaluations.song
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
                            echo '<div class="rating-list-item-rating">' . $rating . '</div>';
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
</html>
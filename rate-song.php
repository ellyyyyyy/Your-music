<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Твоя.Музыка</title>
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/scripts.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
        <form class="rate-song" method="POST" action="backend/rete-song-code.php">
        <h3>Выберите авторов:</h3>
        <select name="authors[]" class="select2" multiple>
            <?php
            // Подключаемся к базе данных
            include 'backend/connect.php';
            $db = new mysqli($host, $user, $passw, $db_name);

            // Получаем список авторов из таблицы "artists"
            $query = "SELECT * FROM artists";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $authorId = $row['id'];
                $authorName = $row['name'];
                echo '<option value="' . $authorId . '">' . $authorName . '</option>';
            }
            }
            ?>
        </select>

  <h3>Выберите значения для ползунков:</h3>
  <div class="slider-container">
    <label for="slider1">Ритмика</label>
    <input type="range" id="slider1" name="slider1" min="1" max="10" value="5"><span class="slider-value">5</span><br>

    <label for="slider2">Структура</label>
    <input type="range" id="slider2" name="slider2" min="1" max="10" value="5"><span class="slider-value">5</span><br>

    <label for="slider3">Реализация</label>
    <input type="range" id="slider3" name="slider3" min="1" max="10" value="5"><span class="slider-value">5</span><br>

    <label for="slider4">Харизма</label>
    <input type="range" id="slider4" name="slider4" min="1" max="10" value="5"><span class="slider-value">5</span><br>

    <label for="slider5">Атмосфера</label>
    <input type="range" id="slider5" name="slider5" min="1" max="5" value="1"><span class="slider-value">1</span><br>

    <label for="slider6">Трендовость</label>
    <input type="range" id="slider6" name="slider6" min="1" max="5" value="1"><span class="slider-value">1</span><br>
    
    <!-- Добавьте остальные ползунки здесь -->
  </div>
  <!-- Добавьте остальные ползунки здесь -->

  <h3>Добавить песню:</h3>
  <label for="song">Название песни:</label>
  <input type="text" id="song" name="song"><br>

  <input type="submit" value="Отправить">
            </form>
    </main>
    </div>
    <script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
</body>
</html>
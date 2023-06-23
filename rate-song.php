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
    header('Location: backend/logout.php');
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
        <h1>Оценить трек</h1>
        <form class="rate-song" method="POST" action="backend/rete-song-code.php" enctype="multipart/form-data">
          <div class="rate-song-form">
            <h3>Название трека</h3>
            <input class="song-name" type="text" id="song" name="song"><br>
            <h3>Выберите авторов</h3>
            <select name="authors[]" class="select2" multiple style="width: 200px;">
            
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
            <h3>Обложка трека</h3>
            <input name="image" type="file" id="images" accept="images/jpg, images/png" required>
            <div class="submit-wrap">
            <div id="result">
              <span id="averageRating">30</span>
            </div>
            <p>/90</p>
            <input type="submit" value="Оценить">
            </div>
          </div>
          <div class="slider-container">
            <label for="slider1">Ритмика</label>
            <input class="slider1" type="range" id="slider1" name="slider1" min="1" max="10" value="5"><span class="slider-value">5</span><br>

            <label for="slider2">Структура</label>
            <input class="slider1" type="range" id="slider2" name="slider2" min="1" max="10" value="5"><span class="slider-value">5</span><br>

            <label for="slider3">Реализация</label>
            <input class="slider1" type="range" id="slider3" name="slider3" min="1" max="10" value="5"><span class="slider-value">5</span><br>

            <label for="slider4">Харизма</label>
            <input class="slider1" type="range" id="slider4" name="slider4" min="1" max="10" value="5"><span class="slider-value">5</span><br>

            <label for="slider5">Атмосфера</label>
            <input class="slider2" type="range" id="slider5" name="slider5" min="1" max="5" value="1"><span class="slider-value">1</span><br>

            <label for="slider6">Трендовость</label>
            <input class="slider3" type="range" id="slider6" name="slider6" min="1" max="5" value="1"><span class="slider-value">1</span><br>
          </div>

    </div>
        </form>
    </main>
    </div>
    <script>
      $(document).ready(function() {
        $('.select2').select2();
      });

      const sliders = document.querySelectorAll(".slider1, .slider2, .slider3, .slider4, .slider5, .slider6");

      const handleSliderChange = () => {
      const values = Array.from(sliders).map(slider => Number(slider.value));

      const rhymes = values[0];
      const structure = values[1];
      const realization = values[2];
      const charisma = values[3];
      const atmospherePercentage = values[4] * 10;
      const trendinessPercentage = values[5] * 10;

      const result = rhymes + structure + realization + charisma + (atmospherePercentage * 0.5) + (trendinessPercentage * 0.5);

      const resultElement = document.getElementById("result");
        resultElement.textContent = result.toFixed(0);
      };

      // Назначение обработчика события на каждый ползунок
      sliders.forEach(slider => {
        slider.addEventListener("input", handleSliderChange);
      });


      const sliders1 = document.querySelectorAll(".slider1");
      const sliders2 = document.querySelectorAll(".slider2");
      const sliders3 = document.querySelectorAll(".slider3");

      sliders1.forEach(function(slider) {
      const min = slider.min;
      const max = slider.max;
      const value = slider.value;
      slider.style.background = `linear-gradient(to right, #5676ea, #334ca5 ${(value - min) / (max - min) * 100}%, #DEE2E6 ${(value - min) / (max - min) * 100}%, #DEE2E6 100%)`;
      slider.oninput = function() {
        this.style.background = `linear-gradient(to right, #5676ea, #334ca5 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 100%)`;
      };
    });

    sliders3.forEach(function(slider) {
      const min = slider.min;
      const max = slider.max;
      const value = slider.value;
      slider.style.background = `linear-gradient(to right, #a050de, #334ca5 ${(value - min) / (max - min) * 100}%, #DEE2E6 ${(value - min) / (max - min) * 100}%, #DEE2E6 100%)`;
      slider.oninput = function() {
        this.style.background = `linear-gradient(to right, #a050de, #672797 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 100%)`;
      };
    });

    sliders2.forEach(function(slider) {
      const min = slider.min;
      const max = slider.max;
      const value = slider.value;
      slider.style.background = `linear-gradient(to right, #da2264, #334ca5 ${(value - min) / (max - min) * 100}%, #DEE2E6 ${(value - min) / (max - min) * 100}%, #DEE2E6 100%)`;
      slider.oninput = function() {
        this.style.background = `linear-gradient(to right, #da2264, #8d1843 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 ${(this.value - this.min) / (this.max - this.min) * 100}%, #DEE2E6 100%)`;
      };
    });
</script>
</body>
<script src="js/scripts.js"></script>
</html>
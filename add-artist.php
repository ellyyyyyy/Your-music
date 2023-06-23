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
    <style>
        #preview-image {
            width: 100%;
            height: auto;
            aspect-ratio: 1/1;
        }
    </style>
</head>
<body>
    <header class="header main">
        <div id="inner-header">
            <div class="wrap">
                <a href="/" class="logo"><img src="fonts/logo.png" alt="ТВОЯ МУЗЫКА"></a>
                <ul class="header-menu">
                    <li><a href="/rating.php"><span>Рейтинг</span></a></li>
                    <li><a href="/artists.php"><span>Артисты</span></a></li>
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
        <h1>Добавить артиста</h1>
            <div class="add-artist-wrap">
            <form class="add-artist" action="backend/add-artist-code.php" method="POST" enctype="multipart/form-data">
            <h3>Имя артиста</h3>
                <input class="artist-name" type="text" name="name" oninput="updatePreviewName(this.value)">
                <h3>Аватар артиста</h3>
                <input name="image" type="file" id="images" accept="images/jpg, images/png" required>
                <button type="submit">Создать карточку артиста</button>
            </form>

            <div class="card-wrapper card-artist card-artist card-add-artist">
                <img src="placeholder.jpg" class="card-blur">
                <div class="card">
                    <div class="card-image">
                        <img id="preview-image" src="images/placeholder.jpg">
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <div class="card-header-info">
                                <div class="card-title" id="preview-name">Имя артиста</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script>
            function updatePreviewName(value) {
                var cardTitle = document.getElementById("preview-name");
                cardTitle.textContent = value;
            }

            function handleImageUpload() {
                var fileInput = document.getElementById('images');
                var previewImage = document.getElementById('preview-image');

                var reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(fileInput.files[0]);
            }

            var fileInput = document.getElementById('images');
            fileInput.addEventListener('change', handleImageUpload);
        </script>

    </main>
</body>
<script src="js/scripts.js"></script>
</html>

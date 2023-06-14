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
                    <li><a href="/add-artist.php"><span>Добавить артиста</span></a></li>
                    <li><a href="/rate-song.php"><span>Оценить трек</span></a></li>
                </ul>
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
</html>

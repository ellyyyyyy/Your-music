<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    include 'connect.php';
    $db = new mysqli($host, $user, $passw, $db_name);
    // Получение данных из формы
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Запрос на проверку данных пользователя
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = $db->query($query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Сохранение информации о пользователе в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Перенаправление на защищенную страницу
        header('Location: ../index.php');
        exit();
    } else {
        // Ошибка аутентификации
        $error_message = 'Неверное имя пользователя или пароль';
    }

    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Страница входа</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="wrap">
        <div class="error-message">
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
        </div>
       <div class="login-wrap">
            <form method="POST" action="login.php">
                <input type="text" id="username" name="username" required>
                <input type="password" id="password" name="password" required>
                <button type="submit">Войти</button>
            </form>
        </div>
    </div> 
</body>
</html>
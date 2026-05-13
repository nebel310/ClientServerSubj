<?php
session_start();

// Массив пользователей
$users = [
    'admin' => [
        'id' => 1,
        'password_hash' => '$2y$12$m1Ddz4guyiOVXVf56uVV6.5Jpi46M/EINk8J29UX7HoJEQDbi0hTy',
    ],
    'user' => [
        'id' => 2,
        'password_hash' => '$2y$12$Sxb5EHUkiHNeLTHjB8qWf.6FDSB9WUwgKqx7w.7pcuorr9SW3Wpga',
    ],
];

// Функция записи лога
function writeLog(string $login, string $action): void
{
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $file = $dir . '/auth.log';
    $time = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $line = "$time | ip=$ip | login=$login | action=$action" . PHP_EOL;
    file_put_contents($file, $line, FILE_APPEND);
}

// Обработка выхода
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $login = $_SESSION['login'] ?? 'unknown';
    writeLog($login, 'LOGOUT');
    session_destroy();
    header('Location: index.php');
    exit;
}

// Обработка POST-запроса (попытка входа)
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = 'Заполните все поля.';
    } elseif (!isset($users[$login])) {
        writeLog($login, 'FAIL_LOGIN');
        $error = 'Неверный логин или пароль.';
    } else {
        if (password_verify($password, $users[$login]['password_hash'])) {
            $_SESSION['user_id'] = $users[$login]['id'];
            $_SESSION['login'] = $login;
            writeLog($login, 'SUCCESS_LOGIN');
            header('Location: index.php');
            exit;
        } else {
            writeLog($login, 'FAIL_LOGIN');
            $error = 'Неверный логин или пароль.';
        }
    }
}

// Проверка авторизации
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
<?php if ($isLoggedIn): ?>
    <h1>Добро пожаловать, <?= htmlspecialchars($_SESSION['login']) ?>!</h1>
    <p>Вы вошли в систему.</p>
    <a href="index.php?action=logout">Выйти</a>
<?php else: ?>
    <h1>Форма авторизации</h1>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <div>
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Войти</button>
    </form>
<?php endif; ?>
</body>
</html>
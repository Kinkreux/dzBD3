<?php

//если пользователь уже вошел, отправляем его на список задач
if (!empty($_COOKIE['user_id'])) {
    header('Location: ./index.php');
};

//создаем подключение к базе данных
$dataBaseTasks = new PDO('mysql:dbname=mpustovit;host=localhost;charset=UTF8', 'mpustovit', 'neto1714');

//дамп создания базы. Поскольку база создана, он закомментирован
/*try {
    $dataBaseTasks->exec(
        "CREATE TABLE `users` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `login` varchar(20) NOT NULL,
                          `pass_hash` varchar(150) NOT NULL,
                          `date_added` datetime NOT NULL,
                          PRIMARY KEY(`id`)
                          ) ENGINE = InnoDB DEFAULT CHARSET = utf8;");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}*/

if (isset($_GET['reg'])) {
    if ($_GET['reg'] = 'yes') {
        $registration = true;
    }
} else {
    $registration = false;
}
?>

<html>
<header>
    <title>Вход или регистрация</title>
    <style>
        h1, h2 {
            font-size: 18px;
        }

        body {
            max-width: 550px;
            margin-left: 15%;
        }

        td {
            padding: 10px;
        }
    </style>
</header>
<body>
<h1>Вход или регистрация</h1>
<?php if ($registration) :
    ?>
    <h2>Регистрация</h2>
    <form method="post">
        <p>Придумайте логин (только латинские буквы и цифры):</p>
        <input type=text name="new_login">
        <p>Придумайте пароль (только латинские буквы и цифры):</p>
        <input type=password name="new_password">
        <input type="submit" value="Зарегистрироваться">
    </form>
    <a href="auth.php">Вход</a>
    <?php require_once __DIR__ . '/reg_function.php';
else:
    ?>
    <h2>Вход</h2>
    <form method="post">
        <p>Логин:</p>
        <input type=text name="login">
        <p>Ваш пароль:</p>
        <input type=password name="password">
        <input type="submit" value="Войти">
    </form>
    <a href="?reg=yes">Регистрация</a>
    <?php
    require_once __DIR__ . '/auth_function.php';
endif;
?>
</body>
</html>
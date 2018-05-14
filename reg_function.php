<?php
if (array_key_exists('new_login', $_POST)) {
    if (empty($_POST['new_login']) OR empty($_POST['new_password'])) {
        echo '<strong>Введите логин и пароль.</strong>';
    } else {
        $gotNewLogin = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['new_login']);
        $searchNewLogin = $dataBaseTasks->query("SELECT login FROM users where login='" . $gotNewLogin . "'");
        $searchNewLogin = $searchNewLogin->fetch();
        if ($searchNewLogin['login'] == $gotNewLogin) {
            echo '<strong>Уже есть пользователь с логином ' . $gotNewLogin . '. Попробуйте придумать другой логин.</strong>';
        } else {
            $gotNewPassword = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['new_password']);
            if (!empty($gotNewPassword)) {
                $pass_hashNew = MD5($gotNewPassword);
            } else {
                echo '<strong>Введите корректный пароль.</strong>';
            }
            $time = new DateTime('now');
            $time = date_format($time, 'Y-m-d H:i:s');
            $addUser = $dataBaseTasks->exec("INSERT INTO users (login, pass_hash, date_added) VALUES('" . $gotNewLogin . "','" . $pass_hashNew . "', '" . $time . "' )");
            $catchNewUser = $dataBaseTasks->query("SELECT login, pass_hash FROM users WHERE login='" . $gotNewLogin . "'");
            $catchNewUser = $catchNewUser->fetch();
            if ($catchNewUser['login'] == $gotNewLogin && $catchNewUser['pass_hash'] == $pass_hashNew) {
                echo '<strong>Вы успешно зарегистрировались!</strong> <a href="auth.php">Войти</a>';
            } else {
                echo '<strong>Ошибка регистрации. Пожалуйста, попробуйте еще раз.</strong>';
            }
        }
    }
}

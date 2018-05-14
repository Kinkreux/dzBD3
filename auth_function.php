<?php
if (array_key_exists('login', $_POST)) {
    if (empty($_POST['login']) OR empty($_POST['password'])) {
        echo '<strong>Введите логин и пароль.</strong>';
    } else {
        $gotLogin = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['login']);
        $searchLogin = $dataBaseTasks->query("SELECT login FROM users where login='" . $gotLogin . "'");
        $searchLogin = $searchLogin->fetch();
        if ($searchLogin['login'] !== $gotLogin) {
            echo '<strong>Не найден пользователь с логином ' . $gotLogin . ' . Вы можете зарегистрироваться.</strong>';
        } else {
            if ($searchLogin['login'] == $gotLogin) {
                $gotPassword = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['password']);
                $pass_hash = MD5($gotPassword);
                $searchPassword = $dataBaseTasks->query("SELECT pass_hash FROM users WHERE login='" . $gotLogin . "'");
                $searchPassword = $searchPassword->fetch();
                if ($searchPassword['pass_hash'] == $pass_hash) {
                    session_start();
                    $searchId = $dataBaseTasks->query("SELECT id FROM users WHERE login='" . $gotLogin . "'");
                    $searchId = $searchId->fetch();
                    setcookie('user_id', $searchId['id']);
                    header('Location: ./index.php');
                } else {
                    echo '<strong>Неверный логин или пароль.</strong>';
                }
            }
        }
    }
}
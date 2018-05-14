<?php
require_once __DIR__ . '/functions.php';

if (empty($_COOKIE['user_id'])) {
    header('Location: ./auth.php');
}

//создаем подключение к базе данных
$dataBaseTasks = new PDO('mysql:dbname=mpustovit;host=localhost;charset=UTF8', 'mpustovit', 'neto1714');

//дамп создания базы; она уже 1 раз создана, так что закомментирована
/*try {
    $test = $dataBaseTasks->exec(
        "DROP TABLE IF EXISTS `tasks`;
                          CREATE TABLE `tasks` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `description` text NOT NULL,
                          `is_done` tinyint(4) NOT NULL DEFAULT '0',
                          `date_added` datetime NOT NULL,
                          PRIMARY KEY(`id`)
                          ) ENGINE = InnoDB DEFAULT CHARSET = utf8;");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}*/

$author = $_COOKIE['user_id'];


if (array_key_exists('action', $_GET)) {
    $actionArray = newTaskAction();
    $id = $actionArray['id'];
    $action = $actionArray['action'];
    if ($action = 'doTask') {
        doTask($id, $dataBaseTasks);
        header('Location: index.php');
    } elseif ($action = 'deleteTask') {
        deleteTask($id, $dataBaseTasks);
        header('Location: index.php');
    } elseif ($action = 'assignTask') {
        assignTask($user_assign_id, $id, $dataBaseTasks);
        header('Location: index.php');
    } else {
        echo 'Действие не определено.';
    }
}

if (array_key_exists('description', $_POST)) {
    $description = newTaskDescription();
    newTask($description, $dataBaseTasks, $author);
}

//создаем массив задач
$sql = "SELECT id, user_id, user_assigned_id, description, is_done, date_added FROM tasks WHERE user_id=$author GROUP BY user_assigned_id";
$tasksArray = $dataBaseTasks->query($sql);
$tasksArray = $tasksArray->fetchAll();
?>

<html>
<header>
    <title>Мое домашнее задание по лекции 4.2 «Запросы SELECT, INSERT, UPDATE и DELETE»</title>
    <style>
        h1, h2 {
            font-size: 18px;
        }

        body {
            max-width: 700px;
            margin-left: 15%;
        }

        td {
            padding: 10px;
        }
    </style>
</header>
<body>
<h1>Мое домашнее задание по лекции 4.2 «Запросы SELECT, INSERT, UPDATE и DELETE»</h1>
<h2>Мои задачи</h2>
<form method="post">
    <p>Новая задача:</p>
    <input type=text name="description">
    <input type="submit" value="Создать новую задачу">
</form>
<h3>Созданные мной задачи:</h3>
<table>
    <thead>
    <th>Задача</th>
    <th>Автор</th>
    <th>Ответственный</th>
    <th>Сделано</th>
    <th>Дата создания</th>
    <th>Действия:</th>
    </thead>
    <tbody>
    <?php

    if($tasksArray == 0) {
        echo 'Нет созданных мной задач.';
    } else {
    //читаем и выводим задачи построчно
    foreach ($tasksArray as $task) : ?>
        <tr>
            <?php //id
            $id = $task['id'] ?>
            <td><?php //Задача
                echo $task['description'] ?></td>
            <td><?php //Автор я
                $user =  $task['user_id'];
                $sqlMe = "SELECT login FROM users WHERE id='".$user."'";
                $userLogin = $dataBaseTasks->query($sqlMe);
                $userLogin = $userLogin->fetch();
                echo $userLogin['login']?></td>
            <td><?php //Ответственный
                $user_assigned =  $task['user_assigned_id'];
                $sqlAssigned = "SELECT login FROM users WHERE id='".$user_assigned."'";
                $userAssignedLogin = $dataBaseTasks->query($sqlMe);
                $userAssignedLogin = $userAssignedLogin->fetch();
                echo $userAssignedLogin['login'] ?></td>
            <td><?php //статус: сделано / не сделано
                if ($task['is_done']) {
                    echo 'Да';
                } else {
                    echo 'Нет';
                };
                ?></td>
            <td><?php //дата создания задачи
                echo date('d.m.Y H:i', strtotime($task['date_added'])); ?></td>
            <td>
                <form method="get">
                    <?php //делегировать задачу
                    $sqlUsers = "SELECT users.id as user_id, users.login as user_login, tasks.id as task_id FROM users, tasks WHERE users.id not like  '".$author."' and users.id > 0 GROUP BY users.id";
                    $userList = $dataBaseTasks->query($sqlUsers);
                    $userList = $userList->fetchALL();
                    $authorLogin = $dataBaseTasks->query("SELECT login as author_login FROM users where id = '".$author."'");
                    $authorLogin = $authorLogin->fetch();
                    ?>
                    <p>Делегировать:</p>
                    <select onchange="document.location=this.options[this.selectedIndex].value">
                        <option><?php echo $authorLogin['author_login'] ?></option>
                    <?php foreach($userList as $userTask)
                    {
                        echo '<option value="index.php?id='.$userTask['task_id'].'&action=assignTask&user_assign_id='.$userTask['user_id'].'">'.$userTask['user_login'].'</option>';
                    } ?>
                    </select>
                </form>
            <td><?php //выполнить задачу
                    echo '<a href="?id='.$id.'&action=doTask">'.'Выполнить</a>'?></td>
            <td><?php //удалить задачу
                    echo '<a href="?id=' . $id . '&action=deleteTask">'.'Удалить</a>' ?></td>
        </tr>
    <?php endforeach; } ?>
    </tbody>
</table>
<h3>Задачи, порученные мне:</h3>
<a href="logout.php">Выход</a>
<h2>Код приложения</h2>
<a href="https://github.com/Kinkreux/dzBD2" target="_blank">Открыть в новом окне репозиторий на Github</a>
</body>
</html>
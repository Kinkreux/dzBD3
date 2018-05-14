<?php
//Добавить задачу в базу
function newTask($description, $dataBaseTasks, $author)
{
    $time = new DateTime('now');
    $time = date_format($time, 'Y-m-d H:i:s');
    $dataBaseTasks->exec("INSERT INTO tasks(user_id, User_assigned_id, description, date_added) values ('".$author."', '".$author."', '".$description."', '".$time."')");
}

//Делегировать задачу
function assignTask($user_assign_id, $id, $dataBaseTasks)
{
    $dataBaseTasks->exec("UPDATE tasks(user_assigned_id) SET VALUE('".$user_assign_id."') WHERE id = '".$id."'");
}

//Выполнить задачу
function doTask($id, $dataBaseTasks)
{
        $dataBaseTasks->exec("UPDATE tasks(is_done) SET VALUE(1) WHERE id = '".$id."'");
}

//Удалить задачу
function deleteTask($id, $dataBaseTasks)
{
    $dataBaseTasks->exec("DELETE * FROM tasks WHERE id='".$id."'");
}


//обработка форм
function newTaskDescription()
{
    if (isset($_POST)) {
        if (array_key_exists('description', $_POST)) {
            $description = htmlspecialchars($_POST['description']);
            return $description;
        }
    }
}

function newTaskAction()
{
    if (isset($_GET)) {
        if (!array_key_exists('action', $_GET)) {
            echo 'Ошибка: действие неопределено. Не получилось выполнить или удалить задачу.';
        } elseif(!array_key_exists('id', $_GET)) {
            echo 'Ошибка: действие неопределено. Не получилось выполнить или удалить задачу.';
        }else {
            foreach ($_GET as $get)
            (
                htmlspecialchars($get)
            );
            $actionArray = $_GET;
            return $actionArray;
        }
    }
}
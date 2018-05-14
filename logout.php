<?php

session_destroy();
setcookie('user_id', '');
header('Location: ./auth.php');
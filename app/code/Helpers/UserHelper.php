<?php
require '../../../vendor/autoload.php';

use \Controllers\UserController;

if ($_COOKIE['user_role'] == 0 && isset($_POST['login'])) {

    header('Location: ../../../index.php');
}


if ($_COOKIE['user_role'] == 0 && isset($_POST['register'])) {

    $username = $_POST['reg_username'];
    $password = $_POST['reg_psw'];
    $email = $_POST['reg_email'];
    $phonenumber = $_POST['reg_phone'];

    UserController::register($username, $password, $email, $phonenumber);

    header('Location: ../../../index.php');
}
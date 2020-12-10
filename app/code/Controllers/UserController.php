<?php

namespace Controllers;

use Models\User;

class UserController
{
    public function login() {
        if (isset($_POST['login'])) {
            $_SESSION['user'] = User::login();
            header('location: /');
            exit();
        }
    }

    public function register() {
        if (isset($_POST['register'])) {
            User::register();
        }
    }

    public function logout() {
        User::logout();
    }
}
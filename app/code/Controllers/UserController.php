<?php

namespace Controllers;

use Models\User;

class UserController
{
    public function login() {

        $response = User::login();

        if ($response instanceof User) {
            $_SESSION['user'] = User::login();
        } elseif (gettype($response) === 'string') {
            echo $response;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function register() {
        $response = User::register();

        if (gettype($response) == 'string') {
            echo $response;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function logout() {
        User::logout();
    }
}
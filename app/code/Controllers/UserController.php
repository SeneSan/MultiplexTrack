<?php

namespace Controllers;

use Models\User;

class UserController extends Controller
{
    public function login() {

        /** @var User $userModel */
        $userModel = $this->model('User');
        $response = $userModel->login();

        if ($response instanceof User) {
            $_SESSION['user'] = $userModel->login();
        } elseif (gettype($response) === 'string') {
            echo $response;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function register() {
        /** @var User $userModel */
        $userModel = $this->model('User');
        $response = $userModel->register();

        if (gettype($response) == 'string') {
            echo $response;
        } else {
            echo OOPS_MESSAGE;
        }
    }

    public function logout() {
        /** @var User $userModel */
        $userModel = $this->model('User');
        $userModel->logout();
    }
}
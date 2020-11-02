<?php

namespace Controllers;

class UserController
{
    public function login() {
        //TODO
    }

    public static function register($username, $email, $password, $phonenumber) {
        $pdo = DatabaseController::getConnection();

        $passHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, email, phonenumber, type) VALUES (?, ?, ?, ?, ?)";
        try {
            $pdo->prepare($sql)->execute([$username, $passHash, $email, $phonenumber, 1]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
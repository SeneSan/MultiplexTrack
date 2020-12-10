<?php

namespace Models;

class User
{
    CONST LOG_FILE = 'user';

    public const NOUSER = 0;
    public const ADMIN = 1;
    public const USER = 2;

    private $userId;
    private $username;
    private $email;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
    private $password;
    private $phonenumber;
    private $type;

    public function __construct($userId, $username, $email, $password, $phonenumber, $type)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->phonenumber = $phonenumber;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * @param mixed $phonenumber
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;
    }

    public static function register() {

        $username = $_POST['reg_username'];
        $password = $_POST['reg_psw'];
        $email = $_POST['reg_email'];
        $phonenumber = $_POST['reg_phone'];

        $pdo = Database::getConnection();

        $passHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, email, phonenumber, type) VALUES (?, ?, ?, ?, ?)";
        try {
            $pdo->prepare($sql)->execute([$username, $passHash, $email, $phonenumber, 1]);
        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }

        header('Location: /');
    }

    public static function login() {

        $username = $_POST['username'];
        $password = $_POST['psw'];

        $pdo = Database::getConnection();
        $sql = "SELECT * FROM users WHERE username = ?";
        try {
            $query = $pdo->prepare($sql);
            $query->execute([$username]);
            $result = $query->fetch();

            if ($result && password_verify($password, $result['password'])) {
                $userId = $result['id'];
                $username = $result['username'];
                $password = $result['password'];
                $email = $result['email'];
                $phonenumber = $result['phonenumber'];
                $type = $result['type'];

                return new User($userId, $username, $password, $email, $phonenumber, $type);

            } elseif ($username === $result['username']) {
                echo 'Incorrect credentials!';
            } else {
                echo 'User does not exist!';
            }

        } catch (\PDOException $e) {
            Logger::logError($e, self::LOG_FILE);
        } catch (\Error $err) {
            Logger::logError($err, self::LOG_FILE);
        }

        header('Location: /');
    }

    public static function logout() {
        unset($_SESSION['user']);
        header('location: /');
        exit();
    }
}
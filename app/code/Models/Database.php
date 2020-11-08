<?php


namespace Models;

use PDO;

class Database
{
    public static function getConnection() {

        $configurationFile = simplexml_load_file(__ROOT__ . 'local.xml');

        $dbHost = $configurationFile->db_host;
        $dbName = $configurationFile->db_name;
        $dbUser = $configurationFile->db_user;
        $dbPass = $configurationFile->db_pass;
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset";

        try {
            return new PDO($dsn, $dbUser, $dbPass);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function select($table , $params = []) {
        $pdo = self::getConnection();


    }

    public static function insert ($table, $columns, $values) {
        $pdo = self::getConnection();


    }
}
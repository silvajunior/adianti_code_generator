<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Connection
{

    private static $conn;

    public function connect()
    {

        $file = file_get_contents(__DIR__ . '/config.json');
        $configJson = json_decode($file, true);

        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $configJson['host'],
            $configJson['port'],
            $configJson['database'],
            $configJson['user'],
            $configJson['password']);

        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;

    }

    public static function get()
    {

        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;

    }

    protected function __construct()
    {

    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

}

?>
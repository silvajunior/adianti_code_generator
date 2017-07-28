<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Connection.class.php');

class TableReader
{

    public static function getInfo( $tableName )
    {

        try {

            $pdo = Connection::get()->connect();

            $stmt = $pdo->query("SELECT * FROM information_schema.columns WHERE table_name = '" . $tableName . "';");

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

                $data[] = [
                    'column_name' => $row['column_name'],
                    'data_type' => $row['data_type'],
                    'is_nullable' => $row['is_nullable'],
                    'length' => $row['character_maximum_length']
                ];

            }

            return $data;

        } catch (\PDOException $e) {

            echo $e->getMessage();

        }

    }

}

?>
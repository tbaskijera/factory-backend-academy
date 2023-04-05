<?php

namespace App;

use PDO;
use Exception;
use Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Connection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $db_name = $_ENV['DB_NAME'];
        $db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];

        try {
            $this->connection = new PDO('mysql:host=mysql;dbname='.$db_name, $db_user, $db_pass);
        } catch (\Throwable $t) {
            throw $t;
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function fetchAssoc()
    {
    }

    public function fetchAssocAll()
    {
    }

    public function insert($table, $object)
    {
        if (!isset($object) || !is_array($object)) {
            return new Exception("Wrong format");
        }

        if (Utils::is_associative_array($object)) {
            $this->singleInsert($table, $object);
        } else {
            $this->groupInsert($table, $object);
        }
    }

    public function update($table, $updateValues, $condition)
    {
        $updateKeys = array_keys($updateValues);
        $setClause = implode(', ', array_map(function ($key) {
            return "{$key} = :{$key}";
        }, $updateKeys));

        $conditionKeys = array_keys($condition);
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = :{$key}";
        }, $conditionKeys));

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $stmt = $this->connection->prepare($sql);

        foreach ($updateValues as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        foreach ($condition as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
    }


    private function singleInsert($table, $object)
    {
        [$objectKeys, $objectValues] = [array_keys($object), array_values($object)];

        $columnNames = implode(', ', $objectKeys);
        $placeholders = ':' . implode(', :', $objectKeys);

        $sql = "INSERT INTO $table ($columnNames) VALUES ($placeholders)";

        $stmt = $this->connection->prepare($sql);

        foreach ($objectKeys as $i => $key) {
            $stmt->bindValue(':' . $key, $objectValues[$i]);
        }

        $stmt->execute();
    }


    public function groupInsert($table, $objects)
    {
        $objectKeys = array_keys($objects[0]);

        $columnNames = implode(', ', $objectKeys);

        $valueRows = array_map(function ($object) {
            return '(' . implode(', ', array_fill(0, count($object), '?')) . ')';
        }, $objects);

        $values = array_merge(...array_map('array_values', $objects));

        $placeholders = implode(', ', $valueRows);

        $sql = "INSERT INTO $table ($columnNames) VALUES $placeholders";
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($values);
    }
}

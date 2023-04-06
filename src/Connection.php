<?php

namespace App;

use PDO;
use Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
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

    public static function getInstance(): Connection
    {
        if (self::$instance == null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function fetchAssoc(string $query, array $values): ?array
    {
        $statement = $this->connection->prepare($query);

        if (Utils::is_associative_array($values)) {
            foreach ($values as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        } else {
            foreach ($values as $i => $value) {
                $statement->bindValue($i + 1, $value);
            }
        }

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return ($result !== false) ? $result : null;
    }

    public function fetchAssocAll(string $query, array $values): array
    {
        $statement = $this->connection->prepare($query);

        if (Utils::is_associative_array($values)) {
            foreach ($values as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
        } else {
            $count = count($values);
            for ($i = 0; $i < $count; $i++) {
                $statement->bindValue($i + 1, $values[$i]);
            }
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $object): void
    {
        if (!isset($object) || !is_array($object)) {
            throw new Exception("Wrong format");
        }

        if (Utils::is_associative_array($object)) {
            $this->singleInsert($table, $object);
        } else {
            $this->groupInsert($table, $object);
        }
    }

    public function update(string $table, array $updateValues, array $condition): void
    {
        $updateKeys = array_keys($updateValues);
        $setClause = implode(', ', array_map(function ($key) {
            return "{$key} = :update_{$key}";
        }, $updateKeys));

        $conditionKeys = array_keys($condition);
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = :condition_{$key}";
        }, $conditionKeys));
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $stmt = $this->connection->prepare($sql);

        foreach ($updateValues as $key => $value) {
            $stmt->bindValue(":update_{$key}", $value);
        }

        foreach ($condition as $key => $value) {
            $stmt->bindValue(":condition_{$key}", $value);
        }

        $stmt->execute();
    }


    private function singleInsert(string $table, array $object): void
    {
        [$objectKeys, $objectValues] = [array_keys($object), array_values($object)];

        $columnNames = implode(', ', $objectKeys);
        $placeholders = ':' . implode(', :', $objectKeys);

        $sql = "INSERT INTO $table ($columnNames) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);

        foreach ($objectKeys as $i => $key) {
            $stmt->bindValue(":{$key}", $objectValues[$i]);
        }

        $stmt->execute();
    }


    public function groupInsert(string $table, array $objects): void
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

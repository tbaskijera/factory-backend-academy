<?php

namespace App\Models;

use App\Connection;

abstract class Model
{
    protected static $connection;
    protected static $tableName;
    protected static $primaryKeyName = 'id';
    public $properties = [];

    public static function __init()
    {
        static::$connection = Connection::getInstance();
        static::$tableName = static::resolveTable();
    }

    public function __get(string $key)
    {
        return $this->properties[$key];
    }

    public function __set(string $key, $value)
    {
        $this->properties[$key] = $value;
    }

    public function save(): void
    {
        static::$connection->insert(static::$tableName, $this->properties);
        $id = static::$connection->getLastInsertId();
        $this->{static::$primaryKeyName} = $id;
    }

    public function update(): void
    {
        $id = $this->{static::$primaryKeyName};
        static::$connection->update(static::$tableName, $this->properties, [static::$primaryKeyName => $id]);
    }

    public function softDelete(): void
    {
        $id = $this->{static::$primaryKeyName};
        static::$connection->update(static::$tableName, ['deleted_at' => $this->properties['deleted_at']], [static::$primaryKeyName => $id]);
    }

    public static function find(int $lookupID): object
    {
        $tableName = static::$tableName;
        $primaryKey = static::$primaryKeyName;
        $query = "SELECT * FROM $tableName WHERE $primaryKey = ?";
        $properties = static::$connection->fetchAssoc($query, [$lookupID]);
        $instance = new static();
        $instance->properties = $properties;
        return $instance;
    }

    public function toArray(): array
    {
        return $this->properties;
    }

    public static function all(): array
    {
        $query = 'SELECT * FROM ' . static::$tableName;
        return static::$connection->fetchAssocAll($query, []);
    }

    protected static function resolveTable(): string
    {
        $className = static::class;
        $classWithoutNamespace = substr($className, strrpos($className, '\\') + 1);
        $tableName = strtolower($classWithoutNamespace);
        $tableName .= 's';
        return $tableName;
    }
}

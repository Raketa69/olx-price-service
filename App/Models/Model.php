<?php

declare(strict_types=1);

namespace App\Models;

use App\Db;
use Exception;

abstract class Model
{
    protected const TABLE = '';
    public int $id;

    abstract public function getModelName();

    public static function findAll(): array
    {
        $db = Db::instance();

        $sql = 'SELECT * FROM ' . static::TABLE;

        return $db->query(
            $sql,
            static::class,
            []
        );
    }

    public function insert(): void
    {
        $props = get_object_vars($this);
        $columns = [];
        $binds = [];
        $data = [];

        foreach ($props as $name => $value) {
            $columns[] = $name;
            $binds[] = gettype($value) === "string" ? "\"$value\"" : $value;
            $data["$name"] = $value;
        }

        $sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $binds) . ')';

        $db = DB::instance();

        $db->execute($sql, $this->getModelName());
        $this->id = (int) $db->lastId();
    }

    public function update()
    {
        $props = get_object_vars($this);
        $columns = [];
        $data = [];

        foreach ($props as $name => $value) {
            // Не обновляем поле id
            if ($name !== 'id') {
                $columns[] = $name . ' = ' . (gettype($value) === "string" ? "\"$value\"" : $value);
            }
            $data[$name] = $value;
        }

        $sql = 'UPDATE ' . static::TABLE . ' SET ' . implode(',', $columns) . ' WHERE id = ' . $this->id;

        $db = DB::instance();

        return $db->execute($sql, $this->getModelName());
    }

    public function delete(): void
    {
        if (!isset($this->id)) {
            throw new Exception("Cannot delete record without ID");
        }

        $id = (int) $this->id;
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE id = ' . $id;

        $db = DB::instance();
        $db->execute($sql, $this->getModelName());
    }
}

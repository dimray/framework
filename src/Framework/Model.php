<?php

declare(strict_types=1);

namespace Framework;

use App\Database;
use PDO;

abstract class Model
{
    public function __construct(protected Database $database) {}

    protected $table;

    protected function getTable()
    {
        if ($this->table !== null) {

            return $this->table;
        }

        $parts = explode("\\", $this::class);

        return strtolower(array_pop($parts));
    }

    public function getLastId()
    {
        $pdo = $this->database->getConnection();

        return $pdo->lastInsertId();
    }

    public function findAll()
    {

        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()}";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {

        $pdo = $this->database->getConnection();

        $sql = "select * from {$this->getTable()} where id = :id";


        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function insert($data)
    {

        $pdo = $this->database->getConnection();

        $columns = implode(", ", array_keys($data));

        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "insert into {$this->getTable()} ($columns) values ($placeholders)";

        $stmt = $pdo->prepare($sql);

        $i = 1;

        foreach ($data as $value) {
            $type = match (getType($value)) {
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                "boolean" => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);
        }

        return $stmt->execute();
    }

    public function update($data)
    {

        $id = $data['id'];

        unset($data['id']);

        $columns = array_keys($data);

        $placeholders = implode(" = ?, ", $columns) . " = ?";

        $pdo = $this->database->getConnection();

        $sql = "update {$this->getTable()} set $placeholders where id = ?";

        $stmt = $pdo->prepare($sql);

        $i = 1;

        foreach ($data as $value) {
            $type = match (getType($value)) {
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                "boolean" => PDO::PARAM_BOOL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);
        }

        $stmt->bindValue($i, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {

        $pdo = $this->database->getConnection();

        $sql = "delete from {$this->getTable()} where id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

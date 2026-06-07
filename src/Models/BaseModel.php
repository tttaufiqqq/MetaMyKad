<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

abstract class BaseModel
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function find(int $id): array|false
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :id LIMIT 1', $this->table, $this->primaryKey);
        $statement = Database::connection()->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function findAll(): array
    {
        $sql = sprintf('SELECT * FROM %s ORDER BY %s DESC', $this->table, $this->primaryKey);
        return Database::connection()->query($sql)->fetchAll();
    }

    public function query(string $sql, array $params = []): array
    {
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function save(array $data): bool
    {
        if (isset($data[$this->primaryKey]) && $data[$this->primaryKey] !== null) {
            $columns = array_filter(array_keys($data), fn (string $key): bool => $key !== $this->primaryKey);
            $assignments = implode(', ', array_map(fn (string $column): string => $column . ' = :' . $column, $columns));
            $sql = sprintf('UPDATE %s SET %s WHERE %s = :%s', $this->table, $assignments, $this->primaryKey, $this->primaryKey);
        } else {
            $columns = array_keys($data);
            $columnList = implode(', ', $columns);
            $placeholders = implode(', ', array_map(fn (string $column): string => ':' . $column, $columns));
            $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, $columnList, $placeholders);
        }

        $statement = Database::connection()->prepare($sql);
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $sql = sprintf('DELETE FROM %s WHERE %s = :id', $this->table, $this->primaryKey);
        $statement = Database::connection()->prepare($sql);
        return $statement->execute(['id' => $id]);
    }

    public function callProcedure(string $procedure, array $params = []): array
    {
        $placeholders = implode(', ', array_fill(0, count($params), '?'));
        $sql = $placeholders === ''
            ? sprintf('CALL %s()', $procedure)
            : sprintf('CALL %s(%s)', $procedure, $placeholders);

        $statement = Database::connection()->prepare($sql);
        $statement->execute(array_values($params));

        $results = $statement->fetchAll();

        while ($statement->nextRowset()) {
            // Clear any additional result sets from MySQL procedures.
        }

        $statement->closeCursor();

        return $results;
    }
}

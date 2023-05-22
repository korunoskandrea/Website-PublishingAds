<?php

class Query {
    private string $table;
    private PDO $connection;
    public function __construct(string $table, PDO $connection) {
        $this->table = $table;
        $this->connection = $connection;
    }

    public function selectAll(?string $orderByField = null): PDOStatement {
        $sqlString = "SELECT * FROM ".$this->table;
        if ($orderByField) {
            $sqlString .= " ORDER BY ".$orderByField." DESC";
        }
        return $this->connection->prepare($sqlString);
    }
    public function selectWhereUsingAnd(array $whereFields, array $values, ?string $orderByField = null): PDOStatement {
        if (count($whereFields) !== count($values)) return $this->selectAll();

        $sqlString = "SELECT * FROM ".$this->table." WHERE ";

        foreach ($whereFields as $index => $field) {
            $sqlString .= $field. " = :".$field;
            if ($index < count($whereFields) - 1) {
                $sqlString .= " AND ";
            }
        }
        if ($orderByField) {
            $sqlString .= " ORDER BY ".$orderByField." DESC";
        }
        $statement = $this->connection->prepare($sqlString);
        foreach ($whereFields as $index => $field) {

            $statement->bindParam(":".$field, $values[$index]);
        }
        return $statement;
    }

    public function selectWhereUsingOr(array $whereFields, array $values, ?string $orderByField = null): PDOStatement {
        if (count($whereFields) !== count($values)) return $this->selectAll();

        $sqlString = "SELECT * FROM ".$this->table." WHERE ";
        foreach ($whereFields as $index => $field) {
            $sqlString .= $field. " = :".$field;
            if ($index < count($whereFields) - 1) {
                $sqlString .= " OR ";
            }
        }
        if ($orderByField) {
            $sqlString .= " ORDER BY ".$orderByField." DESC";
        }
        $statement = $this->connection->prepare($sqlString);
        foreach ($whereFields as $index => $field) {
            $statement->bindParam(":".$field, $values[$index]);
        }
        return $statement;
    }

    public function insert(array $insertFields, array $values): ?PDOStatement {
        if (count($insertFields) !== count($values)) return null;

        $sqlString = "INSERT INTO ".$this->table." (";
        foreach ($insertFields as $index => $field) {
            $sqlString .= $field;
            if ($index < count($insertFields) - 1) {

                $sqlString .= ", ";
            }
        }
        $sqlString .= ") VALUES (";
        foreach ($insertFields as $index => $field) {
            $sqlString .= ":".$field;
            if ($index < count($insertFields) - 1) {
                $sqlString .= ", ";
            }
        }
        $sqlString .= ")";
        $statement = $this->connection->prepare($sqlString);
        foreach ($insertFields as $index => $field) {

            $statement->bindParam(":".$field, $values[$index]);
        }
        return $statement;
    }
}
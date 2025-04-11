<?php

class Database {
    private $pdo;

    public function __construct($path) {
        $this->pdo = new PDO("sqlite:" . $path);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function Execute($sql) {
        echo "[debug] SQL: $sql\n";
        return $this->pdo->exec($sql);
    }

    public function Fetch($sql) {
        $stmt = $this->pdo->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function Create($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map( function ($v) { return "'$v'"; }, array_values($data)));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
       
        return $this->Execute($sql);
    }

    public function Read($table, $id) {
        $sql = "SELECT * FROM $table WHERE id = {$id}";
        return $this->Fetch( $sql)[0] ?? [];
    }

    public function Update($table, $id, $data) {
        $columns = implode(", ",array_map( function ($k, $v) { return "{$k}='{$v}'"; }, array_keys($data), array_values($data)));
        $sql = "UPDATE $table SET $columns WHERE id = {$id}";
        return $this->Execute($sql);
    }

    public function Delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = {$id}";
        return $this->Execute($sql);
    }

    public function Count($table) {
        $sql = "SELECT COUNT(*) as cnt FROM $table";
        $row = $this->Fetch($sql);
        return $row[0]['cnt'] ?? 0;
    }
}

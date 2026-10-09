<?php
namespace Core;

class DatabaseSessionHandler implements \SessionHandlerInterface
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : '';
    }

    public function write(string $id, string $data): bool
    {
        $timestamp = time();
        $stmt = $this->db->prepare("
            INSERT INTO sessions (id, data, timestamp) 
            VALUES (:id, :data, :timestamp)
            ON DUPLICATE KEY UPDATE data = VALUES(data), timestamp = VALUES(timestamp)
        ");
        return $stmt->execute([
            'id' => $id,
            'data' => $data,
            'timestamp' => $timestamp
        ]);
    }

    public function destroy(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function gc(int $max_lifetime): int|false
    {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE timestamp < :time");
        $stmt->execute(['time' => time() - $max_lifetime]);
        return $stmt->rowCount();
    }
}

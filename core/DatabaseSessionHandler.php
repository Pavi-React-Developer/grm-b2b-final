<?php
namespace Core;

class DatabaseSessionHandler implements \SessionHandlerInterface
{
    private string $fallbackPath;

    public function __construct()
    {
        $this->fallbackPath = dirname(__DIR__) . '/storage/sessions';
        if (!is_dir($this->fallbackPath)) {
            @mkdir($this->fallbackPath, 0777, true);
        }
    }

    private function getDb(): ?\PDO
    {
        try {
            return Database::getInstance();
        } catch (\Throwable $e) {
            return null;
        }
    }

    private array $readHashes = [];

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
        try {
            $db = $this->getDb();
            if ($db) {
                $stmt = $db->prepare("SELECT data FROM sessions WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $result = $stmt->fetchColumn();
                if ($result !== false) {
                    $this->readHashes[$id] = md5((string)$result);
                    $this->writeFileFallback($id, (string)$result);
                    return $result;
                }
            }
        } catch (\Throwable $e) {
            // DB temporarily unreachable
        }

        // Fallback to local file cache
        $fallback = $this->readFileFallback($id);
        $this->readHashes[$id] = md5((string)$fallback);
        return $fallback;
    }

    public function write(string $id, string $data): bool
    {
        $timestamp = time();
        // Always write to local fallback cache first for instant safety
        $this->writeFileFallback($id, $data);

        // Optimization: If session data did not change during this request, skip remote network write
        $currentHash = md5($data);
        if (isset($this->readHashes[$id]) && $this->readHashes[$id] === $currentHash) {
            return true;
        }

        try {
            $db = $this->getDb();
            if ($db) {
                $stmt = $db->prepare("
                    INSERT INTO sessions (id, data, timestamp) 
                    VALUES (:id, :data, :timestamp)
                    ON DUPLICATE KEY UPDATE data = VALUES(data), timestamp = VALUES(timestamp)
                ");
                $stmt->execute([
                    'id' => $id,
                    'data' => $data,
                    'timestamp' => $timestamp
                ]);
                $this->readHashes[$id] = $currentHash;
            }
        } catch (\Throwable $e) {
            // DB write failed (e.g. DNS drop), file fallback already saved data
        }

        // Return true so PHP session_write_close doesn't emit a Warning
        return true;
    }

    public function destroy(string $id): bool
    {
        $this->deleteFileFallback($id);
        try {
            $db = $this->getDb();
            if ($db) {
                $stmt = $db->prepare("DELETE FROM sessions WHERE id = :id");
                $stmt->execute(['id' => $id]);
            }
        } catch (\Throwable $e) {
            // Ignore DB error on destroy
        }
        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        try {
            $db = $this->getDb();
            if ($db) {
                $stmt = $db->prepare("DELETE FROM sessions WHERE timestamp < :time");
                $stmt->execute(['time' => time() - $max_lifetime]);
            }
        } catch (\Throwable $e) {
            // Ignore DB error on gc
        }
        return 1;
    }

    private function readFileFallback(string $id): string
    {
        $file = $this->fallbackPath . '/sess_' . preg_replace('/[^a-zA-Z0-9,-]/', '', $id);
        if (is_file($file)) {
            $content = @file_get_contents($file);
            return $content !== false ? $content : '';
        }
        return '';
    }

    private function writeFileFallback(string $id, string $data): void
    {
        $file = $this->fallbackPath . '/sess_' . preg_replace('/[^a-zA-Z0-9,-]/', '', $id);
        @file_put_contents($file, $data, LOCK_EX);
    }

    private function deleteFileFallback(string $id): void
    {
        $file = $this->fallbackPath . '/sess_' . preg_replace('/[^a-zA-Z0-9,-]/', '', $id);
        if (is_file($file)) {
            @unlink($file);
        }
    }
}

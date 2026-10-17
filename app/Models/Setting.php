<?php
namespace App\Models;

use Core\Database;

class Setting
{
    private \PDO $db;
    private static array $memo = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function get(string $key, $default = null, bool $forceRefresh = false)
    {
        if (!$forceRefresh && array_key_exists($key, self::$memo)) {
            return self::$memo[$key];
        }

        $cacheKey = 'app_setting_' . $key;
        if (!$forceRefresh && class_exists('\Core\Cache')) {
            $cached = \Core\Cache::get($cacheKey);
            if ($cached !== null) {
                self::$memo[$key] = $cached;
                return $cached;
            }
        }

        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        $result = $val !== false ? $val : $default;

        self::$memo[$key] = $result;
        if (class_exists('\Core\Cache')) {
            \Core\Cache::set($cacheKey, $result, 3600);
        }

        return $result;
    }

    public function set(string $key, string $value): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()
        ");
        $stmt->execute([$key, $value]);

        self::$memo[$key] = $value;
        if (class_exists('\Core\Cache')) {
            \Core\Cache::delete('app_setting_' . $key);
        }
    }
}

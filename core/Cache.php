<?php
namespace Core;

class Cache
{
    private static string $cacheDir = __DIR__ . '/../scratch/cache';

    public static function init()
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }

    public static function get(string $key, $default = null)
    {
        self::init();
        $file = self::$cacheDir . '/' . md5($key) . '.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            // Check expiry (set to 1 hour for now, or indefinitely if no expiry)
            if (isset($data['expiry']) && $data['expiry'] < time()) {
                unlink($file);
                return $default;
            }
            return $data['content'] ?? $default;
        }
        return $default;
    }

    public static function set(string $key, $content, int $ttl = 3600)
    {
        self::init();
        $file = self::$cacheDir . '/' . md5($key) . '.json';
        $data = [
            'expiry' => time() + $ttl,
            'content' => $content
        ];
        file_put_contents($file, json_encode($data));
    }

    public static function delete(string $key)
    {
        self::init();
        $file = self::$cacheDir . '/' . md5($key) . '.json';
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    public static function clear()
    {
        self::init();
        $files = glob(self::$cacheDir . '/*.json');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
}

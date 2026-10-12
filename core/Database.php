<?php
namespace Core;

class Database
{
    private static ?\PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): \PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        return self::connect();
    }

    public static function reconnect(): \PDO
    {
        self::$instance = null;
        return self::connect();
    }

    private static function connect(): \PDO
    {
        $config = require CONFIG_PATH . '/database.php';
        
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,    // Real prepared statements for security & speed
            \PDO::ATTR_STRINGIFY_FETCHES   => false,   // Return native PHP types (int/float), not strings
            \PDO::ATTR_PERSISTENT          => false,   // Disabled to prevent stale SSL connections
            \PDO::ATTR_TIMEOUT             => 15,      // Connection timeout in seconds
            \PDO::MYSQL_ATTR_INIT_COMMAND  => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        // If TiDB / Cloud Database requires SSL
        if (!empty($config['ssl'])) {
            $options[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            $options[\PDO::MYSQL_ATTR_SSL_CA] = dirname(__DIR__) . '/cacert.pem';
        }

        $maxRetries = 3;
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                self::$instance = new \PDO($dsn, $config['user'], $config['pass'], $options);
                $tz = defined('APP_TIMEZONE') ? APP_TIMEZONE : 'Asia/Kolkata';
                $offset = (new \DateTime('now', new \DateTimeZone($tz)))->format('P');
                self::$instance->exec("SET time_zone = '{$offset}'");
                return self::$instance;
            } catch (\PDOException $e) {
                $lastException = $e;
                if ($attempt < $maxRetries) {
                    usleep(300000); // Wait 300ms before retry
                    continue;
                }
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}

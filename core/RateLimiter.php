<?php
namespace Core;

class RateLimiter
{
    /**
     * Tries to consume a token from the rate limit bucket.
     * Returns true if allowed, false if limit exceeded.
     */
    public static function attempt(string $key, int $maxHits, int $decaySeconds): bool
    {
        $db = Database::getInstance();
        $now = time();
        $expires = $now + $decaySeconds;

        // Clean up expired limits
        $db->exec("DELETE FROM rate_limits WHERE expires_at < $now");

        $stmt = $db->prepare("SELECT hits FROM rate_limits WHERE key_name = :key FOR UPDATE");
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();

        if ($row) {
            if ($row['hits'] >= $maxHits) {
                return false; // Rate limit exceeded
            }
            // Increment
            $db->prepare("UPDATE rate_limits SET hits = hits + 1 WHERE key_name = :key")->execute(['key' => $key]);
            return true;
        } else {
            // Create new record
            $db->prepare("INSERT INTO rate_limits (key_name, hits, expires_at) VALUES (:key, 1, :expires)")
               ->execute(['key' => $key, 'expires' => $expires]);
            return true;
        }
    }
}

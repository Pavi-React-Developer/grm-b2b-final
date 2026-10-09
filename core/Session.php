<?php
namespace Core;

class Session
{
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $handler = new \Core\DatabaseSessionHandler();
            session_set_save_handler($handler, true);
            session_start();
        }
    }

    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function remove(string $key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Set a flash message that only exists for the next request.
     */
    public static function setFlash(string $key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Check if a flash message exists.
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Get a flash message and immediately remove it.
     */
    public static function getFlash(string $key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}

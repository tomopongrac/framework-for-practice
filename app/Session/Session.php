<?php

namespace App\Session;

class Session implements SessionStoreInterface
{

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value = null)
    {
        $_SESSION[$key] = $value;
    }

    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    public function clear(array $keys)
    {
        foreach ($keys as $sessionKey) {
            if ($this->exists($sessionKey)) {
                unset($_SESSION[$sessionKey]);
            }
        }
    }
}

<?php

namespace App\Session;

class Session implements SessionStoreInterface
{

    /**
     * Return session value for given key.
     * If key does not exist return default value if provided.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value = null)
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

<?php

namespace App\Session;

interface SessionStoreInterface
{
    /**
     * Return session value for given key.
     * If key does not exist return default value if provided.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, $default = null);

    public function set(string $key, $value = null);

    public function exists($key);

    public function clear(array $keys);
}

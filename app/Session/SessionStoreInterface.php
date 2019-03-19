<?php

namespace App\Session;

interface SessionStoreInterface
{
    public function get($key, $default = null);

    public function set($key, $value = null);

    public function exists($key);

    public function clear(array $keys);
}

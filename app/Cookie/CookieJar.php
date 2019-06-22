<?php

declare(strict_types=1);

namespace App\Cookie;

class CookieJar
{
    protected $path = '/';

    protected $domain = '';

    protected $secure = false;

    protected $httpOnly = true;

    public function set(string $key, string $value, int $minutes = 60): void
    {
        $expiry = time() + ($minutes * 60);

        setcookie($key, $value, $expiry, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    public function get(string $key, string $default = null): ?string
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]);
    }

    public function clear(string $key): void
    {
        $this->set($key, '', -2628000);
    }

    public function forever(string $key, string $value): void
    {
        $this->set($key, $value, 2628000);
    }
}

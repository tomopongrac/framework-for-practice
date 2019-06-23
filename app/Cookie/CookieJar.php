<?php

declare(strict_types=1);

namespace App\Cookie;

class CookieJar
{
    /**
     * The path on the server in which the cookie will be available on
     *
     * @var string
     */
    protected $path = '/';

    /**
     * The (sub)domain that the cookie is available to
     *
     * @var string
     */
    protected $domain = '';

    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol.
     *
     * @var bool
     */
    protected $httpOnly = true;

    /**
     * Create cookie with $key name and $value for given minutes.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  int  $minutes
     */
    public function set(string $key, string $value, int $minutes = 60): void
    {
        $expiry = time() + ($minutes * 60);

        setcookie($key, $value, $expiry, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    /**
     * Get cookie value for give key.
     * If cokie does not exist return default value if provided.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, string $default = null): ?string
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    /**
     * Check if cookie with given name is exist.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]);
    }

    /**
     * Delete cookie with given name.
     *
     * @param  string  $key
     */
    public function clear(string $key): void
    {
        $this->set($key, '', -2628000);
    }

    public function forever(string $key, string $value): void
    {
        $this->set($key, $value, 2628000);
    }
}

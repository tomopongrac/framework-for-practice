<?php

namespace App\Config;

use App\Config\Loaders\LoaderInterface;

class Config
{
    protected $config = [];

    /**
     * Load config files to the object
     *
     * @param  array  $loaders
     * @return Config
     */
    public function load(array $loaders): Config
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof LoaderInterface) {
                continue;
            }
            $this->config = array_merge($this->config, $loader->parse());
        }

        return $this;
    }

    /**
     * Get array with config values
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Get value for key.
     * If key does not exist return default value if provided.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, ?string $default = null)
    {
        return $this->extractFromConfig($key) ?? $default;
    }

    /**
     * Extract value from config array for given key
     *
     * @param  string  $key
     * @return string|null
     */
    protected function extractFromConfig(string $key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return null;
        }

        return $filtered;
    }

    private function exists(array $config, $key)
    {
        return array_key_exists($key, $config);
    }
}

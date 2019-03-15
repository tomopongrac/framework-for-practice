<?php

namespace App\Config;

use App\Config\Loaders\LoaderInterface;

class Config
{
    protected $config = [];

    public function load(array $loaders)
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof LoaderInterface) {
                continue;
            }
            $this->config = array_merge($this->config, $loader->parse());
        }

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function get($key, $default = null)
    {
        return $this->extractFromConfig($key) ?? $default;
    }

    protected function extractFromConfig($key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return;
        }

        return $filtered;
    }

    private function exists(array $config, $key)
    {
        return array_key_exists($key, $config);
    }
}

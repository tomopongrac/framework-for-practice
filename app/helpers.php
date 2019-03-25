<?php

if (!function_exists('redirect')) {
    function redirect($path)
    {
        return new \Zend\Diactoros\Response\RedirectResponse($path);
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return __DIR__.'/..'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case $value === 'true';
                return true;
            case $value == 'false';
                return false;
            default:
                return $value;
        }
    }
}

if (!function_exists('detectDotEnvFile')) {
    function detectDotEnvFile($environmentEnv = null): string
    {
        if (isset($environmentEnv)) {
            return '.env.'.$environmentEnv;
        }
        if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] === 'Symfony BrowserKit') {
            return '.env.behat';
        }

        return '.env';
    }
}

<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';

try {
    $dotenv = \Dotenv\Dotenv::create(base_path());
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
}

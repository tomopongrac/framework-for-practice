<?php

session_start();

require_once __DIR__.'/../vendor/autoload.php';

try {
    $dotenv = \Dotenv\Dotenv::create(__DIR__.'/..');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
}

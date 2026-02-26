<?php
// Load Config
require_once __DIR__ . '/config/database.php';

// Autoload Core Classes
spl_autoload_register(function($className){
    if (file_exists(__DIR__ . '/lib/' . $className . '.php')) {
        require_once __DIR__ . '/lib/' . $className . '.php';
    } elseif (file_exists(__DIR__ . '/controllers/' . $className . '.php')) {
        require_once __DIR__ . '/controllers/' . $className . '.php';
    } elseif (file_exists(__DIR__ . '/models/' . $className . '.php')) {
        require_once __DIR__ . '/models/' . $className . '.php';
    }
});

// Start session
session_start();

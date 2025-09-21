<?php
// Simple autoloader for admin classes
spl_autoload_register(function ($class) {
    if (strpos($class, 'Admin\\') === 0) {
        $file = __DIR__ . '/classes/' . str_replace('Admin\\', '', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use Admin\Auth;

$auth = new Auth();

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Otherwise redirect to login
header('Location: login.php');
exit;

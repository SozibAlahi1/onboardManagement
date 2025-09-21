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
$auth->logout();

header('Location: login.php');
exit;

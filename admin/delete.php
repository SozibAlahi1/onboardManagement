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
use Admin\SubmissionManager;

$auth = new Auth();
$auth->requireLogin();

$submissionManager = new SubmissionManager();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

if ($submissionManager->deleteSubmission($id)) {
    header('Location: dashboard.php?deleted=1');
} else {
    header('Location: dashboard.php?error=1');
}
exit;

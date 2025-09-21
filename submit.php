<?php
// Ensure errors don't break JSON output
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Minimal PSR-4-less autoload for this small app (no composer needed)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strpos($class, $prefix) === 0) {
        $path = __DIR__ . '/' . str_replace('App\\', 'app/', $class) . '.php';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) require $path;
    }
});

use App\Database;
use App\Repositories;
use App\Services\SubmissionService;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON payload']);
    exit;
}

try {
    $pdo = Database::getConnection();
    $repo = new Repositories($pdo);
    $service = new SubmissionService($pdo, $repo);
    $submissionId = $service->save($payload);
    echo json_encode(['success' => true, 'submission_id' => $submissionId]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Save failed', 'error' => $e->getMessage()]);
}


<?php
namespace Admin;

use PDO;

class Auth
{
    private array $config;
    private PDO $pdo;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/auth.php';
        $this->pdo = Database::getConnection();
        $this->initSession();
    }

    private function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($this->config['session_name']);
            session_set_cookie_params($this->config['session_lifetime']);
            session_start();
        }
    }

    public function login(string $username, string $password): bool
    {
        // Check for brute force protection
        if ($this->isLockedOut()) {
            return false;
        }

        if ($username === $this->config['admin_username'] && 
            password_verify($password, $this->config['admin_password_hash'])) {
            
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();
            
            // Clear any failed attempts
            $this->clearFailedAttempts();
            return true;
        }

        $this->recordFailedAttempt();
        return false;
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
    }

    public function isLoggedIn(): bool
    {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            return false;
        }

        // Check session timeout
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity']) > $this->config['session_lifetime']) {
            $this->logout();
            return false;
        }

        // Update last activity
        $_SESSION['last_activity'] = time();
        return true;
    }

    public function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    private function isLockedOut(): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as attempts 
            FROM admin_login_attempts 
            WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)
        ");
        $stmt->execute([$_SERVER['REMOTE_ADDR'], $this->config['lockout_duration']]);
        $result = $stmt->fetch();
        
        return ($result['attempts'] ?? 0) >= $this->config['max_login_attempts'];
    }

    private function recordFailedAttempt(): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO admin_login_attempts (ip_address, attempt_time) 
            VALUES (?, NOW())
        ");
        $stmt->execute([$_SERVER['REMOTE_ADDR']]);
    }

    private function clearFailedAttempts(): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM admin_login_attempts 
            WHERE ip_address = ?
        ");
        $stmt->execute([$_SERVER['REMOTE_ADDR']]);
    }
}

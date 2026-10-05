<?php
require_once __DIR__ . '/config.php';

function e(?string $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function base_url(string $path = ''): string { return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/'); }
function start_secure_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('gl_admin_session');
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')]);
        session_start();
    }
}
function csrf_token(): string {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): bool {
    start_secure_session();
    return isset($_POST['csrf_token'], $_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
}
function is_admin(): bool { start_secure_session(); return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!is_admin()) { header('Location: login.php'); exit; } }
function set_flash(string $type, string $message): void { start_secure_session(); $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function get_flash(): ?array { start_secure_session(); $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $flash; }

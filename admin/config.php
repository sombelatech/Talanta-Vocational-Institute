<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ============================================================
// TALANTA ADMIN — CONFIGURATION
// ============================================================
// Change the admin password by generating a new bcrypt hash.
// You can do this with any online "PHP password_hash bcrypt
// generator" tool, or ask your developer to run:
//   php -r "echo password_hash('yourNewPassword', PASSWORD_DEFAULT);"
// Then paste the result below, replacing the value in quotes.
// ------------------------------------------------------------

define('ADMIN_PASSWORD_HASH', '$2b$12$GmSq468fwKw/8uddN06NvuzGp8PGXgKAS1XapGFeSu11Yqr2bKdfO');
// Default password is: Talanta2026!
// CHANGE THIS as soon as you log in for the first time.

define('DATA_DIR', __DIR__ . '/../data');

function require_login() {
    if (empty($_SESSION['talanta_admin'])) {
        header('Location: login.php');
        exit;
    }
}

function load_json($file, $default = []) {
    $path = DATA_DIR . '/' . $file;
    if (!file_exists($path)) return $default;
    $content = file_get_contents($path);
    $data = json_decode($content, true);
    return $data === null ? $default : $data;
}

function save_json($file, $data) {
    $path = DATA_DIR . '/' . $file;
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

// Admin password helpers: allow storing the admin password hash in data/admin.json
function get_admin_password_hash() {
    $adminPath = DATA_DIR . '/admin.json';
    if (file_exists($adminPath)) {
        $j = json_decode(file_get_contents($adminPath), true);
        if (!empty($j['password_hash'])) return $j['password_hash'];
    }
    return ADMIN_PASSWORD_HASH;
}

function change_admin_password_hash($newHash) {
    $adminPath = DATA_DIR . '/admin.json';
    $data = ['password_hash' => $newHash, 'updated_at' => time()];
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
    return file_put_contents($adminPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// CSRF helpers
function get_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

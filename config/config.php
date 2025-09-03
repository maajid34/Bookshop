<?php
// ---- DATABASE (from Railway env vars) ----
define('DB_HOST', getenv('DB_HOST') ?: 'mysql.railway.internal');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'railway'); // ama 'bookstore' haddii aad sidaas u dejisay

// ---- APP BASE URL (tani ma aha DB) ----
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $scheme . '://' . $host);

// ---- Uploads ----
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads/covers/');
if (!is_dir(UPLOAD_DIR)) { @mkdir(UPLOAD_DIR, 0775, true); }

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if ($mysqli->connect_error) {
  error_log('DB connect failed: '.$mysqli->connect_error);
  http_response_code(500);
  exit('Database error');
}

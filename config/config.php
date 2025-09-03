<?php
define('DB_HOST', 'hopper.proxy.rlwy.net');   // host only
define('DB_PORT', 11670);                     // port
define('DB_USER', 'root');
define('DB_PASS', 'SvwPNIUVdRzoalQEJJDdylbsSjyWKocn');
define('DB_NAME', 'railway');                 // or "bookstore" if you created it manually

// Base URL of your app (not DB)
define('BASE_URL', 'https://bookshop-app.up.railway.app');

// Uploads
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads/covers/');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

?>

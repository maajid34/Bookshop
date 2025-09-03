<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function csrf_token(){
    if(empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(){
    return '<input type="hidden" name="csrf" value="'.csrf_token().'">';
}
function csrf_check(){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $ok = isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
        if(!$ok){ http_response_code(419); die('Invalid CSRF token'); }
    }
}
?>

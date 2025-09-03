<?php
function e($str){ return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($path){ header('Location: ' . $path); exit; }
function is_post(){ return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function current_url_path(){
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
// Simple paginator
function paginate($total, $perPage, $page){
    $pages = max(1, ceil($total / $perPage));
    return ['pages'=>$pages, 'page'=>max(1, min($page, $pages))];
}
?>

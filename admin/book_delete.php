<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/auth.php'; require_admin(); ?>
<?php
$id = intval($_GET['id'] ?? 0);
if($id){
  $stmt = $pdo->prepare("DELETE FROM books WHERE id=?"); $stmt->execute([$id]);
}
header('Location: /admin/books.php'); exit;

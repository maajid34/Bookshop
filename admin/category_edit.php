<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?"); $stmt->execute([$id]); $c=$stmt->fetch();
if(!$c){ echo 'Category not found'; require __DIR__.'/partials/admin_footer.php'; exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $slug = trim($_POST['slug'] ?? '');
  $stmt = $pdo->prepare("UPDATE categories SET name=?,slug=? WHERE id=?");
  $stmt->execute([$name,$slug,$id]);
  header('Location: /admin/categories.php'); exit;
}
?>
<h1 class="text-xl font-semibold mb-4">Edit Category</h1>
<form method="post" class="bg-white p-4 rounded-xl shadow grid md:grid-cols-2 gap-3">
  <input class="border rounded px-3 py-2" name="name" value="<?= e($c['name']) ?>" required>
  <input class="border rounded px-3 py-2" name="slug" value="<?= e($c['slug']) ?>" required>
  <div class="md:col-span-2">
    <button class="px-4 py-2 rounded bg-primary text-white">Update</button>
    <a href="/admin/categories.php" class="px-4 py-2 rounded border">Cancel</a>
  </div>
</form>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

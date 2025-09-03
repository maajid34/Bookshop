<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $slug = trim($_POST['slug'] ?? '');
  $stmt = $pdo->prepare("INSERT INTO categories (name,slug) VALUES (?,?)");
  $stmt->execute([$name,$slug]);
  header('Location: /admin/categories.php'); exit;
}
?>
<h1 class="text-xl font-semibold mb-4">Create Category</h1>
<form method="post" class="bg-white p-4 rounded-xl shadow grid md:grid-cols-2 gap-3">
  <input class="border rounded px-3 py-2" name="name" placeholder="Name" required>
  <input class="border rounded px-3 py-2" name="slug" placeholder="slug-like-this" required>
  <div class="md:col-span-2">
    <button class="px-4 py-2 rounded bg-primary text-white">Save</button>
    <a href="/admin/categories.php" class="px-4 py-2 rounded border">Cancel</a>
  </div>
</form>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

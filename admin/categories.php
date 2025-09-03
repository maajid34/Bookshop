<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Categories</h1>
  <a href="/admin/category_create.php" class="px-3 py-2 rounded bg-primary text-white">+ New Category</a>
</div>
<div class="bg-white rounded-xl shadow overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="border-b"><th class="text-left py-2 px-3">Name</th><th>Slug</th><th></th></tr></thead>
    <tbody>
    <?php foreach($pdo->query("SELECT * FROM categories ORDER BY name") as $c): ?>
      <tr class="border-b">
        <td class="py-2 px-3"><?= e($c['name']) ?></td>
        <td><?= e($c['slug']) ?></td>
        <td class="text-right px-3 py-2">
          <a class="text-primary mr-3" href="/admin/category_edit.php?id=<?= $c['id'] ?>">Edit</a>
          <a class="text-red-600" href="/admin/category_delete.php?id=<?= $c['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

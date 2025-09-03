<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<h1 class="text-xl font-semibold mb-4">Users</h1>
<div class="bg-white rounded-xl shadow overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="border-b"><th class="text-left py-2 px-3">Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
    <tbody>
      <?php foreach($pdo->query("SELECT id,name,email,role,created_at FROM users ORDER BY id DESC") as $u): ?>
        <tr class="border-b">
          <td class="py-2 px-3"><?= e($u['name']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['role']) ?></td>
          <td><?= e($u['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

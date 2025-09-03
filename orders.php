<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php require_once __DIR__ . '/includes/auth.php'; require_login(); ?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="max-w-5xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-4">My Orders</h1>
  <?php if(isset($_GET['placed'])): ?><div class="mb-3 p-3 bg-green-50 border border-green-200 rounded text-sm">Order placed successfully.</div><?php endif; ?>
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
      <thead><tr class="border-b"><th class="text-left py-2 px-3">#</th><th>Total</th><th>Status</th><th>Method</th><th>Date</th></tr></thead>
      <tbody>
      <?php
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
        $stmt->execute([user()['id']]);
        foreach($stmt as $o):
      ?>
        <tr class="border-b">
          <td class="py-2 px-3"><?= $o['id'] ?></td>
          <td>$<?= number_format($o['total_amount'],2) ?></td>
          <td><?= e($o['status']) ?></td>
          <td><?= e($o['payment_method']) ?></td>
          <td><?= e($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

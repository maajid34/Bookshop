<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.user_id=u.id WHERE o.id=?");
$stmt->execute([$id]);
$o = $stmt->fetch();
if(!$o){ echo 'Order not found'; require __DIR__ . '/partials/admin_footer.php'; exit; }
$items = $pdo->prepare("SELECT oi.*, b.title FROM order_items oi LEFT JOIN books b ON b.id=oi.book_id WHERE order_id=?");
$items->execute([$id]);
$items = $items->fetchAll();
?>
<h1 class="text-xl font-semibold mb-4">Order #<?= $o['id'] ?></h1>
<div class="grid md:grid-cols-2 gap-6">
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="font-semibold mb-2">Summary</div>
    <ul class="text-sm space-y-1">
      <li>User: <?= e($o['email']) ?></li>
      <li>Total: $<?= number_format($o['total_amount'],2) ?></li>
      <li>Status: <?= e($o['status']) ?></li>
      <li>Payment: <?= e($o['payment_method']) ?> <?= e($o['payment_reference']) ?></li>
      <li>Date: <?= e($o['created_at']) ?></li>
    </ul>
  </div>
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="font-semibold mb-2">Items</div>
    <ul class="text-sm space-y-1">
      <?php foreach($items as $it): ?>
        <li class="flex justify-between"><span><?= e($it['title']) ?> × <?= $it['quantity'] ?></span><span>$<?= number_format($it['quantity']*$it['unit_price'],2) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

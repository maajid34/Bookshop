<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
$byDay = $pdo->query("SELECT DATE(created_at) d, SUM(total_amount) s FROM orders GROUP BY DATE(created_at) ORDER BY d DESC LIMIT 30")->fetchAll();
$top = $pdo->query("SELECT b.title, SUM(oi.quantity) q FROM order_items oi LEFT JOIN books b ON b.id=oi.book_id GROUP BY oi.book_id, b.title ORDER BY q DESC LIMIT 10")->fetchAll();
$low = $pdo->query("SELECT title, stock FROM books WHERE stock<=5 ORDER BY stock ASC")->fetchAll();
?>
<h1 class="text-xl font-semibold mb-4">Reports</h1>
<div class="grid md:grid-cols-3 gap-6">
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="font-semibold mb-2">Sales (last 30 days)</div>
    <ul class="text-sm space-y-1">
      <?php foreach($byDay as $r): ?>
        <li class="flex justify-between"><span><?= e($r['d']) ?></span><span>$<?= number_format($r['s'],2) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="font-semibold mb-2">Top 10 books</div>
    <ul class="text-sm space-y-1">
      <?php foreach($top as $r): ?>
        <li class="flex justify-between"><span><?= e($r['title']) ?></span><span><?= e($r['q']) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="font-semibold mb-2">Low stock (≤5)</div>
    <ul class="text-sm space-y-1">
      <?php foreach($low as $r): ?>
        <li class="flex justify-between"><span><?= e($r['title']) ?></span><span><?= e($r['stock']) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

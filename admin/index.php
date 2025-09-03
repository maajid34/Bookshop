<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
$books = $pdo->query("SELECT COUNT(*) c FROM books")->fetch()['c'];
$orders = $pdo->query("SELECT COUNT(*) c FROM orders")->fetch()['c'];
$sales = $pdo->query("SELECT IFNULL(SUM(total_amount),0) s FROM orders")->fetch()['s'];
$users = $pdo->query("SELECT COUNT(*) c FROM users")->fetch()['c'];
?>
<div class="grid md:grid-cols-4 gap-4">
  <!-- Books -->
  <div class="bg-white p-4 rounded-xl shadow flex items-center gap-4">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#043062]/10">
      <i data-lucide="book-open" class="w-5 h-5 text-[#043062]"></i>
    </span>
    <div>
      <div class="text-sm text-gray-500">Books</div>
      <div class="text-2xl font-bold"><?= $books ?></div>
    </div>
  </div>

  <!-- Orders -->
  <div class="bg-white p-4 rounded-xl shadow flex items-center gap-4">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#043062]/10">
      <i data-lucide="shopping-cart" class="w-5 h-5 text-[#043062]"></i>
    </span>
    <div>
      <div class="text-sm text-gray-500">Orders</div>
      <div class="text-2xl font-bold"><?= $orders ?></div>
    </div>
  </div>

  <!-- Sales -->
  <div class="bg-white p-4 rounded-xl shadow flex items-center gap-4">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#043062]/10">
      <i data-lucide="banknote" class="w-5 h-5 text-[#043062]"></i>
    </span>
    <div>
      <div class="text-sm text-gray-500">Sales</div>
      <div class="text-2xl font-bold">$<?= number_format($sales,2) ?></div>
    </div>
  </div>

  <!-- Users -->
  <div class="bg-white p-4 rounded-xl shadow flex items-center gap-4">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#043062]/10">
      <i data-lucide="users" class="w-5 h-5 text-[#043062]"></i>
    </span>
    <div>
      <div class="text-sm text-gray-500">Users</div>
      <div class="text-2xl font-bold"><?= $users ?></div>
    </div>
  </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>if (window.lucide) lucide.createIcons();</script>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

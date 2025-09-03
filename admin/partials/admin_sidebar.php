<!-- <aside class="bg-[#007BFF] text-white p-4">
  <div class="font-bold text-lg mb-6"></div>
  <nav class="space-y-2">
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/index.php">Overview</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/books.php">Books</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/categories.php">Categories</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/orders.php">Orders</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/reports.php">Reports</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="/admin/users.php">Users</a>
  </nav>
</aside> -->

<!-- <aside class="bg-[#007BFF] text-white p-4">
  <div class="font-bold text-lg mb-6">< ?></div>
  <nav class="space-y-2">
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/index.php') ?>">Overview</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/books.php') ?>">Books</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/categories.php') ?>">Categories</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/orders.php') ?>">Orders</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/reports.php') ?>">Reports</a>
    <a class="block rounded px-3 py-2 hover:bg-white/10" href="<?= url('admin/users.php') ?>">Users</a>
  </nav>
</aside> -->

<aside class="bg-gradient-to-r from-[#042F5F] via-[#043062] to-[#043165] text-white w-64 min-h-screen p-5 shadow-xl">
  <!-- App Name -->
  <div class="flex items-center gap-2 mb-8">
    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
      <i data-lucide="book-open" class="w-5 h-5"></i>
    </span>
    <span class="font-bold text-xl tracking-wide"><?= e(APP_NAME) ?></span>
  </div>

  <!-- Navigation -->
  <nav class="space-y-2">
    <a href="<?= url('admin/index.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
      <span>Overview</span>
    </a>
    <a href="<?= url('admin/books.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="book" class="w-5 h-5"></i>
      <span>Books</span>
    </a>
    <a href="<?= url('admin/categories.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="layers" class="w-5 h-5"></i>
      <span>Categories</span>
    </a>
    <a href="<?= url('admin/orders.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="shopping-cart" class="w-5 h-5"></i>
      <span>Orders</span>
    </a>
    <a href="<?= url('admin/reports.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
      <span>Reports</span>
    </a>
    <a href="<?= url('admin/users.php') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/15 transition">
      <i data-lucide="users" class="w-5 h-5"></i>
      <span>Users</span>
    </a>
  </nav>
</aside>

<script>
  lucide.createIcons(); // render all icons
</script>



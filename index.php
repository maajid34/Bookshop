<?php require("config/config.php"); ?>
<?php require("includes/db.php");?>
<?php require("partials/header.php"); ?>

<section class="max-w-6xl mx-auto px-4 py-12">
  <div class="grid md:grid-cols-2 gap-10 items-center">
    <div>
      <h1 class="text-3xl md:text-4xl font-bold leading-tight">Discover your next great read</h1>
      <p class="mt-3 text-gray-600">Browse, add to cart, and order books quickly with our simple checkout.</p>
      <div class="mt-6 flex gap-3">
        <a href="books.php" class="px-5 py-3 rounded bg-primary text-white font-semibold">Shop Books</a>
        <a href="about.php" class="px-5 py-3 rounded border border-gray-300">Learn more</a>
      </div>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow">
      <form action="books.php" method="get" class="flex gap-2">
        <input type="text" name="q" class="w-full border rounded px-3 py-3" placeholder="Search by title or author...">
        <button class="px-5 py-3 rounded bg-accent text-black font-semibold">Search</button>
      </form>
    </div>
  </div>
</section>

<section class="max-w-6xl mx-auto px-4 pb-16">
  <h2 class="text-xl font-semibold mb-4">New Arrivals</h2>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
  <?php
    $stmt = $pdo->query("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id=c.id ORDER BY b.id DESC LIMIT 8");
    foreach($stmt as $b):
  ?>
    <div class="bg-white rounded-xl shadow p-4 flex flex-col">
      <div class="aspect-[3/4] bg-gray-100 rounded mb-3 overflow-hidden flex items-center justify-center">
        <?php if($b['cover']): ?>
          <img src="<?= e($b['cover']) ?>" alt="" class="w-full h-full object-cover">
        <?php else: ?>
          <span class="text-gray-400 text-sm">No cover</span>
        <?php endif; ?>
      </div>
      <div class="font-semibold"><?= e($b['title']) ?></div>
      <div class="text-sm text-gray-600"><?= e($b['author']) ?></div>
      <div class="mt-auto flex items-center justify-between pt-2">
        <div class="font-bold">$<?= e(number_format($b['price'],2)) ?></div>
        <a href="/public/book.php?id=<?= $b['id'] ?>" class="text-sm text-primary font-semibold">View</a>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

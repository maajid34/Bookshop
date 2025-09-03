<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id=c.id WHERE b.id=?");
$stmt->execute([$id]);
$b = $stmt->fetch();
if(!$b){ http_response_code(404); echo '<div class="max-w-3xl mx-auto px-4 py-12">Book not found</div>'; require __DIR__.'/partials/footer.php'; exit; }
?>
<div class="max-w-5xl mx-auto px-4 py-10 grid md:grid-cols-2 gap-8">
  <div class="bg-white rounded-xl shadow p-3">
    <div class="aspect-[3/4] bg-gray-100 rounded overflow-hidden flex items-center justify-center">
      <?php if($b['cover']): ?><img src="<?= e($b['cover']) ?>" class="w-full h-full object-cover"><?php else: ?><span class="text-gray-400">No cover</span><?php endif; ?>
    </div>
  </div>
  <div>
    <h1 class="text-2xl font-bold"><?= e($b['title']) ?></h1>
    <div class="text-gray-600">by <?= e($b['author']) ?></div>
    <div class="text-sm text-gray-500 mt-1"><?= e($b['category']) ?> • <?= e($b['book_condition']) ?></div>
    <div class="mt-4 text-2xl font-bold">$<?= e(number_format($b['price'],2)) ?></div>
    <p class="mt-4 text-gray-700 whitespace-pre-line"><?= e($b['description']) ?></p>
    <form method="post" action="/public/cart.php" class="mt-6">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="book_id" value="<?= $b['id'] ?>">
      <button class="px-5 py-3 rounded bg-primary text-white">Add to cart</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php require_once __DIR__ . '/includes/functions.php'; ?>
<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$q = trim($_GET['q'] ?? '');
$cat = intval($_GET['category'] ?? 0);
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';
$cond = $_GET['cond'] ?? '';

$where = [];
$params = [];

if($q !== ''){ $where[] = "(b.title LIKE ? OR b.author LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
if($cat){ $where[] = "b.category_id=?"; $params[] = $cat; }
if($min !== ''){ $where[] = "b.price>=?"; $params[] = floatval($min); }
if($max !== ''){ $where[] = "b.price<=?"; $params[] = floatval($max); }
if(in_array($cond, ['new','used'])){ $where[] = "b.book_condition=?"; $params[] = $cond; }
$where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Pagination
$perPage = 12;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page-1) * $perPage;

// Count
$stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM books b $where_sql");
$stmt->execute($params);
$total = (int)$stmt->fetch()['c'];

$pg = paginate($total, $perPage, $page);

// Data
$stmt = $pdo->prepare("SELECT b.*, c.name AS category FROM books b LEFT JOIN categories c ON b.category_id=c.id $where_sql ORDER BY b.id DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$books = $stmt->fetchAll();

$cats = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
?>

<div class="max-w-6xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-4">Books</h1>

  <form class="grid md:grid-cols-6 gap-3 bg-white p-4 rounded-xl shadow mb-6">
    <input class="border rounded px-3 py-2 md:col-span-2" name="q" value="<?= e($q) ?>" placeholder="Search title/author">
    <select class="border rounded px-3 py-2" name="category">
      <option value="0">All categories</option>
      <?php foreach($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $cat==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <input class="border rounded px-3 py-2" type="number" step="0.01" name="min" value="<?= e($min) ?>" placeholder="Min price">
    <input class="border rounded px-3 py-2" type="number" step="0.01" name="max" value="<?= e($max) ?>" placeholder="Max price">
    <select class="border rounded px-3 py-2" name="cond">
      <option value="">Any condition</option>
      <option value="new" <?= $cond==='new'?'selected':'' ?>>New</option>
      <option value="used" <?= $cond==='used'?'selected':'' ?>>Used</option>
    </select>
    <button class="px-4 py-2 rounded bg-accent text-black font-semibold">Filter</button>
  </form>

  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php foreach($books as $b): ?>
    <div class="bg-white rounded-xl shadow p-4 flex flex-col">
      <a href="book.php?id=<?= $b['id'] ?>" class="aspect-[3/4] bg-gray-100 rounded mb-3 overflow-hidden flex items-center justify-center">
        <?php if($b['cover']): ?><img src="<?= e($b['cover']) ?>" class="w-full h-full object-cover"><?php else: ?><span class="text-gray-400 text-sm">No cover</span><?php endif; ?>
      </a>
      <div class="font-semibold"><?= e($b['title']) ?></div>
      <div class="text-sm text-gray-600"><?= e($b['author']) ?></div>
      <div class="text-xs text-gray-500"><?= e($b['category']) ?> • <?= e($b['book_condition']) ?></div>
      <div class="mt-auto flex items-center justify-between pt-2">
        <div class="font-bold">$<?= e(number_format($b['price'],2)) ?></div>
        
        <form method="post" action="cart.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="book_id" value="<?= $b['id'] ?>">
          <button class="text-sm text-primary font-semibold">Add to cart</button>
        </form>
      </div>
<?php if (!empty($b['audio'])): ?>
  <audio controls preload="none" class="w-full mt-2">
    <source src="<?= e($b['audio']) ?>">
    Your browser does not support the audio element.
  </audio>
<?php endif; ?>
    </div>
    
    <?php endforeach; ?>
  </div>

  <?php if($pg['pages']>1): ?>
    <div class="mt-8 flex gap-2">
      <?php for($i=1;$i<=$pg['pages'];$i++): ?>
        <a class="px-3 py-1 rounded border <?= $i==$pg['page'] ? 'bg-primary text-white' : '' ?>" href="?<?= http_build_query(array_merge($_GET,['page'=>$i])) ?>"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

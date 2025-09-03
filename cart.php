<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if($action==='add'){
  $book_id = intval($_POST['book_id'] ?? 0);
  if($book_id){
    $_SESSION['cart'][$book_id] = ($_SESSION['cart'][$book_id] ?? 0) + 1;
  }
  header('Location: cart.php'); exit;
}
if($action==='update'){
  foreach($_POST['qty'] ?? [] as $id=>$q){
    $q = max(0, intval($q));
    if($q==0) unset($_SESSION['cart'][$id]);
    else $_SESSION['cart'][$id] = $q;
  }
  header('Location: cart.php'); exit;
}
if($action==='clear'){
  $_SESSION['cart'] = [];
  header('Location: cart.php'); exit;
}

// Load books
$items = [];
$total = 0;
if($_SESSION['cart']){
  $ids = array_map('intval', array_keys($_SESSION['cart']));
  $placeholders = implode(',', array_fill(0,count($ids),'?'));
  $stmt = $pdo->prepare("SELECT id,title,price,cover FROM books WHERE id IN ($placeholders)");
  $stmt->execute($ids);
  foreach($stmt as $b){
    $qty = $_SESSION['cart'][$b['id']] ?? 0;
    $items[] = ['id'=>$b['id'],'title'=>$b['title'],'price'=>$b['price'],'cover'=>$b['cover'],'qty'=>$qty,'sub'=>$qty*$b['price']];
    $total += $qty*$b['price'];
  }
}

require_once __DIR__ . '/partials/header.php';
?>

<div class="max-w-5xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-4">Cart</h1>
  <?php if(!$items): ?>
    <div class="bg-white p-6 rounded-xl shadow">Your cart is empty.</div>
  <?php else: ?>
    <form method="post" class="bg-white p-4 rounded-xl shadow">
      <input type="hidden" name="action" value="update">
      <table class="w-full text-sm">
        <thead><tr class="border-b">
          <th class="text-left py-2">Book</th><th>Price</th><th>Qty</th><th>Subtotal</th>
        </tr></thead>
        <tbody>
        <?php foreach($items as $it): ?>
          <tr class="border-b">
            <td class="py-2"><?= e($it['title']) ?></td>
            <td class="text-center">$<?= number_format($it['price'],2) ?></td>
            <td class="text-center"><input class="w-16 border rounded text-center" type="number" name="qty[<?= $it['id'] ?>]" value="<?= $it['qty'] ?>"></td>
            <td class="text-right">$<?= number_format($it['sub'],2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div class="flex items-center justify-between mt-4">
        <a href="books.php" class="text-primary">Continue shopping</a>
        <div class="text-lg font-bold">Total: $<?= number_format($total,2) ?></div>
      </div>
      <div class="mt-4 flex gap-3">
        <button class="px-4 py-2 rounded border" name="action" value="update">Update</button>
        <a class="px-4 py-2 rounded bg-primary text-white" href="checkout.php">Checkout</a>
        <a class="px-4 py-2 rounded border" href="cart.php?action=clear">Clear</a>
      </div>
    </form>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

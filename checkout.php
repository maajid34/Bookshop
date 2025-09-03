<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php require_once __DIR__ . '/includes/auth.php'; ?>
<?php require_login(); ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
// Prepare cart
$cart = $_SESSION['cart'] ?? [];
if(!$cart){ header('Location: cart.php'); exit; }

$total = 0; $items = [];
$ids = array_map('intval', array_keys($cart));
$placeholders = implode(',', array_fill(0,count($ids),'?'));
$stmt = $pdo->prepare("SELECT id,title,price,stock FROM books WHERE id IN ($placeholders)");
$stmt->execute($ids);
$books = $stmt->fetchAll();
$bookById = []; foreach($books as $b){ $bookById[$b['id']]=$b; }
foreach($cart as $id=>$qty){
  $b = $bookById[$id];
  $qty = min($qty, $b['stock']);
  if($qty <= 0) continue;
  $items[] = ['id'=>$id,'title'=>$b['title'],'qty'=>$qty,'price'=>$b['price']];
  $total += $qty*$b['price'];
}

$msg = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $method = in_array($_POST['payment_method'] ?? '', ['cod','mobile']) ? $_POST['payment_method'] : 'cod';
  $pref = trim($_POST['payment_reference'] ?? '');

  if(!$name || !$phone || !$address){
    $msg = 'Please fill all required fields.';
  } else {
    // Start transaction
    $pdo->beginTransaction();
    try {
      $stmt = $pdo->prepare("INSERT INTO orders (user_id,total_amount,status,payment_method,payment_reference,shipping_name,shipping_phone,shipping_address) VALUES (?,?,?,?,?,?,?,?)");
      $stmt->execute([user()['id'],$total,'pending',$method,$pref,$name,$phone,$address]);
      $order_id = $pdo->lastInsertId();
      $ins = $pdo->prepare("INSERT INTO order_items (order_id,book_id,quantity,unit_price) VALUES (?,?,?,?)");
      $upd = $pdo->prepare("UPDATE books SET stock=stock-? WHERE id=? AND stock>=?");

      foreach($items as $it){
        $ins->execute([$order_id,$it['id'],$it['qty'],$it['price']]);
        $upd->execute([$it['qty'],$it['id'],$it['qty']]);
      }
      $pdo->commit();
      $_SESSION['cart'] = []; // clear
      header('Location: orders.php?placed=1'); exit;
    } catch(Exception $e){
      $pdo->rollBack();
      $msg = 'Order failed: ' . $e->getMessage();
    }
  }
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="max-w-4xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-4">Checkout</h1>
  <?php if($msg): ?><div class="mb-3 p-3 bg-red-50 border border-red-200 rounded text-sm"><?= e($msg) ?></div><?php endif; ?>
  <div class="grid md:grid-cols-2 gap-6">
    <form method="post" class="space-y-3 bg-white p-4 rounded-xl shadow">
      <input class="w-full border px-3 py-2 rounded" name="name" placeholder="Full name" value="<?= e(user()['name']) ?>">
      <input class="w-full border px-3 py-2 rounded" name="phone" placeholder="Phone">
      <textarea class="w-full border px-3 py-2 rounded" name="address" placeholder="Shipping address" rows="3"></textarea>
      <div>
        <label class="font-semibold block mb-1">Payment method</label>
        <label class="mr-4"><input type="radio" name="payment_method" value="cod" checked> Cash on Delivery</label>
        <label class="ml-4"><input type="radio" name="payment_method" value="mobile"> Mobile (enter reference)</label>
      </div>
      <input class="w-full border px-3 py-2 rounded" name="payment_reference" placeholder="Payment reference (optional)">
      <button class="px-5 py-2 rounded bg-primary text-white">Place Order</button>
    </form>
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="font-semibold mb-2">Order Summary</div>
      <ul class="text-sm space-y-2">
        <?php foreach($items as $it): ?>
          <li class="flex justify-between"><span><?= e($it['title']) ?> x <?= e($it['qty']) ?></span><span>$<?= number_format($it['qty']*$it['price'],2) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-3 border-t pt-2 font-bold flex justify-between"><span>Total</span><span>$<?= number_format($total,2) ?></span></div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

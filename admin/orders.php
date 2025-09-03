<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id = intval($_POST['id'] ?? 0);
  $status = in_array($_POST['status'] ?? '', ['pending','confirmed','shipped','cancelled']) ? $_POST['status'] : 'pending';
  if($id){
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->execute([$status,$id]);
  }
}
?>
<h1 class="text-xl font-semibold mb-4">Orders</h1>
<div class="bg-white rounded-xl shadow overflow-x-auto">
  <table class="w-full text-sm">
    <thead><tr class="border-b"><th class="text-left py-2 px-3">#</th><th>Custome_name</th><th>Contact</th><th>Book title</th><th>Quantity</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php
      // $stmt = $pdo->query("SELECT o.*, u.email,o.shipping_name,o.shipping_phone  FROM orders o LEFT JOIN users u ON o.user_id=u.id ORDER BY o.id DESC");
      $stmt = $pdo->query("SELECT o.*, o.shipping_name,o.shipping_phone,o.shipping_address,b.title,oi.quantity,o.total_amount,o.created_at FROM orders o LEFT JOIN users u 
    ON o.user_id = u.id LEFT JOIN order_items oi ON o.id = oi.order_id left join books b on oi.book_id = b.id ORDER BY o.id DESC;");
      foreach($stmt as $o):
    ?>
      <tr class="border-b">
        <td class="py-2 px-3"><?= $o['id'] ?></td>
       
        <td><?= e($o['shipping_name']) ?></td>
        <td><?= e($o['shipping_phone']) ?></td>
        <td><?= e($o['title']) ?></td>
        <td><?= e($o['quantity']) ?></td>
        <td>$<?= number_format($o['total_amount'],2) ?></td>
        <td><?= e($o['status']) ?></td>
        <td><?= e($o['created_at']) ?></td>
        <td class="text-right px-3 py-2">
          <a class="text-primary mr-3" href="/admin/order_view.php?id=<?= $o['id'] ?>">View</a>
          <form method="post" class="inline">
            <input type="hidden" name="id" value="<?= $o['id'] ?>">
            <select name="status" class="border rounded px-2 py-1">
              <?php foreach(['pending','confirmed','shipped','cancelled'] as $s): ?>
                <option value="<?= $s ?>" <?= $o['status']==$s?'selected':'' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
            <button class="ml-2 px-3 py-1 rounded bg-primary text-white">Save</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

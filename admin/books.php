<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>

<?php if (!empty($b['cover'])): ?>
  <img src="<?= htmlspecialchars($b['cover']) ?>" class="h-12 w-10 object-cover rounded" alt="">
<?php else: ?>
  <span class="text-gray-500">No cover</span>
<?php endif; ?>

<?php if (!empty($b['audio'])): ?>
  <audio controls preload="none" src="<?= htmlspecialchars($b['audio']) ?>"></audio>
<?php else: ?>
  —
<?php endif; ?>

<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-semibold">Books</h1>
  <a href="<?= url('admin/book_create.php') ?>" class="px-3 py-2 rounded bg-primary text-white">+ New Book</a>
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">
  <table class="w-full text-sm">
    <thead>
      <tr class="border-b">
        <th class="text-left py-2 px-3">Cover</th>
        <th class="text-left py-2 px-3">Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Audio</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php
      // b.* already includes cover and audio columns
      $stmt = $pdo->query("
        SELECT b.*, c.name AS category
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id
        ORDER BY b.id DESC
      ");
      foreach ($stmt as $b):
    ?>
      <tr class="border-b">
        <td class="py-2 px-3">
          <?php if(!empty($b['cover'])): ?>
            <img
              src="<?= url($b['cover']) ?>"
              alt="cover"
              class="w-10 h-14 object-cover rounded border"
              onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'text-gray-400 text-xs',textContent:'No cover'}));"
            >
          <?php else: ?>
            <span class="text-gray-400 text-xs">No cover</span>
          <?php endif; ?>
        </td>
        <td class="py-2 px-3"><?= e($b['title']) ?></td>
        <td class="text-center"><?= e($b['author']) ?></td>
        <td class="text-center">$<?= number_format($b['price'], 2) ?></td>
        <td class="text-center"><?= e($b['stock']) ?></td>
        <td class="text-center"><?= e($b['category']) ?></td>
        <td class="text-center py-2 px-3">
          <?php if(!empty($b['audio'])): ?>
            <audio controls preload="none" class="w-40 align-middle">
              <source src="<?= url($b['audio']) ?>">
            </audio>
          <?php else: ?>
            <span class="text-gray-400 text-xs">—</span>
          <?php endif; ?>
        </td>
        <td class="text-right px-3 py-2 whitespace-nowrap">
          <a class="text-primary mr-3" href="<?= url('admin/book_edit.php?id=' . $b['id']) ?>">Edit</a>
          <a class="text-red-600" href="<?= url('admin/book_delete.php?id=' . $b['id']) ?>" onclick="return confirm('Delete this book?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

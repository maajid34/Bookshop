<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/auth.php'; ?>

<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id=?");
$stmt->execute([$id]);
$b = $stmt->fetch();
if (!$b) { die('Book not found'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title       = trim($_POST['title'] ?? '');
  $author      = trim($_POST['author'] ?? '');
  $price       = floatval($_POST['price'] ?? 0);
  $stock       = intval($_POST['stock'] ?? 0);
  $category_id = intval($_POST['category_id'] ?? 0);
  $condition   = in_array($_POST['book_condition'] ?? 'new', ['new','used']) ? $_POST['book_condition'] : 'new';
  $isbn        = trim($_POST['isbn'] ?? '');
  $language    = trim($_POST['language'] ?? '');
  $pages       = intval($_POST['pages'] ?? 0);

  // Keep existing values unless new files are uploaded
  $coverPath = $b['cover'];
  $audioPath = $b['audio'] ?? null;

  /* ---- Cover upload (optional) ---- */
  if (!empty($_FILES['cover']['name'])) {
    $allowed = ['png','jpg','jpeg','gif'];
    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed) && $_FILES['cover']['size'] <= 2*1024*1024) {
      $name = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
      $destDir = __DIR__ . '/../assets/uploads/covers/';
      if (!is_dir($destDir)) mkdir($destDir, 0775, true);
      $dest = $destDir . $name;
      if (move_uploaded_file($_FILES['cover']['tmp_name'], $dest)) {
        $coverPath = 'assets/uploads/covers/' . $name; // relative web path
      }
    }
  }

  /* ---- Audio upload (optional) ---- */
  if (!empty($_FILES['audio']['name'])) {
    $allowedAudio = ['mp3','m4a','aac','wav','ogg','oga','flac','webm'];
    $aext = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
    if (in_array($aext, $allowedAudio) && $_FILES['audio']['size'] <= 15*1024*1024) {
      $aname = 'audio_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $aext;
      $adir = __DIR__ . '/../assets/uploads/audio/';
      if (!is_dir($adir)) mkdir($adir, 0775, true);
      $adest = $adir . $aname;
      if (move_uploaded_file($_FILES['audio']['tmp_name'], $adest)) {
        $audioPath = 'assets/uploads/audio/' . $aname; // relative web path
      }
    }
  }

  // Keep description as-is (we’re focusing on audio now)
  $description = $b['description'];

  $stmt = $pdo->prepare("UPDATE books
    SET category_id=?, title=?, author=?, price=?, book_condition=?, stock=?, isbn=?, language=?, pages=?, cover=?, audio=?, description=?
    WHERE id=?");
  $stmt->execute([$category_id,$title,$author,$price,$condition,$stock,$isbn,$language,$pages,$coverPath,$audioPath,$description,$id]);

  header('Location: ' . url('admin/books.php')); exit;
}
?>

<?php require_once __DIR__ . '/partials/admin_header.php'; ?>
<h1 class="text-xl font-semibold mb-4">Edit Book</h1>

<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow grid md:grid-cols-2 gap-3">
  <input class="border rounded px-3 py-2" name="title" value="<?= e($b['title']) ?>" required>
  <input class="border rounded px-3 py-2" name="author" value <?= '="'.e($b['author']).'"' ?> required>
  <input class="border rounded px-3 py-2" type="number" step="0.01" name="price" value="<?= e($b['price']) ?>" required>
  <input class="border rounded px-3 py-2" type="number" name="stock" value="<?= e($b['stock']) ?>" required>

  <select class="border rounded px-3 py-2" name="category_id" required>
    <?php foreach($pdo->query("SELECT id,name FROM categories ORDER BY name") as $c): ?>
      <option value="<?= $c['id'] ?>" <?= $b['category_id']==$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <select class="border rounded px-3 py-2" name="book_condition">
    <option value="new"  <?= $b['book_condition']=='new'?'selected':'' ?>>New</option>
    <option value="used" <?= $b['book_condition']=='used'?'selected':'' ?>>Used</option>
  </select>

  <input class="border rounded px-3 py-2" name="isbn" value="<?= e($b['isbn']) ?>">
  <input class="border rounded px-3 py-2" name="language" value="<?= e($b['language']) ?>">
  <input class="border rounded px-3 py-2" type="number" name="pages" value="<?= e($b['pages']) ?>">

  <!-- Cover -->
  <div class="md:col-span-2">
    <label class="block text-sm font-semibold mb-1">Cover image</label>
    <?php if(!empty($b['cover'])): ?>
      <img src="<?= url($b['cover']) ?>" class="w-16 h-20 object-cover rounded border mb-2" alt="cover">
    <?php endif; ?>
    <input class="border rounded px-3 py-2 w-full" type="file" name="cover" accept="image/*">
  </div>

  <!-- Audio -->
  <div class="md:col-span-2">
    <label class="block text-sm font-semibold mb-1">Audio file</label>
    <?php if(!empty($b['audio'])): ?>
      <audio controls preload="none" class="w-full mb-2">
        <source src="<?= url($b['audio']) ?>">
      </audio>
    <?php endif; ?>
    <input class="border rounded px-3 py-2 w-full" type="file" name="audio" accept="audio/*">
  </div>

  <div class="md:col-span-2">
    <button class="px-4 py-2 rounded bg-primary text-white">Update</button>
    <a href="<?= url('admin/books.php') ?>" class="px-4 py-2 rounded border">Cancel</a>
  </div>
</form>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

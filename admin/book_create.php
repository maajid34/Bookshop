<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php require_once __DIR__ . '/partials/admin_header.php'; ?>

<?php
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title       = trim($_POST['title'] ?? '');
  $author      = trim($_POST['author'] ?? '');
  $price       = floatval($_POST['price'] ?? 0);
  $stock       = intval($_POST['stock'] ?? 0);
  $category_id = intval($_POST['category_id'] ?? 0);
  $condition   = in_array($_POST['book_condition'] ?? 'new',['new','used']) ? $_POST['book_condition'] : 'new';
  $isbn        = trim($_POST['isbn'] ?? '');
  $language    = trim($_POST['language'] ?? '');
  $pages       = intval($_POST['pages'] ?? 0);

  // We’re replacing description with audio, so keep it NULL
  $description = null;

  /* ---- Cover upload ---- */
  // $coverPath = null;
  // if(!empty($_FILES['cover']['name'])){
  //   $allowed = ['png','jpg','jpeg','gif'];
  //   $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
  //   if(in_array($ext,$allowed) && $_FILES['cover']['size'] <= 2*1024*1024){
  //     $name = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
  //     $destDir = __DIR__ . '/../assets/uploads/covers/';
  //     if(!is_dir($destDir)) mkdir($destDir, 0775, true);
  //     $dest = $destDir . $name;
  //     if(move_uploaded_file($_FILES['cover']['tmp_name'], $dest)){
  //       $coverPath = 'assets/uploads/covers/' . $name; // relative web path
  //     }
  //   }
  // }

/* ---- Cover upload ---- */
 $coverPath = null;
  if(!empty($_FILES['cover']['name'])){
    $allowed = ['png','jpg','jpeg','gif'];
    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
    if(in_array($ext,$allowed) && $_FILES['cover']['size']<=2*1024*1024){
      $name = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
      $dest = __DIR__ . '/../assets/uploads/covers/' . $name;
      if(move_uploaded_file($_FILES['cover']['tmp_name'], $dest)){
        $coverPath = 'assets/uploads/covers/' . $name;
      }
    }
  }


  /* ---- Audio upload ---- */
  $audioPath = null;
  if(!empty($_FILES['audio']['name'])){
    $allowedAudio = ['mp3','m4a','aac','wav','ogg','oga','flac','webm'];
    $aext = strtolower(pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION));
    if(in_array($aext, $allowedAudio) && $_FILES['audio']['size'] <= 15*1024*1024){
      $aname = 'audio_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $aext;
      $adir = __DIR__ . '/../assets/uploads/audio/';
      if(!is_dir($adir)) mkdir($adir, 0775, true);
      $adest = $adir . $aname;
      if(move_uploaded_file($_FILES['audio']['tmp_name'], $adest)){
        $audioPath = 'assets/uploads/audio/' . $aname; // relative web path
      }
    }
  }

  $stmt = $pdo->prepare(
    "INSERT INTO books
      (category_id,title,author,price,book_condition,stock,isbn,language,pages,cover,audio,description)
     VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
  );
  $stmt->execute([
    $category_id,$title,$author,$price,$condition,$stock,$isbn,$language,$pages,$coverPath,$audioPath,$description
  ]);

  header('Location: ' . url('../admin/books.php')); exit;
}

$cats = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
?>

<h1 class="text-xl font-semibold mb-4">Create Book</h1>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow grid md:grid-cols-2 gap-3">
  <input class="border rounded px-3 py-2" name="title" placeholder="Title" required>
  <input class="border rounded px-3 py-2" name="author" placeholder="Author" required>
  <input class="border rounded px-3 py-2" type="number" step="0.01" name="price" placeholder="Price" required>
  <input class="border rounded px-3 py-2" type="number" name="stock" placeholder="Stock" required>

  <select class="border rounded px-3 py-2" name="category_id" required>
    <option value="">Select category</option>
    <?php foreach($cats as $c): ?>
      <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <select class="border rounded px-3 py-2" name="book_condition">
    <option value="new">New</option>
    <option value="used">Used</option>
  </select>

  <input class="border rounded px-3 py-2" name="isbn" placeholder="ISBN">
  <input class="border rounded px-3 py-2" name="language" placeholder="Language">
  <input class="border rounded px-3 py-2" type="number" name="pages" placeholder="Pages">

  <label class="md:col-span-2 text-sm font-semibold">Cover image</label>
  <input class="border rounded px-3 py-2 md:col-span-2" type="file" name="cover" accept="image/*">

  <label class="md:col-span-2 text-sm font-semibold">Audio file (instead of description)</label>
  <input class="border rounded px-3 py-2 md:col-span-2" type="file" name="audio" accept="audio/*">

  <div class="md:col-span-2">
    <button class="px-4 py-2 rounded bg-primary text-white">Save</button>
    <a href="<?= url('admin/books.php') ?>" class="px-4 py-2 rounded border">Cancel</a>
  </div>
</form>

<?php require_once __DIR__ . '/partials/admin_footer.php'; ?>

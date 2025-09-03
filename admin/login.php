<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/auth.php'; ?>

<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $stmt = $pdo->prepare("SELECT id,name,email,role,password_hash FROM users WHERE email=? AND role='admin' LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if($u && password_verify($pass,$u['password_hash'])){
    $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];
    header('Location: ' . url('admin/index.php'));  exit;
    
  } else $msg='Invalid admin credentials';
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 bg-[url('../assets/uploads/covers/book-library-with-open-textbook.jpg')] bg-cover bg-center">

  <div class="max-w-md mx-auto mt-24 bg-white p-6 rounded-xl shadow">
    <div class="text-xl font-semibold mb-3">Admin Login</div>
    <?php if($msg): ?><div class="mb-3 p-3 bg-red-50 border border-red-200 rounded text-sm"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form method="post" class="space-y-3">
      <input class="w-full border px-3 py-2 rounded" type="email" name="email" placeholder="Admin email">
      <input class="w-full border px-3 py-2 rounded" type="password" name="password" placeholder="Password">
      <button class="px-4 py-2 rounded bg-[#002B5C] text-white">Log in</button>
    </form>
  </div>

</body>
</html>

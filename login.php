<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$msg = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $stmt = $pdo->prepare("SELECT id,name,email,role,password_hash FROM users WHERE email=? LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if($u && password_verify($pass, $u['password_hash'])){
    $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];
    $to = $_SESSION['redirect_after_login'] ?? 'index.php';
    unset($_SESSION['redirect_after_login']);
    header('Location: '.$to); exit;
  } else $msg='Invalid credentials';
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="max-w-md mx-auto px-4 py-12">
  <h1 class="text-2xl font-bold mb-2">Login</h1>
  <?php if($msg): ?><div class="mb-3 p-3 bg-red-50 border border-red-200 rounded text-sm"><?= e($msg) ?></div><?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-6 rounded-xl shadow">
    <input class="w-full border px-3 py-2 rounded" name="email" type="email" placeholder="Email">
    <input class="w-full border px-3 py-2 rounded" name="password" type="password" placeholder="Password">
    <button class="px-5 py-2 rounded bg-primary text-white">Login</button>
  </form>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

<?php require_once __DIR__ . '/config/config.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$msg = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if(!$name || !$email || !$pass){
    $msg = 'All fields required';
  } else {
    try{
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
      $stmt->execute([$name,$email,password_hash($pass, PASSWORD_DEFAULT)]);
      $id = $pdo->lastInsertId();
      $_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email,'role'=>'customer'];
      header('Location: index.php'); exit;
    } catch(PDOException $e){
      $msg = 'Registration failed: ' . ($e->getCode()=='23000' ? 'Email already exists' : $e->getMessage());
    }
  }
}
?>

<?php require_once __DIR__ . '/partials/header.php'; ?>
<div class="max-w-md mx-auto px-4 py-12">
  <h1 class="text-2xl font-bold mb-2">Create account</h1>
  <?php if($msg): ?><div class="mb-3 p-3 bg-red-50 border border-red-200 rounded text-sm"><?= e($msg) ?></div><?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-6 rounded-xl shadow">
    <input class="w-full border px-3 py-2 rounded" name="name" placeholder="Full name">
    <input class="w-full border px-3 py-2 rounded" name="email" type="email" placeholder="Email">
    <input class="w-full border px-3 py-2 rounded" name="password" type="password" placeholder="Password">
    <button class="px-5 py-2 rounded bg-primary text-white">Register</button>
  </form>
</div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>

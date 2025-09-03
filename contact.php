<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/db.php'; ?>
<?php require_once __DIR__ . '/../includes/csrf.php'; csrf_check(); ?>
<?php require_once __DIR__ . '/partials/header.php'; ?>

<?php
$msg = '';
if(is_post()){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  if($name && $email && $subject && $message){
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)");
    $stmt->execute([$name,$email,$subject,$message]);
    $msg = 'Message sent.';
  } else {
    $msg = 'All fields are required.';
  }
}
?>

<div class="max-w-3xl mx-auto px-4 py-12">
  <h1 class="text-2xl font-bold mb-2">Contact us</h1>
  <?php if($msg): ?><div class="mb-3 text-sm p-3 bg-green-50 border border-green-200 rounded"><?= e($msg) ?></div><?php endif; ?>
  <form method="post" class="space-y-3 bg-white p-6 rounded-xl shadow">
    <?= csrf_field() ?>
    <input class="w-full border px-3 py-2 rounded" name="name" placeholder="Your name">
    <input class="w-full border px-3 py-2 rounded" type="email" name="email" placeholder="Your email">
    <input class="w-full border px-3 py-2 rounded" name="subject" placeholder="Subject">
    <textarea class="w-full border px-3 py-2 rounded" name="message" rows="5" placeholder="Message"></textarea>
    <button class="px-5 py-2 rounded bg-primary text-white">Send</button>
  </form>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

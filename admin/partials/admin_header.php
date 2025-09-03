<?php require_once __DIR__ . '/../../includes/auth.php'; require_admin(); ?>
<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html>
  <script src="https://unpkg.com/lucide@latest"></script>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin • <?= e(APP_NAME) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#007BFF',
            accent: '#FFD43B',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-100 bg-[url('../assets/uploads/covers/book-library-with-open-textbook.jpg')] bg-cover bg-center">
  <div class="min-h-screen grid grid-cols-[260px_1fr]">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <div class="flex flex-col">
      <div class="bg-white shadow px-4 py-3 flex items-center justify-between">
        <div class="font-semibold">Admin Dashboard</div>
        <div class="text-sm">Logged in as <?= e(user()['name']) ?> • <a class="text-primary" href="../../logout.php">Logout</a></div>
      </div>
      <div class="p-6">

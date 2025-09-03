<?php require("./includes/auth.php"); ?>


<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php require_once __DIR__ . '/../includes/functions.php'; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NAME) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- head: add Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

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

  <style>
  :root{
    --primary:#007BFF;
    --accent:#FFD43B;
    --ink:#0f172a;      /* slate-900 */
    --muted:#475569;    /* slate-600 */
    --ring:rgba(0,123,255,.25);
  }

  html,body{scroll-behavior:smooth}
  body{
    font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
    background:
      radial-gradient(1200px 600px at 20% -10%, rgba(0,123,255,.07), transparent 60%),
      radial-gradient(1200px 600px at 100% 10%, rgba(255,212,59,.07), transparent 60%),
      linear-gradient(180deg, #f8fafc, #ffffff);
    color: var(--ink);
  }

  /* ── Header (scoped to avoid overriding Tailwind gradient) ─────────────── */
  .site-header{
    position: sticky; top: 0; z-index: 50;
    /* let Tailwind classes control background (gradient) */
    border-bottom: 1px solid rgba(2,6,23,.06);
    transition: box-shadow .2s ease, background-color .2s ease, transform .2s ease;
  }
  .site-header.scrolled{
    background-color:#002B5C; /* solid when scrolled on top of gradient */
    box-shadow: 0 6px 30px -10px rgba(16,24,40,.25);
  }

  /* Navbar link polish + underline animation */
  .site-header nav a{
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: .25rem 0;
    font-weight: 500;
    color: rgba(255,255,255,.92);  /* WHITE by default */
    transition: color .2s ease;
    text-decoration: none;
  }
  .site-header nav a:hover{ color: #ffffff; }
  .site-header nav a::after{
    content:"";
    position:absolute; left:0; right:0; bottom:-6px; height:2px;
    transform: scaleX(0); transform-origin: left;
    background: linear-gradient(90deg, var(--accent), #fff);
    transition: transform .25s ease; border-radius:9999px;
  }
  .site-header nav a:hover::after{ transform: scaleX(1); }

  /* Logo chip subtle shine */
  .site-header .max-w-6xl > a > span:first-child{
    box-shadow: 0 10px 20px -10px rgba(0,123,255,.6), inset 0 -2px 0 0 rgba(255,255,255,.6);
    position: relative;
  }
  .site-header .max-w-6xl > a > span:first-child::after{
    content:""; position:absolute; inset:-2px; border-radius:9999px;
    background: radial-gradient(50% 60% at 50% 0%, rgba(255,255,255,.35), transparent 60%);
    pointer-events:none;
  }

  /* Gentle shapes in main area */
  main{
    background-image:
      radial-gradient(300px 120px at 20% 10%, rgba(0,123,255,.06), transparent 70%),
      radial-gradient(260px 120px at 80% 10%, rgba(255,212,59,.06), transparent 70%);
  }

  /* Button look (applied via JS, no markup changes) */
  .btn{
    padding:.5rem .75rem; border-radius:.5rem; font-weight:600;
    box-shadow:0 6px 20px -12px rgba(2,6,23,.25);
    transition: transform .15s ease, box-shadow .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
  }
  .btn:hover{ transform: translateY(-1px); box-shadow:0 14px 32px -16px rgba(2,6,23,.35); }

  /* Style specific links without touching your HTML */
  a[href*="login.php"]{ background:var(--primary) !important; color:#fff !important; border-color:transparent !important; }
  a[href*="register.php"], a[href*="logout.php"]{ border:1px solid rgba(2,6,23,.12) !important; }
  a[href*="/admin"]{ background:var(--accent) !important; color:#111827 !important; }

  a[href*="login.php"], a[href*="register.php"], a[href*="logout.php"], a[href*="/admin"]{
    border-radius:.5rem !important; padding:.5rem .75rem !important;
  }

  /* Tiny icons for Cart / Orders */
  a[href*="cart.php"]::before{ content:"🛒"; margin-right:.35rem; }
  a[href*="/orders.php"], a[href*="orders.php"]{ white-space:nowrap; }
  a[href*="/orders.php"]::before, a[href*="orders.php"]::before{ content:"📦"; margin-right:.35rem; }

  /* Better focus styles */
  a:focus-visible, button:focus-visible{
    outline:2px solid transparent; box-shadow:0 0 0 4px var(--ring); border-radius:.5rem;
  }

  /* Optional auto dark-mode (no markup changes) */
  @media (prefers-color-scheme: dark){
    body{ background: linear-gradient(180deg, #0b1220, #0b0f19); color:#e5e7eb; }
    .site-header{ background: rgba(17,24,39,.65); border-bottom-color: rgba(148,163,184,.15); }
    .site-header.scrolled{ background: rgba(17,24,39,.85); box-shadow: 0 6px 30px -10px rgba(0,0,0,.55); }
    .site-header nav a{ color:#eef2ff; }
    .site-header nav a:hover{ color:#ffffff; }
    a[href*="register.php"], a[href*="logout.php"]{ border-color: rgba(148,163,184,.25) !important; color:#e5e7eb !important; }
  }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- Header -->
<header class="site-header relative shadow-lg sticky top-0 z-50
               text-white text-2xl
               bg-gradient-to-r from-[#002B5C] via-[#063a7a] to-[#002B5C]">
  <!-- accent line -->
  <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#FFD43B] via-[#33b1ff] to-[#FFD43B]"></div>

  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="./index.php" class="flex items-center gap-3">
      <!-- <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#007BFF] text-white font-bold ring-2 ring-white/30 shadow-[0_8px_24px_-8px_rgba(0,123,255,.7)]"><img src="../logo.jpg" alt=""></span> -->
    
       <img src="./logo.png" alt="" class="rounded-[50%]">
     
    </a>

    <nav class="flex items-center gap-6 font-medium">
      <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="./index.php">Home</a>
      <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="./books.php">Books</a>
      <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="./about.php">About</a>
      <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="/contact.php">Contact</a>
      <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="./cart.php">🛒 Cart</a>

      <?php if(is_logged_in()): ?>
        <a class="relative text-white hover:text-white transition after:absolute after:left-0 after:bottom-[-6px] after:h-0.5 after:w-0 hover:after:w-full after:bg-[#FFD43B] after:transition-all after:duration-300" href="orders.php">📦 My Orders</a>
        <span class="text-white/90 text-xl">Hi, <strong><?= e(user()['name']) ?></strong></span>
        <?php if(is_admin()): ?>
          <a class="px-3 py-1.5 rounded-lg bg-[#FFD43B] text-[#002B5C] font-semibold shadow hover:shadow-md transition" href="/admin/index.php">Admin</a>
        <?php endif; ?>
        <a class="px-3 py-1.5 rounded-lg border border-white/30 text-white hover:bg-white/10 transition" href="./logout.php">Logout</a>
      <?php else: ?>
        <a class="px-3 py-1.5 rounded-lg bg-[#007BFF] text-white shadow hover:bg-[#0a6df0] transition" href="./login.php">Login</a>
        <a class="px-3 py-1.5 rounded-lg border border-white/30 text-white hover:bg-white/10 transition" href="./register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="min-h-[70vh]">
<script>
  // Header shadow when scrolling
  document.addEventListener('scroll', () => {
    const h = document.querySelector('header');
    if (!h) return;
    if (window.scrollY > 6) h.classList.add('scrolled');
    else h.classList.remove('scrolled');
  });

  // Give your CTA anchors a nice .btn treatment (no HTML edits)
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('a[href*="login.php"], a[href*="register.php"], a[href*="logout.php"], a[href*="/admin"]').forEach(a=>{
      a.classList.add('btn');
    });
  });
</script>

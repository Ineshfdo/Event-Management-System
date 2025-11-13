<?php
// File: header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?? "EventHorizan"; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- Header -->
<header class="fixed top-0 left-0 w-full z-50
    bg-white/30 backdrop-blur-xl
    border-b border-white/20
    supports-[backdrop-filter]:bg-white/40">

  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
<!-- Logo -->
<div class="flex-shrink-0">
  <a href="../pages/index.php" class="flex items-center gap-3 text-gray-900 font-semibold transition-transform duration-200 hover:scale-105">
    <img class="h-12 w-auto" src="../Images/apiit.png" alt="APIIT Logo">
    <span class="text-xl font-bold tracking-tight">Event Horizan</span>
  </a>
</div>


    <!-- Desktop Navigation -->
    <nav class="hidden md:flex gap-6 items-center text-slate-700 font-medium">

      <a href="../pages/index.php" 
         class="relative transition text-slate-700 hover:text-blue-600
                after:absolute after:left-0 after:-bottom-0.5 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Home
      </a>

      <a href="../pages/clubs.php"
         class="relative transition text-slate-700 hover:text-blue-600
                after:absolute after:left-0 after:-bottom-0.5 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Clubs
      </a>

      <a href="../pages/reminders.php"
         class="relative transition text-slate-700 hover:text-blue-600
                after:absolute after:left-0 after:-bottom-0.5 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Reminders
      </a>

     
      <!-- User / Login -->
      <?php if(isset($_SESSION['email'])): ?>
        <?php $firstLetter = strtoupper($_SESSION['email'][0]); ?>

        <div class="flex items-center gap-3 ml-4">
          <div class="w-10 h-10 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center">
            <?= $firstLetter ?>
          </div>

          <a href="../pages/logout.php"
             class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
            Logout
          </a>
        </div>

      <?php else: ?>
        <a href="../pages/login.php"
           class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
          Login
        </a>
      <?php endif; ?>
    </nav>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="md:hidden text-gray-700 focus:outline-none">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white/60 backdrop-blur-xl border-b border-white/20">
    <nav class="px-6 pt-3 pb-4 space-y-2 text-slate-800">

      <a href="../pages/index.php"
         class="block font-medium hover:text-blue-600 transition">Home</a>

      <a href="../pages/clubs.php"
         class="block font-medium hover:text-blue-600 transition">Clubs</a>

      <a href="../pages/reminders.php"
         class="block font-medium hover:text-blue-600 transition">Reminders</a>

      <a href="../pages/contactus.php"
         class="block font-medium hover:text-blue-600 transition">Contact Us</a>

      <a href="../pages/aboutus.php"
         class="block font-medium hover:text-blue-600 transition">About Us</a>

      <?php if(isset($_SESSION['email'])): ?>
        <?php $firstLetter = strtoupper($_SESSION['email'][0]); ?>

        <div class="flex items-center gap-3 mt-3">
          <div class="w-10 h-10 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center">
            <?= $firstLetter ?>
          </div>

          <a href="../pages/logout.php"
             class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
            Logout
          </a>
        </div>

      <?php else: ?>
        <a href="../pages/login.php"
           class="block text-center mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
          Login
        </a>
      <?php endif; ?>

    </nav>
  </div>

</header>

<!-- Push content below header -->
<div class="pt-20"></div>

<script>
  const menuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  const navLinks = document.querySelectorAll('.nav-link');

  if(menuButton){
    menuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  const currentPath = window.location.pathname.split("/").pop();
  navLinks.forEach(link => {
    const linkPath = link.getAttribute('href').split("/").pop();
    if (linkPath === currentPath || (currentPath === "" && linkPath === "index.php")) {
      link.classList.add('text-blue-600');
      link.classList.remove('text-gray-700');
    }
  });
</script>

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

<body class="bg-gray-50 text-gray-900">

<!-- ======================================
            HEADER NAVBAR
====================================== -->
<header class="fixed top-0 left-0 w-full z-50
               bg-white/30 backdrop-blur-2xl
               border-b border-white/20 shadow-sm">

  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">

    <!-- LOGO -->
    <div class="flex-shrink-0">
      <a href="../pages/index.php"
         class="flex items-center gap-3 transition-transform duration-200 hover:scale-105">
        <img class="h-12 w-auto drop-shadow-sm" src="../Images/apiit.png" alt="APIIT Logo">
      </a>
    </div>

    <!-- DESKTOP NAV -->
    <nav class="hidden md:flex gap-8 items-center font-medium">

      <a href="../pages/index.php"
         class="nav-link relative text-gray-800 hover:text-blue-600 transition
                after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Home
      </a>

      <a href="../pages/clubs.php"
         class="nav-link relative text-gray-800 hover:text-blue-600 transition
                after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Clubs
      </a>

      <a href="../pages/reminders.php"
         class="nav-link relative text-gray-800 hover:text-blue-600 transition
                after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0
                after:bg-blue-600 after:transition-all after:duration-300
                hover:after:w-full">
        Reminders
      </a>

      <!-- USER LOGIN / LOGOUT -->
      <?php if(isset($_SESSION['email'])): ?>
        <?php $firstLetter = strtoupper($_SESSION['email'][0]); ?>

        <div class="flex items-center gap-3 ml-4">
          <div class="w-10 h-10 bg-blue-600 text-white font-bold rounded-full 
                      flex items-center justify-center shadow-md">
            <?= $firstLetter ?>
          </div>

          <a href="../pages/logout.php"
             class="px-4 py-2 bg-red-600 text-white rounded-xl shadow-xl
                    hover:bg-red-700 transition font-semibold">
            Logout
          </a>
        </div>

      <?php else: ?>
        <a href="../pages/login.php"
           class="ml-4 px-5 py-2 bg-blue-600 text-white rounded-xl shadow-md
                  hover:bg-blue-700 transition font-semibold">
          Login
        </a>
      <?php endif; ?>

    </nav>

    <!-- MOBILE MENU BUTTON -->
    <button id="mobile-menu-button" class="md:hidden text-gray-800 hover:text-blue-600 transition">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

  </div>

  <!-- ======================================
               MOBILE MENU
  ====================================== -->
  <div id="mobile-menu"
       class="hidden md:hidden bg-white/80 backdrop-blur-xl border-b border-white/20 shadow-sm">

    <nav class="px-6 pt-4 pb-6 space-y-4 text-gray-900 font-medium">

      <a href="../pages/index.php"
         class="block text-lg hover:text-blue-600 transition nav-link">
        Home
      </a>

      <a href="../pages/clubs.php"
         class="block text-lg hover:text-blue-600 transition nav-link">
        Clubs
      </a>

      <a href="../pages/reminders.php"
         class="block text-lg hover:text-blue-600 transition nav-link">
        Reminders
      </a>

      <?php if(isset($_SESSION['email'])): ?>
        <?php $firstLetter = strtoupper($_SESSION['email'][0]); ?>

        <div class="flex items-center gap-4 mt-4">
          <div class="w-11 h-11 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center shadow">
            <?= $firstLetter ?>
          </div>

          <a href="../pages/logout.php"
             class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
            Logout
          </a>
        </div>

      <?php else: ?>
        <a href="../pages/login.php"
           class="block text-center mt-3 px-5 py-2 bg-blue-600 text-white 
                  rounded-lg shadow hover:bg-blue-700 transition font-semibold">
          Login
        </a>
      <?php endif; ?>

    </nav>
  </div>

</header>

<!-- Push content down -->
<div class="pt-24"></div>

<!-- ======================================
            SCRIPT
====================================== -->
<script>
  const menuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  const navLinks = document.querySelectorAll('.nav-link');

  if(menuButton){
    menuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Highlight active page
  const currentPath = window.location.pathname.split("/").pop();
  navLinks.forEach(link => {
    const linkPath = link.getAttribute('href').split("/").pop();

    if (linkPath === currentPath || 
        (currentPath === "" && linkPath === "index.php")) {

      link.classList.add('text-blue-600', 'font-semibold');
      link.classList.remove('text-gray-800');
    }
  });
</script>

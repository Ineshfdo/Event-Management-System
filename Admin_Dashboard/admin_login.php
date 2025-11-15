<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

// Hardcoded credentials
$admin_email = "ineshfernando643@gmail.com";
$admin_password = "200605";

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin_email;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
  <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

  <?php if(!empty($error)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <div>
      <label for="email" class="block mb-1 font-medium">Email</label>
      <input type="email" name="email" id="email" required
             class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
      <label for="password" class="block mb-1 font-medium">Password</label>
      <input type="password" name="password" id="password" required
             class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
        Login
    </button>
  </form>
</div>

</body>
</html>

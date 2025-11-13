<?php
session_start();
require_once('../includes/db_connection.php'); 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        // Store login session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Add login flag → used by index.php to show popup
        $_SESSION['just_logged_in'] = true;

        header("Location: index.php");
        exit;

    } else {
        $message = "Invalid email or password.";
    }
}

require_once('../includes/header.php');
?>

<br><br><br><br>
<div class="max-w-md mx-auto mt-20 p-8 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    <?php if ($message): ?>
        <p class="mb-4 text-red-500"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label class="block mb-2">Email</label>
        <input type="email" name="email" class="w-full border p-2 rounded mb-4" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded mb-4" required>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
        Don't have an account? 
        <a href="signup.php" class="text-blue-600">Sign Up</a>
    </p>
</div>

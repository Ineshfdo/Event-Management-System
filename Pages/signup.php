<?php
require_once('../includes/db_connection.php'); // Correct path to DB
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $hashedPassword]);
            $message = "Account created successfully! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate email
                $message = "Email already registered.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<?php require_once('../includes/header.php'); ?>
<br>
<div class="max-w-md mx-auto mt-20 p-8 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>
    <?php if($message): ?>
        <p class="mb-4 text-red-500"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
        <label class="block mb-2">Email</label>
        <input type="email" name="email" class="w-full border p-2 rounded mb-4" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded mb-4" required>

        <label class="block mb-2">Confirm Password</label>
        <input type="password" name="confirm_password" class="w-full border p-2 rounded mb-4" required>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Sign Up
        </button>
    </form>
    <p class="mt-4 text-center text-gray-600">Already have an account? <a href="login.php" class="text-blue-600">Login</a></p>
</div>



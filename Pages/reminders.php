<?php
// File: reminders.php
session_start();
include('../includes/db_connection.php'); // DB first

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ==========================
// 1️⃣ Delete reminders with past events
// ==========================
$delPast = $pdo->prepare("
    DELETE r 
    FROM reminders r
    INNER JOIN events e ON r.event_id = e.id
    WHERE r.user_id = ? AND e.event_date < NOW()
");
$delPast->execute([$user_id]);

// ==========================
// 2️⃣ Handle manual deletion BEFORE including header
// ==========================
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reminder_id = intval($_GET['delete']);

    // Ensure this reminder belongs to the logged-in user
    $check = $pdo->prepare("SELECT * FROM reminders WHERE id = ? AND user_id = ?");
    $check->execute([$reminder_id, $user_id]);

    if ($check->rowCount() > 0) {
        $del = $pdo->prepare("DELETE FROM reminders WHERE id = ? AND user_id = ?");
        $del->execute([$reminder_id, $user_id]);
        header("Location: reminders.php?deleted=success");
        exit;
    } else {
        header("Location: reminders.php?deleted=fail");
        exit;
    }
}

$title = "My Reminders";
include('../includes/header.php'); // Include AFTER handling deletion

// ==========================
// 3️⃣ Fetch all active reminders for the logged-in user
// ==========================
$query = "
SELECT r.id AS reminder_id, r.created_at AS reminder_added, e.* 
FROM reminders r
INNER JOIN events e ON r.event_id = e.id
WHERE r.user_id = ?
ORDER BY r.created_at DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$reminders = $stmt->fetchAll();
?>

<div class="max-w-5xl mx-auto mt-10 mb-16">
    <h1 class="text-3xl font-bold text-center mb-6">My Event Reminders</h1>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success'): ?>
        <p class="text-green-600 text-center mb-4">Reminder deleted successfully!</p>
    <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 'fail'): ?>
        <p class="text-red-600 text-center mb-4">Failed to delete reminder!</p>
    <?php endif; ?>

    <?php if (empty($reminders)): ?>
        <p class="text-center text-gray-600">You have no reminders yet.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($reminders as $event): ?>
                <div class="bg-white shadow-md p-4 rounded-lg hover:shadow-xl transition relative">
                    <a href="../events/event_view.php?id=<?= $event['id'] ?>">
                        <img src="../uploads/<?= $event['main_image'] ?>" class="h-48 w-full object-cover rounded-lg mb-3">
                        <h2 class="text-xl font-semibold text-gray-900 mb-1"><?= $event['title'] ?></h2>
                    </a>
                    <p class="text-gray-600 text-sm mb-2">Event Date: <?= $event['event_date'] ?></p>
                    
                    <!-- Delete Button -->
                    <a href="reminders.php?delete=<?= $event['reminder_id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this reminder?');"
                       class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                       Delete
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<br><br><br><br><br><br><br><br>
<?php include('../includes/footer.php'); ?>

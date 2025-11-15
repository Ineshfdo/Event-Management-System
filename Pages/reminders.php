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
// 2️⃣ Manual deletion before header
// ==========================
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reminder_id = intval($_GET['delete']);

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
include('../includes/header.php');

// ==========================
// 3️⃣ Fetch reminders
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

<!-- ==========================
       MAIN WRAPPER
========================== -->
<div class="max-w-6xl mx-auto mt-1 mb-20 px-6">

    <h1 class="text-4xl font-extrabold text-center mb-10 text-gray-900 tracking-tight">
        My Event Reminders
    </h1>

    <!-- STATUS MESSAGES -->
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'success'): ?>
        <p class="text-green-600 text-center mb-6 font-semibold">Reminder deleted successfully!</p>
    <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 'fail'): ?>
        <p class="text-red-600 text-center mb-6 font-semibold">Failed to delete reminder!</p>
    <?php endif; ?>

    <!-- NO REMINDERS -->
    <?php if (empty($reminders)): ?>
        <p class="text-center text-gray-600 text-lg">You have no reminders yet.</p>

    <?php else: ?>

    <!-- REMINDERS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php foreach ($reminders as $event): ?>
        <div class="relative bg-white/90 backdrop-blur-xl p-5 border border-gray-200 
                    rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 
                    transition-all cursor-pointer">

            <a href="event_view.php?id=<?= $event['id'] ?>">

                <img src="../uploads/<?= $event['main_image'] ?>"
                     class="h-48 w-full object-cover rounded-xl shadow mb-4">

                <h2 class="text-xl font-bold text-gray-900 mb-2 tracking-tight">
                    <?= htmlspecialchars($event['title']) ?>
                </h2>
            </a>

            <p class="text-gray-600 text-sm mb-2">
                <span class="font-semibold text-gray-900">Event Date:</span>
                <?= $event['event_date'] ?>
            </p>

            <!-- DELETE BUTTON -->
            <a href="reminders.php?delete=<?= $event['reminder_id'] ?>"
               onclick="return confirm('Are you sure you want to delete this reminder?');"
               class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1.5 
                      rounded-lg text-xs shadow-md hover:bg-red-700 
                      transition font-semibold">
               Delete
            </a>

        </div>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>

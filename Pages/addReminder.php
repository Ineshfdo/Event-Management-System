<?php
// File: addReminder.php
session_start();
$title = "Event Reminder";
include('../includes/header.php');
include('../includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['event_id'])) {
    echo "Invalid Event!";
    exit;
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Check if reminder already exists
$check = $pdo->prepare("SELECT * FROM reminders WHERE user_id = ? AND event_id = ?");
$check->execute([$user_id, $event_id]);

if ($check->rowCount() == 0) {
    // Insert reminder
    $stmt = $pdo->prepare("INSERT INTO reminders (user_id, event_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $event_id]);
}

// Fetch event details
$eventStmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$eventStmt->execute([$event_id]);
$event = $eventStmt->fetch();

if (!$event) {
    echo "Event not found!";
    exit;
}

// Optional: fetch linked club
$club = null;
if (!empty($event['club_id'])) {
    $clubStmt = $pdo->prepare("SELECT * FROM clubs WHERE id = ?");
    $clubStmt->execute([$event['club_id']]);
    $club = $clubStmt->fetch();
}
?>

<div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4 text-green-600">Reminder Added Successfully!</h2>

    <img src="../uploads/<?= $event['main_image'] ?>" class="w-full h-64 object-cover rounded-lg mb-5">
    <h1 class="text-3xl font-bold mb-2"><?= $event['title'] ?></h1>
    <p class="text-gray-600 text-sm mb-3">📅 Event Date: <?= $event['event_date'] ?></p>
    <p class="text-gray-800 text-lg mb-4"><?= nl2br($event['description']) ?></p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <?php if (!empty($event['extra_image_1'])): ?>
            <img src="../uploads/<?= $event['extra_image_1'] ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($event['extra_image_2'])): ?>
            <img src="../uploads/<?= $event['extra_image_2'] ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($event['extra_image_3'])): ?>
            <img src="../uploads/<?= $event['extra_image_3'] ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
    </div>

    <?php if ($club): ?>
        <p class="text-blue-600 font-semibold mb-4">
            🔗 Linked Club: 
            <a href="../clubs/club_view.php?id=<?= $club['id'] ?>"><?= $club['club_name'] ?></a>
        </p>
    <?php endif; ?>

    <a href="reminders.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
        View My Reminders
    </a>
    <a href="../Pages/index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ml-2">
        Back to Calendar
    </a>
</div>

<?php include('../includes/footer.php'); ?>

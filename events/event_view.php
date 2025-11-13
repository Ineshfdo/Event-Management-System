<?php
include('../includes/header.php');
include('../includes/db_connection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    echo "Invalid Event ID";
    exit;
}

$event_id = intval($_GET['id']);

// ===== Auto-delete events older than 30 days =====
$deleteOld = $pdo->prepare("DELETE FROM events WHERE event_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
$deleteOld->execute();

// ===== Fetch event details =====
$query = "SELECT * FROM events WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Event not found!";
    exit;
}

/* Fetch linked club */
$club = null;
if (!empty($event['club_id'])) {
    $clubQuery = "SELECT * FROM clubs WHERE id = ?";
    $clubStmt = $pdo->prepare($clubQuery);
    $clubStmt->execute([$event['club_id']]);
    $club = $clubStmt->fetch();
}

// Check if event is in the past
$eventPast = (strtotime($event['event_date']) < time());
?>

<div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
    <img src="../uploads/<?= htmlspecialchars($event['main_image']) ?>" class="w-full h-64 object-cover rounded-lg mb-5">
    <h1 class="text-3xl font-bold"><?= htmlspecialchars($event['title']) ?></h1>
<br>
    <!-- Event Date, Venue, Price & Ticket URL -->
    <p class="text-gray-600 text-sm mb-1">
        Event Date & Time: <?= $event['event_date'] ?>
    </p>
    <p class="text-gray-600 text-sm mb-1">
        Venue: <?= htmlspecialchars($event['venue']) ?>
    </p>
    <p class="text-gray-600 text-sm mb-1">
        Price: <?= !empty($event['price']) ? 'LKR ' . number_format($event['price'], 2) : 'Free' ?>
    </p>
    <?php if (!empty($event['ticket_url'])): ?>
        <p class="text-gray-600 text-sm mb-3">
            Place to Buy Tickets: 
            <a href="<?= htmlspecialchars($event['ticket_url']) ?>" target="_blank" class="text-blue-600 underline">
                Buy Tickets
            </a>
        </p>
    <?php endif; ?>

    <p class="text-gray-800 text-lg leading-relaxed mb-6"><?= nl2br(htmlspecialchars($event['description'])) ?></p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <?php if (!empty($event['extra_image_1'])): ?>
            <img src="../uploads/<?= htmlspecialchars($event['extra_image_1']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($event['extra_image_2'])): ?>
            <img src="../uploads/<?= htmlspecialchars($event['extra_image_2']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($event['extra_image_3'])): ?>
            <img src="../uploads/<?= htmlspecialchars($event['extra_image_3']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
    </div>

    <div class="flex gap-4 mt-8">
        <a href="../Pages/index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
           Go Back
        </a>

        <?php if ($eventPast): ?>
            <span class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed">
                Add Reminder (Event Passed)
            </span>
        <?php elseif (isset($_SESSION['user_id'])): ?>
            <a href="../Pages/addReminder.php?event_id=<?= $event_id ?>" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
               Add Reminder
            </a>
        <?php else: ?>
            <a href="../Pages/login.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
               Add Reminder
            </a>
        <?php endif; ?>

        <?php if ($club): ?>
            <a href="../clubs/club_view.php?id=<?= $club['id'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
               View Club
            </a>
        <?php endif; ?>
    </div>
</div>
<br><br><br>
<?php include('../includes/footer.php'); ?>

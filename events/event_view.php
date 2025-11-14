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


<!-- ========================
       EVENT VIEW CARD
======================== -->
<div class="max-w-4xl mx-auto bg-white/90 backdrop-blur-xl p-10 mt-16 rounded-3xl shadow-xl border border-gray-200">

    <!-- Main Image -->
    <img src="../uploads/<?= htmlspecialchars($event['main_image']) ?>" 
         class="w-full h-72 object-cover rounded-2xl shadow-lg mb-6" />

    <!-- Title -->
    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-4">
        <?= htmlspecialchars($event['title']) ?>
    </h1>

    <!-- Event Details -->
    <div class="space-y-2 text-gray-600 text-sm">
        <p><span class="font-semibold text-gray-900">Event Date & Time:</span> <?= $event['event_date'] ?></p>
        <p><span class="font-semibold text-gray-900">Venue:</span> <?= htmlspecialchars($event['venue']) ?></p>
        <p><span class="font-semibold text-gray-900">Price:</span> 
            <?= !empty($event['price']) ? 'LKR ' . number_format($event['price'], 2) : 'Free' ?>
        </p>

        <?php if (!empty($event['ticket_url'])): ?>
        <p>
            <span class="font-semibold text-gray-900">Buy Tickets:</span>
            <a href="<?= htmlspecialchars($event['ticket_url']) ?>" 
               target="_blank" 
               class="text-blue-600 underline hover:text-blue-700 transition">
                Click Here
            </a>
        </p>
        <?php endif; ?>
    </div>

    <!-- Description -->
    <p class="text-gray-800 text-lg leading-relaxed mt-6 mb-8 whitespace-pre-line">
        <?= nl2br(htmlspecialchars($event['description'])) ?>
    </p>

    <!-- Extra Images Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <?php if (!empty($event['extra_image_1'])): ?>
        <img src="../uploads/<?= htmlspecialchars($event['extra_image_1']) ?>" 
             class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition" />
        <?php endif; ?>

        <?php if (!empty($event['extra_image_2'])): ?>
        <img src="../uploads/<?= htmlspecialchars($event['extra_image_2']) ?>" 
             class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition" />
        <?php endif; ?>

        <?php if (!empty($event['extra_image_3'])): ?>
        <img src="../uploads/<?= htmlspecialchars($event['extra_image_3']) ?>" 
             class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition" />
        <?php endif; ?>
    </div>

    <!-- Buttons -->
    <div class="flex flex-wrap gap-4 mt-10">

        <!-- Go Back -->
        <a href="../Pages/index.php" 
           class="px-5 py-2.5 bg-gray-600 text-white rounded-xl shadow hover:bg-gray-700 transition">
           Go Back
        </a>

        <!-- Add Reminder -->
        <?php if ($eventPast): ?>
            <span class="px-5 py-2.5 bg-gray-400 text-white rounded-xl shadow cursor-not-allowed">
                Add Reminder (Event Passed)
            </span>

        <?php elseif (isset($_SESSION['user_id'])): ?>
            <a href="../Pages/addReminder.php?event_id=<?= $event_id ?>" 
               class="px-5 py-2.5 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition">
               Add Reminder
            </a>

        <?php else: ?>
            <a href="../Pages/login.php"
               class="px-5 py-2.5 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition">
               Add Reminder
            </a>
        <?php endif; ?>

        <!-- View Club -->
        <?php if ($club): ?>
        <a href="../clubs/club_view.php?id=<?= $club['id'] ?>" 
           class="px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
           View Club
        </a>
        <?php endif; ?>

    </div>

</div>

<br><br><br>

<?php include('../includes/footer.php'); ?>

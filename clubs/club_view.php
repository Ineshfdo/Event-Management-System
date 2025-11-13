<?php
session_start();

include('../includes/header.php');
include('../includes/db_connection.php');

// Validate club ID
if (!isset($_GET['id'])) {
    echo "Invalid club ID";
    exit;
}

$club_id = intval($_GET['id']);

// Fetch club
$query = "SELECT * FROM clubs WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$club_id]);
$club = $stmt->fetch();

if (!$club) {
    echo "Club not found";
    exit;
}

// Fetch upcoming events
$eventQuery = "SELECT * FROM events WHERE club_id = ?";
$eventStmt = $pdo->prepare($eventQuery);
$eventStmt->execute([$club_id]);
$events = $eventStmt->fetchAll();

// Fetch PAST EVENTS with extra images
$pastQuery = "SELECT * FROM past_events WHERE club_id = ? ORDER BY created_at DESC";
$pastStmt = $pdo->prepare($pastQuery);
$pastStmt->execute([$club_id]);
$past_events = $pastStmt->fetchAll();
?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg mt-10">

    <!-- Main Club Image -->
    <?php if (!empty($club['club_main_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($club['club_main_image']) ?>" 
             class="w-full h-60 object-cover rounded-lg mb-5">
    <?php endif; ?>

    <!-- Extra Club Images -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <?php if (!empty($club['club_extra_image_1'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_1']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($club['club_extra_image_2'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_2']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
        <?php if (!empty($club['club_extra_image_3'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_3']) ?>" class="rounded-lg h-40 w-full object-cover">
        <?php endif; ?>
    </div>

    <!-- Club Name & Description -->
    <h1 class="text-3xl font-bold mb-3"><?= htmlspecialchars($club['club_name']) ?></h1>
    <p class="text-gray-700 text-lg leading-relaxed mb-6">
        <?= nl2br(htmlspecialchars($club['club_description'])) ?>
    </p>

    

    <!-- Upcoming Events -->
    <div class="mb-10">
        <h2 class="text-xl font-semibold text-blue-600 mb-3">Upcoming Events</h2>

        <?php if ($events): ?>
            <?php foreach ($events as $event): ?>
                <p class="mb-1">
                    <a href="../events/event_view.php?id=<?= $event['id'] ?>" 
                       class="text-blue-700 hover:underline">
                        - <?= htmlspecialchars($event['title']) ?>
                    </a>
                </p>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600">No upcoming events for this club.</p>
        <?php endif; ?>
    </div>

    <!-- PAST EVENTS -->
    <div>
        <h2 class="text-xl font-semibold text-red-600 mb-4">Past Events</h2>

        <?php if ($past_events): ?>
            <div class="grid md:grid-cols-2 gap-6">

                <?php foreach ($past_events as $p): ?>
                    <div class="bg-gray-50 p-4 rounded-lg shadow hover:shadow-md transition">

                        <!-- Main Image -->
                        <?php if (!empty($p['main_image'])): ?>
                            <img src="../uploads/<?= $p['main_image'] ?>" class="w-full h-48 object-cover rounded mb-3">
                        <?php endif; ?>

                        <!-- Extra Images -->
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <?php if (!empty($p['extra_image_1'])): ?>
                                <img src="../uploads/<?= $p['extra_image_1'] ?>" class="h-20 w-full object-cover rounded">
                            <?php endif; ?>
                            
                            <?php if (!empty($p['extra_image_2'])): ?>
                                <img src="../uploads/<?= $p['extra_image_2'] ?>" class="h-20 w-full object-cover rounded">
                            <?php endif; ?>

                            <?php if (!empty($p['extra_image_3'])): ?>
                                <img src="../uploads/<?= $p['extra_image_3'] ?>" class="h-20 w-full object-cover rounded">
                            <?php endif; ?>
                        </div>

                        <!-- Event Title -->
                        <h3 class="text-lg font-bold mb-2"><?= htmlspecialchars($p['event_title']) ?></h3>

                        <!-- Event Description (15 words limit) -->
                        <?php
                            $words = explode(" ", $p['event_description']);
                            $short = implode(" ", array_slice($words, 0, 15));
                            $isLong = count($words) > 15;
                        ?>

                        <p class="text-gray-700 text-sm mb-3">
                            <span class="short-desc"><?= htmlspecialchars($short) ?><?= $isLong ? '...' : '' ?></span>
                            <?php if ($isLong): ?>
                                <span class="full-desc hidden"><?= nl2br(htmlspecialchars($p['event_description'])) ?></span>
                                <button class="toggleDesc text-blue-600 text-sm underline">Show More</button>
                            <?php endif; ?>
                        </p>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <p class="text-gray-600">No past events found.</p>
        <?php endif; ?>
<br>
        <!-- Club Contact Info -->
    <div class="bg-gray-100 p-4 rounded-lg mb-8">
        <p class="text-gray-800 font-semibold mb-2">Contact Information:</p>

        <?php if (!empty($club['contact_description_1'])): ?>
            <p class="text-gray-700 mb-1"><?= nl2br(htmlspecialchars($club['contact_description_1'])) ?></p>
        <?php endif; ?>

        <?php if (!empty($club['contact_number_1'])): ?>
            <p class="text-gray-700">Contact 1: <?= htmlspecialchars($club['contact_number_1']) ?></p>
        <?php endif; ?>

        <?php if (!empty($club['contact_number_2'])): ?>
            <p class="text-gray-700">Contact 2: <?= htmlspecialchars($club['contact_number_2']) ?></p>
        <?php endif; ?>
    </div>
    </div>

</div>

<script>
document.querySelectorAll(".toggleDesc").forEach(btn => {
    btn.addEventListener("click", () => {
        const parent = btn.parentElement;
        parent.querySelector(".short-desc").classList.toggle("hidden");
        parent.querySelector(".full-desc").classList.toggle("hidden");

        btn.textContent = btn.textContent === "Show More" ? "Show Less" : "Show More";
    });
});
</script>

<?php include('../includes/footer.php'); ?>

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

/* FETCH UPCOMING EVENTS */
$eventQuery = "
    SELECT * FROM events 
    WHERE club_id = ? AND event_date >= CURDATE()
    ORDER BY event_date ASC
";
$eventStmt = $pdo->prepare($eventQuery);
$eventStmt->execute([$club_id]);
$events = $eventStmt->fetchAll();

/* AUTO PAST EVENTS */
$autoPastQuery = "
    SELECT *, 'auto' AS source_type FROM events 
    WHERE club_id = ? AND event_date < CURDATE()
    ORDER BY event_date DESC
";
$autoPastStmt = $pdo->prepare($autoPastQuery);
$autoPastStmt->execute([$club_id]);
$auto_past_events = $autoPastStmt->fetchAll();

/* MANUAL PAST EVENTS */
$manualPastQuery = "
    SELECT *, 'manual' AS source_type FROM past_events 
    WHERE club_id = ?
    ORDER BY created_at DESC
";
$manualPastStmt = $pdo->prepare($manualPastQuery);
$manualPastStmt->execute([$club_id]);
$manual_past_events = $manualPastStmt->fetchAll();

/* MERGE BOTH */
$past_events = array_merge($auto_past_events, $manual_past_events);
?>

<!-- =======================
        MAIN CARD
======================= -->
<div class="max-w-5xl mx-auto bg-white/90 backdrop-blur-xl p-10 mt-16 rounded-3xl shadow-xl border border-gray-200">

    <!-- MAIN CLUB IMAGE -->
    <?php if (!empty($club['club_main_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($club['club_main_image']) ?>"
             class="w-full h-72 object-cover rounded-2xl shadow-lg mb-6">
    <?php endif; ?>

    <!-- EXTRA IMAGES GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        <?php if (!empty($club['club_extra_image_1'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_1']) ?>"
                 class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition">
        <?php endif; ?>

        <?php if (!empty($club['club_extra_image_2'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_2']) ?>"
                 class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition">
        <?php endif; ?>

        <?php if (!empty($club['club_extra_image_3'])): ?>
            <img src="../uploads/<?= htmlspecialchars($club['club_extra_image_3']) ?>"
                 class="rounded-xl h-40 w-full object-cover shadow-md hover:scale-[1.02] transition">
        <?php endif; ?>
    </div>

    <!-- CLUB NAME -->
    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-4">
        <?= htmlspecialchars($club['club_name']) ?>
    </h1>

    <!-- CLUB DESCRIPTION -->
    <p class="text-gray-700 text-lg leading-relaxed mb-10 whitespace-pre-line">
        <?= nl2br(htmlspecialchars($club['club_description'])) ?>
    </p>


    <!-- =======================
        UPCOMING EVENTS
    ======================== -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">Upcoming Events</h2>

        <?php if ($events): ?>
            <div class="space-y-2">
            <?php foreach ($events as $event): ?>
                <a href="event_view.php?id=<?= $event['id'] ?>"
                   class="block bg-blue-50 border border-blue-200 p-3 rounded-xl shadow-sm 
                          hover:bg-blue-100 transition text-blue-700 font-medium">
                    <?= htmlspecialchars($event['title']) ?>
                </a>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600 italic">No upcoming events for this club.</p>
        <?php endif; ?>
    </div>


    <!-- =======================
        PAST EVENTS
    ======================== -->
    <div>
        <h2 class="text-2xl font-semibold text-red-600 mb-5">Past Events</h2>

        <?php if ($past_events): ?>

            <div class="grid md:grid-cols-2 gap-8">

                <?php foreach ($past_events as $p): ?>
                <div class="bg-white/80 backdrop-blur-lg p-5 rounded-2xl shadow-md 
                            border border-gray-200 hover:shadow-xl transition-all">

                    <!-- MAIN IMAGE -->
                    <?php if (!empty($p['main_image'])): ?>
                        <img src="../uploads/<?= $p['main_image'] ?>"
                             class="w-full h-48 object-cover rounded-xl shadow mb-4">
                    <?php endif; ?>

                    <!-- EXTRA IMAGES -->
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <?php if (!empty($p['extra_image_1'])): ?>
                            <img src="../uploads/<?= $p['extra_image_1'] ?>" 
                                 class="h-20 w-full object-cover rounded shadow">
                        <?php endif; ?>

                        <?php if (!empty($p['extra_image_2'])): ?>
                            <img src="../uploads/<?= $p['extra_image_2'] ?>" 
                                 class="h-20 w-full object-cover rounded shadow">
                        <?php endif; ?>

                        <?php if (!empty($p['extra_image_3'])): ?>
                            <img src="../uploads/<?= $p['extra_image_3'] ?>" 
                                 class="h-20 w-full object-cover rounded shadow">
                        <?php endif; ?>
                    </div>

                    <!-- TITLE -->
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        <?= htmlspecialchars($p['title'] ?? $p['event_title']) ?>
                    </h3>

                    <!-- SHORT DESCRIPTION WITH SEE MORE -->
                    <?php
                        $desc = $p['description'] ?? $p['event_description'];
                        $words = explode(" ", $desc);
                        $short = implode(" ", array_slice($words, 0, 15));
                        $isLong = count($words) > 15;
                    ?>

                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        <span class="short-desc"><?= htmlspecialchars($short) ?><?= $isLong ? '...' : '' ?></span>

                        <?php if ($isLong): ?>
                            <span class="full-desc hidden"><?= nl2br(htmlspecialchars($desc)) ?></span>
                            <button class="toggleDesc text-blue-600 text-sm underline">Show More</button>
                        <?php endif; ?>
                    </p>

                    <!-- DATE TYPE -->
                    <p class="text-xs text-gray-500">
                        <?= $p['source_type'] == "auto"
                            ? "Event Date: " . htmlspecialchars($p['event_date'])
                            : "Added On: " . htmlspecialchars($p['created_at']) ?>
                    </p>

                </div>
                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <p class="text-gray-600 italic">No past events found.</p>
        <?php endif; ?>


        <!-- =======================
            CONTACT INFO
        ======================== -->
        <div class="bg-gray-100 rounded-2xl p-6 mt-12 border border-gray-300 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                Contact Information
            </h3>

            <?php if (!empty($club['contact_description_1'])): ?>
                <p class="text-gray-700 mb-2"><?= nl2br(htmlspecialchars($club['contact_description_1'])) ?></p>
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


<!-- SHOW MORE SCRIPT -->
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

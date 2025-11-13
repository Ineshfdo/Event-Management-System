<?php
// File: clubs.php
$title = "Clubs";
include('../includes/header.php');
include('../includes/db_connection.php');

/* ✅ Fetch all clubs with one linked event (if exists) */
$query = "SELECT clubs.*, events.title AS event_title 
          FROM clubs
          LEFT JOIN events ON events.club_id = clubs.id
          GROUP BY clubs.id
          ORDER BY clubs.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$clubs = $stmt->fetchAll();
?>

<div class="max-w-6xl mx-auto mt-10 mb-24">
    <h1 class="text-3xl font-bold text-center mb-6">All Clubs</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php foreach ($clubs as $club): ?>
        <div class="bg-white shadow-md p-4 rounded-lg hover:shadow-xl transition">
            <a href="../clubs/club_view.php?id=<?= $club['id'] ?>">
                <img src="../uploads/<?= $club['club_main_image'] ?>"
                     class="h-48 w-full object-cover rounded-lg mb-3">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">
                    <?= $club['club_name'] ?>
                </h2>
            </a>

            <p class="text-gray-600 text-sm mb-3">
                <?= $club['short_description'] ?>
            </p>
 
        </div>
        <?php endforeach; ?>

    </div>
</div>
<br><br><br><br><br><br><br>
<?php include('../includes/footer.php'); ?>

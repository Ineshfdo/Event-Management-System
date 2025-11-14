<?php
// File: clubs.php
$title = "Clubs";
include('../includes/header.php');
include('../includes/db_connection.php');

/* ----------------------------------------------------
   SEARCH + PAGINATION SETTINGS
---------------------------------------------------- */
$limit = 6; // Clubs per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$search_param = "%" . $search . "%";

/* ----------------------------------------------------
   COUNT TOTAL CLUBS FOR PAGINATION
---------------------------------------------------- */
$countQuery = "
    SELECT COUNT(DISTINCT clubs.id) AS total
    FROM clubs
    LEFT JOIN events ON events.club_id = clubs.id
    WHERE clubs.club_name LIKE ? 
       OR events.title LIKE ?
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute([$search_param, $search_param]);
$totalClubs = $countStmt->fetchColumn();

$totalPages = ceil($totalClubs / $limit);

/* ----------------------------------------------------
   FETCH CLUBS WITH SEARCH + PAGINATION
---------------------------------------------------- */
$query = "
    SELECT clubs.*, events.title AS event_title
    FROM clubs
    LEFT JOIN events ON events.club_id = clubs.id
    WHERE clubs.club_name LIKE ?
       OR events.title LIKE ?
    GROUP BY clubs.id
    ORDER BY clubs.id DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute([$search_param, $search_param]);
$clubs = $stmt->fetchAll();
?>

<div class="max-w-6xl mx-auto mt-10 mb-24">
    <h1 class="text-3xl font-bold text-center mb-6">All Clubs</h1>

    <!-- 🔍 Search Bar -->
    <form method="GET" class="mb-6 flex justify-center">
        <input 
            type="text" 
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Search clubs or events..."
            class="border px-4 py-2 rounded-l-lg w-1/2"
        >
        <button class="bg-blue-600 text-white px-4 py-2 rounded-r-lg">
            Search
        </button>
    </form>


    <!-- CLUB GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($clubs as $club): ?>
        <div class="bg-white shadow-md p-4 rounded-lg hover:shadow-xl transition">

            <a href="../clubs/club_view.php?id=<?= $club['id'] ?>">
                <img src="../uploads/<?= $club['club_main_image'] ?>"
                     class="h-48 w-full object-cover rounded-lg mb-3"
                     alt="<?= $club['club_name'] ?>">

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

    <!-- PAGINATION -->
    <div class="mt-10 flex justify-center space-x-2">

        <!-- Prev -->
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>" 
               class="px-4 py-2 bg-gray-300 rounded">Prev</a>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
               class="px-4 py-2 rounded 
                      <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>" 
               class="px-4 py-2 bg-gray-300 rounded">Next</a>
        <?php endif; ?>
    </div>

</div>

<br><br><br><br><br><br><br>

<?php include('../includes/footer.php'); ?>

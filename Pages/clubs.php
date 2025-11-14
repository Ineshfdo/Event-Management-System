<?php
// File: clubs.php
$title = "Clubs";
include('../includes/header.php');
include('../includes/db_connection.php');

/* ----------------------------------------------------
   SEARCH + PAGINATION SETTINGS
---------------------------------------------------- */
$limit = 6;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$search_param = "%" . $search . "%";

/* ----------------------------------------------------
   COUNT TOTAL CLUBS
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
   FETCH CLUBS
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

<!-- =======================
       MAIN WRAPPER
======================= -->
<div class="max-w-8xl mx-auto mt-1 mb-28 px-4">

    <h1 class="text-5xl font-extrabold text-center mb-10 text-gray-900 tracking-tight">
        All Clubs
    </h1>

    <!-- 🔍 Search Bar -->
    <form method="GET" 
          class="mb-10 flex justify-center">

        <div class="flex w-full max-w-2xl shadow-md rounded-2xl overflow-hidden bg-white/80 backdrop-blur-lg border border-gray-200">
            <input 
                type="text"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Search clubs or events..."
                class="px-5 py-3 w-full focus:outline-none text-gray-800 text-sm"
            >

            <button class="px-6 bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                Search
            </button>
        </div>

    </form>

    <!-- CLUB GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php foreach ($clubs as $club): ?>
        <div class="relative bg-white/90 backdrop-blur-lg p-5 border border-gray-200 shadow-lg rounded-2xl 
                    hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer">

            <a href="../clubs/club_view.php?id=<?= $club['id'] ?>">

                <!-- Club Image -->
                <img src="../uploads/<?= $club['club_main_image'] ?>"
                     alt="<?= $club['club_name'] ?>"
                     class="h-48 w-full object-cover rounded-xl shadow-md mb-4">

                <!-- Club Name -->
                <h2 class="text-xl font-bold text-gray-900 mb-2 tracking-tight">
                    <?= htmlspecialchars($club['club_name']) ?>
                </h2>

                <!-- Description -->
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?= htmlspecialchars($club['short_description']) ?>
                </p>

            </a>

        </div>
        <?php endforeach; ?>

    </div>

    <!-- PAGINATION -->
    <div class="mt-12 flex justify-center gap-2">

        <!-- Prev -->
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>" 
               class="px-4 py-2 bg-gray-200 rounded-xl text-sm hover:bg-gray-300 transition">
               Prev
            </a>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
               class="px-4 py-2 text-sm rounded-xl border 
               <?= $i == $page 
                    ? 'bg-blue-600 text-white border-blue-600 shadow-md' 
                    : 'bg-white/70 border-gray-300 hover:bg-gray-100' ?> transition">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>" 
               class="px-4 py-2 bg-gray-200 rounded-xl text-sm hover:bg-gray-300 transition">
               Next
            </a>
        <?php endif; ?>

    </div>

</div>

<?php include('../includes/footer.php'); ?>

<?php
session_start();
$title = "Home";
include('../includes/header.php');
include('../includes/db_connection.php');

// =====================
// Reminder popup logic
// =====================
$showPopup = false;
$reminderList = [];

if (isset($_SESSION['user_id']) && isset($_SESSION['just_logged_in'])) {

    unset($_SESSION['just_logged_in']); // show only once
    $user_id = $_SESSION['user_id'];

    $reminderQuery = "
        SELECT e.title, e.event_date
        FROM reminders r
        INNER JOIN events e ON r.event_id = e.id
        WHERE r.user_id = ? AND e.event_date >= NOW()
        ORDER BY e.event_date ASC
    ";

    $stmt = $pdo->prepare($reminderQuery);
    $stmt->execute([$user_id]);
    $reminderList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($reminderList)) {
        $showPopup = true;
    }
}

// =====================
// Delete events older than 30 days
// =====================
$deleteStmt = $pdo->prepare("DELETE FROM events WHERE event_date < NOW() - INTERVAL 30 DAY");
$deleteStmt->execute();

// =====================
// Fetch events
// =====================
$query = "SELECT id, title, event_date FROM events ORDER BY event_date ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =====================
// Top clubs
// =====================
$clubQuery = "SELECT clubs.*, events.title AS event_title
              FROM clubs
              LEFT JOIN events ON events.club_id = clubs.id
              GROUP BY clubs.id
              ORDER BY clubs.id DESC
              LIMIT 4";
$clubStmt = $pdo->prepare($clubQuery);
$clubStmt->execute();
$clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- =========================
     REMINDER POPUP
========================= -->
<?php if ($showPopup): ?>
<div id="reminderPopup" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-2xl border border-gray-200 max-w-md w-full animate-[fadeIn_0.3s_ease]">
        <h2 class="text-2xl font-bold mb-4 text-gray-900">🔔 Upcoming Reminders</h2>

        <ul class="space-y-3 text-gray-700 mb-4">
            <?php foreach ($reminderList as $r): ?>
                <li class="p-3 bg-gray-100 rounded-lg shadow-sm">
                    <strong><?= htmlspecialchars($r['title']) ?></strong><br>
                    <span class="text-sm text-gray-500">Date: <?= $r['event_date'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <button onclick="document.getElementById('reminderPopup').remove();"
                class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition font-semibold">
            OK
        </button>
    </div>
</div>
<?php endif; ?>

<script>
const events = <?php echo json_encode($events); ?>;
</script>

<!-- =========================
     TITLE
========================= -->
<h1 class="text-center text-5xl md:text-5xl font-extrabold tracking-tight mt-2 text-gray-900 drop-shadow-sm">
  Event Horizon
</h1>

<!-- =========================
     CALENDAR
========================= -->
<div class="max-w-4xl mx-auto mt-12 bg-white/90 backdrop-blur-md p-8 rounded-3xl shadow-xl border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <button id="prevMonth"
            class="p-3 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 active:scale-95 transition">
            &lt;
        </button>

        <h2 id="monthYear" class="text-2xl font-bold text-gray-900"></h2>

        <button id="nextMonth"
            class="p-3 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 active:scale-95 transition">
            &gt;
        </button>
    </div>

    <div class="grid grid-cols-7 mb-4 text-center font-semibold text-gray-700 tracking-wide">
        <div>Sun</div> <div>Mon</div> <div>Tue</div>
        <div>Wed</div> <div>Thu</div> <div>Fri</div> <div>Sat</div>
    </div>

    <div id="calendarDays" class="grid grid-cols-7 gap-3 text-center text-sm"></div>
</div>

<script>
const calendarDays = document.getElementById("calendarDays");
const monthYear = document.getElementById("monthYear");
let currentDate = new Date();

function renderCalendar() {
    const month = currentDate.getMonth();
    const year = currentDate.getFullYear();

    monthYear.textContent = currentDate.toLocaleString("default", { month: "long", year: "numeric" });
    calendarDays.innerHTML = "";

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;

    for (let i = 0; i < firstDay; i++) {
        calendarDays.innerHTML += `<div></div>`;
    }

    for (let day = 1; day <= lastDate; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const isToday = dateStr === todayStr;

        const todaysEvents = events.filter(e => e.event_date.startsWith(dateStr));

        let dayClass = `p-3 rounded-xl transition duration-200 shadow-sm 
                        ${isToday 
                            ? "bg-yellow-100 border-2 border-yellow-400 shadow-lg text-yellow-900" 
                            : "hover:bg-blue-100/60 cursor-pointer bg-white/70 backdrop-blur-sm border"}`;

        if (todaysEvents.length > 0) {
            let eventHTML = todaysEvents.map(e => {
                const time = new Date(e.event_date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                return `<p class="text-xs bg-blue-50 border border-blue-200 p-2 rounded-lg mb-1 shadow hover:bg-blue-100 cursor-pointer transition"
                          onclick="window.location.href='event_view.php?id=${e.id}'">
                          ${e.title} <span class="text-gray-500">(${time})</span>
                        </p>`;
            }).join('');
            
            calendarDays.innerHTML += `
                <div class="${dayClass}">
                    <div class="font-semibold mb-2 ${isToday ? 'text-yellow-700' : ''}">${day}</div>
                    ${eventHTML}
                </div>`;
        } else {
            calendarDays.innerHTML += `
                <div class="${dayClass}">
                    <div class="font-semibold ${isToday ? 'text-yellow-700' : ''}">${day}</div>
                </div>`;
        }
    }
}

document.getElementById("prevMonth").onclick = () => { 
    currentDate.setMonth(currentDate.getMonth() - 1); 
    renderCalendar(); 
};
document.getElementById("nextMonth").onclick = () => { 
    currentDate.setMonth(currentDate.getMonth() + 1); 
    renderCalendar(); 
};

renderCalendar();
</script>

<!-- =========================
     TOP CLUBS (Redesigned)
========================= -->
<div class="max-w-6xl mx-auto mt-24">
    <h2 class="text-4xl font-extrabold mb-12 text-center text-gray-900 tracking-tight drop-shadow-sm">
        Latest Clubs
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <?php foreach ($clubs as $club): ?>
        <?php 
            $shortDesc = !empty($club['short_description']) 
                         ? $club['short_description'] 
                         : $club['club_description'];
        ?>

        <div onclick="window.location.href='club_view.php?id=<?= $club['id'] ?>'"
            class="relative bg-white/80 backdrop-blur-md rounded-3xl p-6 shadow-lg border border-gray-200 
                   transition-all duration-300 cursor-pointer 
                   hover:shadow-2xl hover:-translate-y-2 overflow-hidden">

            <!-- Soft glow background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100/40 to-indigo-100/40 opacity-60"></div>

            <!-- Inner content -->
            <div class="relative z-10">
                <img src="../uploads/<?= htmlspecialchars($club['club_main_image']) ?>"
                    class="h-56 w-full object-cover rounded-2xl shadow-md mb-5 transition-all duration-300 hover:scale-[1.02]">

                <h3 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">
                    <?= htmlspecialchars($club['club_name']) ?>
                </h3>

                <p class="text-gray-700 text-sm leading-relaxed mt-1">
                    <?= htmlspecialchars($shortDesc) ?>
                </p>
            </div>

            <!-- Glow ring on hover -->
            <div class="absolute -inset-px rounded-3xl bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 
                        group-hover:opacity-20 blur-xl transition duration-500 pointer-events-none"></div>

        </div>
        <?php endforeach; ?>
    </div>
</div>


<br><br><br>
<?php include('../includes/footer.php'); ?>

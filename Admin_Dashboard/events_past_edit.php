<?php
session_start();

// Admin Authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once('../includes/db_connection.php');

// Validate ID
if (!isset($_GET['id'])) {
    header("Location: events_past_manage.php");
    exit;
}

$event_id = intval($_GET['id']);

// Fetch past event
$stmt = $pdo->prepare("SELECT * FROM past_events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: events_past_manage.php");
    exit;
}

// Fetch clubs
$clubs = $pdo->query("SELECT id, club_name FROM clubs")->fetchAll(PDO::FETCH_ASSOC);

$uploadPath = "../uploads/";

// File upload function
function uploadImage($input, $current, $uploadPath) {
    if (!empty($_FILES[$input]["name"])) {
        $fileName = time() . "_" . basename($_FILES[$input]["name"]);
        move_uploaded_file($_FILES[$input]["tmp_name"], $uploadPath . $fileName);
        return $fileName;
    }
    return $current;
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $club_id = $_POST['club_id'];

    $main_image = uploadImage("main_image", $event['main_image'], $uploadPath);
    $img1 = uploadImage("image1", $event['extra_image_1'], $uploadPath);
    $img2 = uploadImage("image2", $event['extra_image_2'], $uploadPath);
    $img3 = uploadImage("image3", $event['extra_image_3'], $uploadPath);

    $stmt = $pdo->prepare("UPDATE past_events 
        SET event_title=?, event_description=?, main_image=?, extra_image_1=?, extra_image_2=?, extra_image_3=?, club_id=?
        WHERE id=?");

    $stmt->execute([$title, $description, $main_image, $img1, $img2, $img3, $club_id, $event_id]);

    header("Location: events_past_manage.php?updated=success");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Past Event</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<aside class="hidden md:flex flex-col w-64 p-6 bg-black text-white">
    <h2 class="text-xl font-bold mb-8">EventHorizan Admin</h2>

    <nav class="flex flex-col gap-4">
        <a href="dashboard.php" class="p-2 rounded-lg hover:bg-gray-800">Dashboard</a>
        <a href="club_add.php" class="p-2 rounded-lg hover:bg-gray-800">Add Club</a>
        <a href="manage_clubs.php" class="p-2 rounded-lg hover:bg-gray-800">Manage Clubs</a>

        <a href="event_add.php" class="p-2 rounded-lg hover:bg-gray-800">Add Event</a>

        <a href="events_past_add.php" class="p-2 rounded-lg hover:bg-gray-800">Add Past Event</a>

        <a href="manage_events.php" class="p-2 rounded-lg hover:bg-gray-800">Manage Events</a>

        <a href="events_past_manage.php" class="p-2 bg-gray-800 rounded-lg">Manage Past Events</a>

        <a href="admin_logout.php" class="p-2 rounded-lg hover:bg-gray-800 mt-2">Logout</a>
    </nav>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 p-10">
    <h1 class="text-2xl font-semibold mb-6">Edit Past Event</h1>

    <div class="bg-white p-8 rounded-xl shadow max-w-3xl mx-auto">

        <form method="POST" enctype="multipart/form-data" class="space-y-4">

            <div>
                <label class="font-medium">Event Title</label>
                <input type="text" name="title" 
                       value="<?= htmlspecialchars($event['event_title']) ?>" 
                       class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="font-medium">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border p-2 rounded"><?= htmlspecialchars($event['event_description']) ?></textarea>
            </div>

            <div>
                <label class="font-medium">Main Image</label>
                <?php if($event['main_image']): ?>
                    <img src="../uploads/<?= $event['main_image'] ?>" class="h-20 mb-2 rounded">
                <?php endif; ?>
                <input type="file" name="main_image" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="font-medium">Extra Images</label>

                <div class="flex gap-3 mb-2">
                    <?php if($event['extra_image_1']): ?>
                        <img src="../uploads/<?= $event['extra_image_1'] ?>" class="h-16 w-16 rounded">
                    <?php endif; ?>

                    <?php if($event['extra_image_2']): ?>
                        <img src="../uploads/<?= $event['extra_image_2'] ?>" class="h-16 w-16 rounded">
                    <?php endif; ?>

                    <?php if($event['extra_image_3']): ?>
                        <img src="../uploads/<?= $event['extra_image_3'] ?>" class="h-16 w-16 rounded">
                    <?php endif; ?>
                </div>

                <input type="file" name="image1" class="w-full mb-1 border p-2 rounded">
                <input type="file" name="image2" class="w-full mb-1 border p-2 rounded">
                <input type="file" name="image3" class="w-full mb-1 border p-2 rounded">
            </div>

            <div>
                <label class="font-medium">Select Club</label>
                <select name="club_id" class="w-full border p-2 rounded" required>
                    <?php foreach ($clubs as $club): ?>
                        <option value="<?= $club['id'] ?>" 
                            <?= $event['club_id'] == $club['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($club['club_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700">
                Update Past Event
            </button>

        </form>
    </div>
</main>

</body>
</html>

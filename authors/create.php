<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $country = trim($_POST['country']);

    $stmt = $pdo->prepare("
        INSERT INTO authors (name, country)
        VALUES (?, ?)
    ");

    $stmt->execute([
        $name,
        $country
    ]);

    $message = "Author added successfully!";
}

$pageTitle = "Add Author";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<main class="container my-4" style="max-width:720px;">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">✍ Add Author</h3>
        </div>

        <div class="card-body">

            <?php if(!empty($message)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Author Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text"
                           name="country"
                           class="form-control">
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    💾 Save Author
                </button>

                <a href="index.php"
                   class="btn btn-secondary">
                    ← Back
                </a>

            </form>

        </div>
    </div>

</main>

<?php require_once "../includes/footer.php"; ?>
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
    $description = trim($_POST['description']);

    $stmt = $pdo->prepare("
        INSERT INTO categories
        (name, description)
        VALUES (?, ?)
    ");

    $stmt->execute([
        $name,
        $description
    ]);

    $message = "Category added successfully!";
}

$pageTitle = "Add Category";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">📂 Add Category</h3>
        </div>

        <div class="card-body">

            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Category Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              class="form-control"
                              rows="4"></textarea>
                </div>

                <button type="submit"
                        class="btn btn-primary">
                    💾 Save Category
                </button>

                <a href="index.php"
                   class="btn btn-secondary">
                    ← Back
                </a>

            </form>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
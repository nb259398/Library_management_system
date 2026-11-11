<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];
    $total_copies = $_POST['total_copies'];
    $available_copies = $_POST['available_copies'];

    $stmt = $pdo->prepare("
        INSERT INTO books
        (isbn, title, author_id, category_id, publisher,
        year_published, total_copies, available_copies)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $isbn,
        $title,
        $author_id,
        $category_id,
        $publisher,
        $year_published,
        $total_copies,
        $available_copies
    ]);

    $message = "Book added successfully!";
}

$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Add Book";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">📚 Add New Book</h3>
        </div>

        <div class="card-body">

            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text"
                           name="isbn"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>

                    <select name="author_id"
                            class="form-select"
                            required>

                        <?php foreach($authors as $author): ?>
                            <option value="<?= $author['author_id']; ?>">
                                <?= htmlspecialchars($author['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>

                    <select name="category_id"
                            class="form-select"
                            required>

                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['category_id']; ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Publisher</label>
                    <input type="text"
                           name="publisher"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Year Published</label>
                    <input type="number"
                           name="year_published"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Copies</label>
                    <input type="number"
                           name="total_copies"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Available Copies</label>
                    <input type="number"
                           name="available_copies"
                           class="form-control"
                           required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        💾 Save Book
                    </button>

                    <a href="index.php"
                       class="btn btn-secondary">
                        ← Back
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
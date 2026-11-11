<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$id = $_GET['id'];

$message = "";

// Fetch the current book
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch authors and categories
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];
    $total_copies = $_POST['total_copies'];
    $available_copies = $_POST['available_copies'];

    $update = $pdo->prepare("
        UPDATE books
        SET
            isbn=?,
            title=?,
            author_id=?,
            category_id=?,
            publisher=?,
            year_published=?,
            total_copies=?,
            available_copies=?
        WHERE book_id=?
    ");

    $update->execute([
        $isbn,
        $title,
        $author_id,
        $category_id,
        $publisher,
        $year_published,
        $total_copies,
        $available_copies,
        $id
    ]);

    $message = "Book updated successfully!";

    // Refresh data after update
    $stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php
$pageTitle = "Edit Book";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="card shadow border-0">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">✏️ Edit Book</h3>
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
                           value="<?= htmlspecialchars($book['isbn']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="<?= htmlspecialchars($book['title']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author</label>

                    <select name="author_id" class="form-select">

                        <?php foreach($authors as $author): ?>
                            <option value="<?= $author['author_id']; ?>"
                                <?= ($author['author_id'] == $book['author_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($author['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>

                    <select name="category_id" class="form-select">

                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['category_id']; ?>"
                                <?= ($category['category_id'] == $book['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Publisher</label>
                    <input type="text"
                           name="publisher"
                           class="form-control"
                           value="<?= htmlspecialchars($book['publisher']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Year Published</label>
                    <input type="number"
                           name="year_published"
                           class="form-control"
                           value="<?= htmlspecialchars($book['year_published']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Copies</label>
                    <input type="number"
                           name="total_copies"
                           class="form-control"
                           value="<?= htmlspecialchars($book['total_copies']) ?>">
            
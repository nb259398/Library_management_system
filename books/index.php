<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$pageTitle = "Books";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">📚 Books Management</h1>

        <a href="create.php" class="btn btn-primary">
            ➕ Add New Book
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Total Copies</th>
                            <th>Available Copies</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($books)): ?>

                        <tr>
                            <td colspan="6" class="text-center">
                                No books found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($books as $book): ?>

                            <tr>
                                <td><?= htmlspecialchars($book['book_id']) ?></td>

                                <td><?= htmlspecialchars($book['isbn']) ?></td>

                                <td><?= htmlspecialchars($book['title']) ?></td>

                                <td><?= htmlspecialchars($book['total_copies']) ?></td>

                                <td><?= htmlspecialchars($book['available_copies']) ?></td>

                                <td>
                                    <a href="edit.php?id=<?= $book['book_id'] ?>"
                                       class="btn btn-warning btn-sm">
                                        ✏ Edit
                                    </a>

                                    <a href="delete.php?id=<?= $book['book_id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this book?')">
                                        🗑 Delete
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
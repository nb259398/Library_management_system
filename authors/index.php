<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$pageTitle = "Authors";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

$stmt = $pdo->query("
    SELECT
        a.author_id,
        a.name,
        a.country,
        COUNT(b.book_id) AS book_count
    FROM authors a
    LEFT JOIN books b
        ON a.author_id = b.author_id
    GROUP BY a.author_id, a.name, a.country
    ORDER BY a.name
");

$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">✍ Authors Management</h1>

        <a href="create.php" class="btn btn-primary">
            ➕ Add Author
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Country</th>
                            <th>Books</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($authors)): ?>

                        <tr>
                            <td colspan="4" class="text-center">
                                No authors found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($authors as $author): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($author['author_id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($author['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($author['country']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($author['book_count']) ?>
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
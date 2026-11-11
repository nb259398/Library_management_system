<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$pageTitle = "Categories";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

$stmt = $pdo->query("
    SELECT
        c.category_id,
        c.name,
        c.description,
        COUNT(b.book_id) AS book_count
    FROM categories c
    LEFT JOIN books b
        ON c.category_id = b.category_id
    GROUP BY c.category_id, c.name, c.description
    ORDER BY c.name
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">📂 Categories Management</h1>

        <a href="create.php" class="btn btn-primary">
            ➕ Add Category
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
                            <th>Description</th>
                            <th>Books</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($categories)): ?>

                        <tr>
                            <td colspan="4" class="text-center">
                                No categories found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($categories as $category): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($category['category_id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($category['description']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($category['book_count']) ?>
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
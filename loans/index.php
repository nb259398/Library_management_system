<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$stmt = $pdo->query("
SELECT
    loans.loan_id,
    books.title,
    members.full_name,
    loans.date_issued,
    loans.due_date,
    loans.date_returned,
    loans.status
FROM loans
JOIN books ON loans.book_id = books.book_id
JOIN members ON loans.member_id = members.member_id
");

$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Loans";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">📚 Loans Management</h1>

        <a href="issue.php" class="btn btn-primary">
            ➕ Issue Book
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Date Issued</th>
                            <th>Due Date</th>
                            <th>Date Returned</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($loans)): ?>

                        <tr>
                            <td colspan="8" class="text-center">
                                No loans found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach($loans as $loan): ?>

                        <tr>

                            <td><?= htmlspecialchars($loan['loan_id']) ?></td>

                            <td><?= htmlspecialchars($loan['title']) ?></td>

                            <td><?= htmlspecialchars($loan['full_name']) ?></td>

                            <td><?= htmlspecialchars($loan['date_issued']) ?></td>

                            <td><?= htmlspecialchars($loan['due_date']) ?></td>

                            <td>
                                <?= $loan['date_returned'] ? htmlspecialchars($loan['date_returned']) : '-' ?>
                            </td>

                            <td>

                                <?php if($loan['status'] == 'Borrowed'): ?>

                                    <span class="badge bg-warning text-dark">
                                        Borrowed
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-success">
                                        Returned
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if($loan['status'] == 'Borrowed'): ?>

                                    <a href="return.php?id=<?= $loan['loan_id']; ?>"
                                       class="btn btn-success btn-sm">
                                        Return Book
                                    </a>

                                <?php else: ?>

                                    <span class="text-success fw-bold">
                                        Returned
                                    </span>

                                <?php endif; ?>

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

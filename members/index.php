<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$stmt = $pdo->query("SELECT * FROM members");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Members";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">👥 Members Management</h1>

        <a href="create.php" class="btn btn-success">
            ➕ Add New Member
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Member Number</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($members)): ?>

                        <tr>
                            <td colspan="8" class="text-center">
                                No members found.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach($members as $member): ?>

                        <tr>
                            <td><?= htmlspecialchars($member['member_id']) ?></td>

                            <td><?= htmlspecialchars($member['member_number']) ?></td>

                            <td><?= htmlspecialchars($member['full_name']) ?></td>

                            <td><?= htmlspecialchars($member['email']) ?></td>

                            <td><?= htmlspecialchars($member['phone']) ?></td>

                            <td>
                                <?php if ($member['status'] == 'Active'): ?>
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <?= htmlspecialchars($member['status']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($member['date_joined']) ?></td>

                            <td>
                                <a href="edit.php?id=<?= $member['member_id'] ?>"
                                   class="btn btn-warning btn-sm">
                                    ✏ Edit
                                </a>

                                <a href="delete.php?id=<?= $member['member_id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this member?')">
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
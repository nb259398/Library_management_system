<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $member_number = $_POST['member_number'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $date_joined = $_POST['date_joined'];

    $stmt = $pdo->prepare("
        INSERT INTO members
        (member_number, full_name, email, phone, status, date_joined)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $member_number,
        $full_name,
        $email,
        $phone,
        $status,
        $date_joined
    ]);

    $message = "Member added successfully!";
}
?>

<?php
$pageTitle = "Add Member";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">👥 Add New Member</h3>
        </div>

        <div class="card-body">

            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Member Number</label>
                    <input type="text"
                           name="member_number"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text"
                           name="full_name"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date Joined</label>
                    <input type="date"
                           name="date_joined"
                           class="form-control"
                           required>
                </div>

                <div class="mt-4">

                    <button type="submit" class="btn btn-success">
                        💾 Save Member
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        ← Back
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
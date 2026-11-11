<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$username = $_SESSION['username'] ?? 'Admin';

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $username;
}

// Statistics
$totalBooks = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn() ?? 0;

$totalMembers = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn() ?? 0;

$borrowedBooks = $pdo->query("
    SELECT COUNT(*)
    FROM loans
    WHERE status='Borrowed'
")->fetchColumn() ?? 0;

$returnedBooks = $pdo->query("
    SELECT COUNT(*)
    FROM loans
    WHERE status='Returned'
")->fetchColumn() ?? 0;

$suspendedMembers = $pdo->query("
    SELECT COUNT(*)
    FROM members
    WHERE status='Suspended'
")->fetchColumn() ?? 0;

$availableBooks = $pdo->query("
    SELECT COALESCE(SUM(available_copies),0)
    FROM books
")->fetchColumn();
?>

<?php
$pageTitle = "Dashboard";
require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <h1 class="mb-4">📚 Library Management System Dashboard</h1>

    <p class="lead">
        Welcome,
        <strong><?= htmlspecialchars($username) ?></strong>
    </p>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h5>Total Books</h5>
                    <h1><?= $totalBooks ?></h1>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h5>Total Members</h5>
                    <h1><?= $totalMembers ?></h1>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-white shadow">
                <div class="card-body text-center">
                    <h5>Books On Loan</h5>
                    <h1><?= $borrowedBooks ?></h1>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body text-center">
                    <h5>Returned Books</h5>
                    <h1><?= $returnedBooks ?></h1>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white shadow">
                <div class="card-body text-center">
                    <h5>Suspended Members</h5>
                    <h1><?= $suspendedMembers ?></h1>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-body text-center">
                    <h5>Available Copies</h5>
                    <h1><?= $availableBooks ?></h1>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-5">

        <a href="../books/index.php" class="btn btn-primary">
            📖 Manage Books
        </a>

        <a href="../members/index.php" class="btn btn-success">
            👥 Manage Members
        </a>

        <a href="../loans/index.php" class="btn btn-warning">
            🔄 Manage Loans
        </a>

        <a href="../auth/logout.php" class="btn btn-danger">
            🚪 Logout
        </a>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

// Check if loan ID exists
if (!isset($_GET['id'])) {

    header("Location: index.php");
    exit();
}

$loan_id = $_GET['id'];

// Get loan details
$stmt = $pdo->prepare("
    SELECT *
    FROM loans
    WHERE loan_id = ?
");

$stmt->execute([$loan_id]);

$loan = $stmt->fetch(PDO::FETCH_ASSOC);

// Loan not found
if (!$loan) {

    die("Loan not found.");
}

// Only return borrowed books
if ($loan['status'] == 'Borrowed') {

    try {

        // Start transaction
        $pdo->beginTransaction();

        // Update loan record
        $updateLoan = $pdo->prepare("
            UPDATE loans
            SET
                status = 'Returned',
                date_returned = CURDATE()
            WHERE loan_id = ?
        ");

        $updateLoan->execute([$loan_id]);

        // Increase available copies
        $updateBook = $pdo->prepare("
            UPDATE books
            SET available_copies = available_copies + 1
            WHERE book_id = ?
        ");

        $updateBook->execute([$loan['book_id']]);

        // Commit transaction
        $pdo->commit();

    } catch (Exception $e) {

        $pdo->rollBack();

        die("Error while returning book.");
    }
}

header("Location: index.php");
exit();
?>
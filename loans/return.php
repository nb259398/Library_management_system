<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

if (isset($_GET['id'])) {

    $loan_id = $_GET['id'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM loans
        WHERE loan_id = ?
    ");

    $stmt->execute([$loan_id]);

    $loan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($loan && $loan['status'] == 'Borrowed') {

        // Update loan
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
    }
}

header("Location: index.php");
exit();
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

// Load Members
$members = $pdo->query("SELECT * FROM members")
               ->fetchAll(PDO::FETCH_ASSOC);

// Load Books
$books = $pdo->query("SELECT * FROM books")
             ->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $member_id = $_POST['member_id'];
    $book_id = $_POST['book_id'];

    // Check Member
    $memberStmt = $pdo->prepare(
        "SELECT * FROM members WHERE member_id = ?"
    );

    $memberStmt->execute([$member_id]);

    $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

    if ($member['status'] == 'Suspended') {

        $message = "Suspended members cannot borrow books.";

    } else {

        // Check Book
        $bookStmt = $pdo->prepare(
            "SELECT * FROM books WHERE book_id = ?"
        );

        $bookStmt->execute([$book_id]);

        $book = $bookStmt->fetch(PDO::FETCH_ASSOC);

        if ($book['available_copies'] <= 0) {

            $message = "No copies available.";

        } else {

            // Check duplicate borrowing

            $checkLoan = $pdo->prepare("
                SELECT *
                FROM loans
                WHERE member_id = ?
                AND book_id = ?
                AND status = 'Borrowed'
            ");

            $checkLoan->execute([
                $member_id,
                $book_id
            ]);

            if ($checkLoan->rowCount() > 0) {

                $message = "This member already borrowed this book.";

            } else {

                $date_issued = date('Y-m-d');

                $due_date = date(
                    'Y-m-d',
                    strtotime('+14 days')
                );

                // Insert Loan

                $stmt = $pdo->prepare("
                    INSERT INTO loans
                    (
                        member_id,
                        book_id,
                        user_id,
                        date_issued,
                        due_date,
                        status
                    )
                    VALUES
                    (?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $member_id,
                    $book_id,
                    $_SESSION['user_id'],
                    $date_issued,
                    $due_date,
                    'Borrowed'
                ]);

                // Reduce Available Copies

                $updateBook = $pdo->prepare("
                    UPDATE books
                    SET available_copies =
                        available_copies - 1
                    WHERE book_id = ?
                ");

                $updateBook->execute([$book_id]);

                $message = "Book issued successfully!";
            }
        }
    }
}
?>

<?php
$pageTitle = "Issue Book";

if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'] ?? 'Admin';
}

require_once "../includes/header.php";
require_once "../includes/navbar.php";
?>

<div class="container my-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">📚 Issue Book</h3>
        </div>

        <div class="card-body">

            <?php if (!empty($message)): ?>

                <?php if (
                    $message == "Book issued successfully!"
                ): ?>

                    <div class="alert alert-success">
                        <?= htmlspecialchars($message) ?>
                    </div>

                <?php else: ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($message) ?>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">
                        Select Member
                    </label>

                    <select name="member_id"
                            class="form-select"
                            required>

                        <?php foreach ($members as $member): ?>

                            <option value="<?= $member['member_id']; ?>">
                                <?= htmlspecialchars($member['full_name']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Select Book
                    </label>

                    <select name="book_id"
                            class="form-select"
                            required>

                        <?php foreach ($books as $book): ?>

                            <option value="<?= $book['book_id']; ?>">

                                <?= htmlspecialchars($book['title']); ?>

                                (Available:
                                <?= $book['available_copies']; ?>)

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mt-4">

                    <button type="submit"
                            class="btn btn-primary">
                        📖 Issue Book
                    </button>

                    <a href="index.php"
                       class="btn btn-secondary">
                        ← Back
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
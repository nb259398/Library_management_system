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

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
</head>
<body>

<h2>Issue Book</h2>

<p style="color:green;">
    <?php echo $message; ?>
</p>

<form method="POST">

    <label>Member</label>
    <br>

    <select name="member_id" required>

        <?php foreach ($members as $member): ?>

            <option value="<?php echo $member['member_id']; ?>">
                <?php echo $member['full_name']; ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Book</label>
    <br>

    <select name="book_id" required>

        <?php foreach ($books as $book): ?>

            <option value="<?php echo $book['book_id']; ?>">

                <?php echo $book['title']; ?>

                (Available:
                <?php echo $book['available_copies']; ?>)

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <button type="submit">
        Issue Book
    </button>

</form>

<br><br>

<a href="index.php">
    Back to Loans
</a>

</body>
</html>
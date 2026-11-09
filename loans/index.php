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

<!DOCTYPE html>
<html>
<head>
    <title>Loans</title>
</head>
<body>

<h2>Loans List</h2>

<a href="issue.php">Issue Book</a>

<br><br>

<table border="1" cellpadding="10">

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

<?php foreach($loans as $loan): ?>

<tr>
    <td><?= $loan['loan_id']; ?></td>
    <td><?= $loan['title']; ?></td>
    <td><?= $loan['full_name']; ?></td>
    <td><?= $loan['date_issued']; ?></td>
    <td><?= $loan['due_date']; ?></td>
    <td><?= $loan['date_returned']; ?></td>
    <td><?= $loan['status']; ?></td>
<td>

<?php if($loan['status'] == 'Borrowed'): ?>

<a href="return.php?id=<?php echo $loan['loan_id']; ?>">
    Return Book
</a>

<?php else: ?>

Returned

<?php endif; ?>

</td>
</tr>

<?php endforeach; ?>

</table>

</body>
</html>

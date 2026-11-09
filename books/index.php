<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
</head>
<body>

<h2>Books List</h2>

<a href="create.php">Add New Book</a>
<br><br>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>ISBN</th>
    <th>Title</th>
    <th>Total Copies</th>
    <th>Available Copies</th>
    <th>Actions</th>
</tr>

<?php foreach($books as $book): ?>

<tr>
    <td><?php echo $book['book_id']; ?></td>
    <td><?php echo $book['isbn']; ?></td>
    <td><?php echo $book['title']; ?></td>
    <td><?php echo $book['total_copies']; ?></td>
    <td><?php echo $book['available_copies']; ?></td>
<td>
    <a href="edit.php?id=<?php echo $book['book_id']; ?>">Edit</a>

    |

    <a href="delete.php?id=<?php echo $book['book_id']; ?>"
       onclick="return confirm('Are you sure you want to delete this book?')">
       Delete
    </a>
</td>
</tr>

<?php endforeach; ?>

</table>

</body>
</html>

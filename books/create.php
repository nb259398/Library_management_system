<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];
    $total_copies = $_POST['total_copies'];
    $available_copies = $_POST['available_copies'];

    $stmt = $pdo->prepare("
        INSERT INTO books
        (isbn, title, author_id, category_id, publisher,
        year_published, total_copies, available_copies)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $isbn,
        $title,
        $author_id,
        $category_id,
        $publisher,
        $year_published,
        $total_copies,
        $available_copies
    ]);

    $message = "Book added successfully!";
}

$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>

<h2>Add New Book</h2>

<p style="color:green;">
    <?php echo $message; ?>
</p>

<form method="POST">

    ISBN:<br>
    <input type="text" name="isbn" required><br><br>

    Title:<br>
    <input type="text" name="title" required><br><br>

    Author:<br>
    <select name="author_id" required>
        <?php foreach($authors as $author): ?>
            <option value="<?= $author['author_id']; ?>">
                <?= $author['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    Category:<br>
    <select name="category_id" required>
        <?php foreach($categories as $category): ?>
            <option value="<?= $category['category_id']; ?>">
                <?= $category['name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    Publisher:<br>
    <input type="text" name="publisher"><br><br>

    Year Published:<br>
    <input type="number" name="year_published"><br><br>

    Total Copies:<br>
    <input type="number" name="total_copies" required><br><br>

    Available Copies:<br>
    <input type="number" name="available_copies" required><br><br>

    <button type="submit">Add Book</button>

</form>

<br>

<a href="index.php">Back to Books List</a>

</body>
</html>
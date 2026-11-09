<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$id = $_GET['id'];

$message = "";

// Fetch the current book
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch authors and categories
$authors = $pdo->query("SELECT * FROM authors")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];
    $total_copies = $_POST['total_copies'];
    $available_copies = $_POST['available_copies'];

    $update = $pdo->prepare("
        UPDATE books
        SET
            isbn=?,
            title=?,
            author_id=?,
            category_id=?,
            publisher=?,
            year_published=?,
            total_copies=?,
            available_copies=?
        WHERE book_id=?
    ");

    $update->execute([
        $isbn,
        $title,
        $author_id,
        $category_id,
        $publisher,
        $year_published,
        $total_copies,
        $available_copies,
        $id
    ]);

    $message = "Book updated successfully!";

    // Refresh data after update
    $stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
</head>
<body>

<h2>Edit Book</h2>

<p style="color:green;">
    <?php echo $message; ?>
</p>

<form method="POST">

    ISBN:<br>
    <input type="text" name="isbn"
           value="<?php echo $book['isbn']; ?>" required>
    <br><br>

    Title:<br>
    <input type="text" name="title"
           value="<?php echo $book['title']; ?>" required>
    <br><br>

    Author:<br>
    <select name="author_id">

        <?php foreach($authors as $author): ?>

        <option value="<?php echo $author['author_id']; ?>"
        <?php
            if($author['author_id'] == $book['author_id']){
                echo "selected";
            }
        ?>>
            <?php echo $author['name']; ?>
        </option>

        <?php endforeach; ?>

    </select>
    <br><br>

    Category:<br>
    <select name="category_id">

        <?php foreach($categories as $category): ?>

        <option value="<?php echo $category['category_id']; ?>"
        <?php
            if($category['category_id'] == $book['category_id']){
                echo "selected";
            }
        ?>>
            <?php echo $category['name']; ?>
        </option>

        <?php endforeach; ?>

    </select>
    <br><br>

    Publisher:<br>
    <input type="text" name="publisher"
           value="<?php echo $book['publisher']; ?>">
    <br><br>

    Year Published:<br>
    <input type="number" name="year_published"
           value="<?php echo $book['year_published']; ?>">
    <br><br>

    Total Copies:<br>
    <input type="number" name="total_copies"
           value="<?php echo $book['total_copies']; ?>">
    <br><br>

    Available Copies:<br>
    <input type="number" name="available_copies"
           value="<?php echo $book['available_copies']; ?>">
    <br><br>

    <button type="submit">Update Book</button>

</form>

<br>

<a href="index.php">Back to Books List</a>

</body>
</html>
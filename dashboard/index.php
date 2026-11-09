<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome to Library Management System</h1>

<p>
    Logged in as:
    <?php echo $_SESSION['username']; ?>
</p>

<a href="../auth/logout.php">Logout</a>
<br><br>

<a href="../books/index.php">
    Manage Books
</a>
<br><br>

<a href="../members/index.php">
    Manage Members
</a>
<br><br>

<a href="../loans/index.php">
    Manage Loans
</a>

</body>
</html>
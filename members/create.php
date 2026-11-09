<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $member_number = $_POST['member_number'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $date_joined = $_POST['date_joined'];

    $stmt = $pdo->prepare("
        INSERT INTO members
        (member_number, full_name, email, phone, status, date_joined)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $member_number,
        $full_name,
        $email,
        $phone,
        $status,
        $date_joined
    ]);

    $message = "Member added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Member</title>
</head>
<body>

<h2>Add New Member</h2>

<p style="color:green;">
    <?php echo $message; ?>
</p>

<form method="POST">

    Member Number:<br>
    <input type="text" name="member_number" required>
    <br><br>

    Full Name:<br>
    <input type="text" name="full_name" required>
    <br><br>

    Email:<br>
    <input type="email" name="email" required>
    <br><br>

    Phone:<br>
    <input type="text" name="phone" required>
    <br><br>

    Status:<br>
    <select name="status">
        <option value="Active">Active</option>
        <option value="Suspended">Suspended</option>
    </select>
    <br><br>

    Date Joined:<br>
    <input type="date" name="date_joined" required>
    <br><br>

    <button type="submit">
        Add Member
    </button>

</form>

<br>

<a href="index.php">
    Back to Members List
</a>

</body>
</html>
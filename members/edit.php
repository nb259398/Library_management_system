<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $member_number = $_POST['member_number'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];
    $date_joined = $_POST['date_joined'];

    $update = $pdo->prepare("
        UPDATE members
        SET member_number=?,
            full_name=?,
            email=?,
            phone=?,
            status=?,
            date_joined=?
        WHERE member_id=?
    ");

    $update->execute([
        $member_number,
        $full_name,
        $email,
        $phone,
        $status,
        $date_joined,
        $id
    ]);

    $message = "Member updated successfully!";

    $stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
</head>
<body>

<h2>Edit Member</h2>

<p style="color:green;">
    <?php echo $message; ?>
</p>

<form method="POST">

    Member Number:<br>
    <input type="text" name="member_number"
    value="<?php echo $member['member_number']; ?>" required>
    <br><br>

    Full Name:<br>
    <input type="text" name="full_name"
    value="<?php echo $member['full_name']; ?>" required>
    <br><br>

    Email:<br>
    <input type="email" name="email"
    value="<?php echo $member['email']; ?>" required>
    <br><br>

    Phone:<br>
    <input type="text" name="phone"
    value="<?php echo $member['phone']; ?>" required>
    <br><br>

    Status:<br>
    <select name="status">
        <option value="Active"
        <?php if($member['status']=="Active") echo "selected"; ?>>
        Active
        </option>

        <option value="Suspended"
        <?php if($member['status']=="Suspended") echo "selected"; ?>>
        Suspended
        </option>
    </select>

    <br><br>

    Date Joined:<br>
    <input type="date" name="date_joined"
    value="<?php echo $member['date_joined']; ?>" required>

    <br><br>

    <button type="submit">
        Update Member
    </button>

</form>

<br>

<a href="index.php">Back to Members List</a>

</body>
</html>
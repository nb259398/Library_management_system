<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$stmt = $pdo->query("SELECT * FROM members");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Members List</title>
</head>
<body>

<h2>Members List</h2>

<a href="create.php">Add New Member</a>
<br><br>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Member Number</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Date Joined</th>
    <th>Actions</th>
</tr>

<?php foreach($members as $member): ?>

<tr>
    <td><?php echo $member['member_id']; ?></td>
    <td><?php echo $member['member_number']; ?></td>
    <td><?php echo $member['full_name']; ?></td>
    <td><?php echo $member['email']; ?></td>
    <td><?php echo $member['phone']; ?></td>
    <td><?php echo $member['status']; ?></td>
    <td><?php echo $member['date_joined']; ?></td>

    <td>
        <a href="edit.php?id=<?php echo $member['member_id']; ?>">
            Edit
        </a>

        |

        <a href="delete.php?id=<?php echo $member['member_id']; ?>"
        onclick="return confirm('Delete this member?')">
            Delete
        </a>
    </td>
</tr>

<?php endforeach; ?>

</table>

<br>

<a href="../dashboard/index.php">
    Back to Dashboard
</a>

</body>
</html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $pdo->prepare(
        "DELETE FROM members WHERE member_id = ?"
    );

    $stmt->execute([$id]);
}

header("Location: index.php");
exit();
?>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

// Statistics

$totalBooks = $pdo->query("
    SELECT COUNT(*) FROM books
")->fetchColumn();

$totalMembers = $pdo->query("
    SELECT COUNT(*) FROM members
")->fetchColumn();

$borrowedBooks = $pdo->query("
    SELECT COUNT(*) FROM loans
    WHERE status='Borrowed'
")->fetchColumn();

$returnedBooks = $pdo->query("
    SELECT COUNT(*) FROM loans
    WHERE status='Returned'
")->fetchColumn();

$suspendedMembers = $pdo->query("
    SELECT COUNT(*) FROM members
    WHERE status='Suspended'
")->fetchColumn();

$availableBooks = $pdo->query("
    SELECT SUM(available_copies)
    FROM books
")->fetchColumn();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f4f6f9;
            padding:40px;
        }

        h1{
            margin-bottom:10px;
            color:#333;
        }

        .welcome{
            margin-bottom:30px;
            font-size:18px;
        }

        .card-container{
            display:flex;
            flex-wrap:wrap;
            gap:20px;
            margin-bottom:40px;
        }

        .card{
            flex:1;
            min-width:250px;
            padding:25px;
            border-radius:15px;
            color:white;
            box-shadow:0 5px 15px rgba(0,0,0,0.2);
        }

        .blue{
            background:linear-gradient(135deg,#007bff,#00c6ff);
        }

        .green{
            background:linear-gradient(135deg,#28a745,#6bd66b);
        }

        .orange{
            background:linear-gradient(135deg,#ff9800,#ffcc80);
        }

        .purple{
            background:linear-gradient(135deg,#6f42c1,#b388ff);
        }

        .red{
            background:linear-gradient(135deg,#dc3545,#ff7b89);
        }

        .dark{
            background:linear-gradient(135deg,#343a40,#6c757d);
        }

        .card h2{
            font-size:24px;
            margin-bottom:15px;
        }

        .card p{
            font-size:40px;
            font-weight:bold;
        }

        .menu{
            margin-top:20px;
        }

        .menu a{
            display:inline-block;
            text-decoration:none;
            color:white;
            background:#007bff;
            padding:12px 20px;
            margin:8px;
            border-radius:8px;
            font-weight:bold;
        }

        .menu a:hover{
            background:#0056b3;
        }

    </style>

</head>
<body>

<h1>📚 Library Management System Dashboard</h1>

<p class="welcome">
    Welcome,
    <strong><?php echo $_SESSION['username']; ?></strong>
</p>

<div class="card-container">

    <div class="card blue">
        <h2>Total Books</h2>
        <p><?php echo $totalBooks; ?></p>
    </div>

    <div class="card green">
        <h2>Total Members</h2>
        <p><?php echo $totalMembers; ?></p>
    </div>

    <div class="card orange">
        <h2>Books On Loan</h2>
        <p><?php echo $borrowedBooks; ?></p>
    </div>

    <div class="card purple">
        <h2>Returned Books</h2>
        <p><?php echo $returnedBooks; ?></p>
    </div>

    <div class="card red">
        <h2>Suspended Members</h2>
        <p><?php echo $suspendedMembers; ?></p>
    </div>

    <div class="card dark">
        <h2>Available Copies</h2>
        <p><?php echo $availableBooks; ?></p>
    </div>

</div>

<div class="menu">

    <a href="../books/index.php">
        📖 Manage Books
    </a>

    <a href="../members/index.php">
        👥 Manage Members
    </a>

    <a href="../loans/index.php">
        🔄 Manage Loans
    </a>

    <a href="../auth/logout.php">
        🚪 Logout
    </a>

</div>

</body>
</html>
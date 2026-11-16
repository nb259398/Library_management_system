<?php
session_start();
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // Temporary login
        if ($password == $user['password']) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard/index.php");
            exit();

        } else {
            $message = "Invalid password.";
        }

    } else {
        $message = "User not found.";
    }
}

$pageTitle = "Login";
require_once "../includes/header.php";
?>

<style>
.login-wrapper{
    min-height:85vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    width:100%;
    max-width:450px;
    border:none;
    border-radius:20px;
    overflow:hidden;
}

.login-header{
    background:linear-gradient(135deg,#0d6efd,#0a58ca);
    color:white;
    padding:30px;
    text-align:center;
}

.login-header h2{
    margin:0;
    font-weight:700;
}

.login-body{
    padding:30px;
}

.form-control{
    border-radius:10px;
    padding:12px;
}

.btn-login{
    border-radius:10px;
    padding:12px;
    font-weight:600;
}

.library-icon{
    font-size:50px;
    margin-bottom:10px;
}
</style>

<div class="container login-wrapper">

    <div class="card shadow-lg login-card">

        <div class="login-header">
            <div class="library-icon">📚</div>
            <h2>Library Management System</h2>
            <p class="mb-0">Librarian Login Portal</p>
        </div>

        <div class="login-body">

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter username"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter password"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100 btn-login">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>
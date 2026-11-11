<?php
$navLinks = [
    'dashboard'  => 'Dashboard',
    'books'      => 'Books',
    'members'    => 'Members',
    'loans'      => 'Loans',
    'authors'    => 'Authors',
    'categories' => 'Categories',
];
$currentPath = $_SERVER['SCRIPT_NAME'] ?? '';
$fullName = $_SESSION['full_name'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/Library_management_system/dashboard/">Library System</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNav" aria-label="Toggle menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <?php foreach ($navLinks as $folder => $label): ?>
          <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, "/$folder/") ? 'active' : '' ?>"
               href="/Library_management_system/<?= $folder ?>/"><?= $label ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <ul class="navbar-nav align-items-lg-center">
 

<?php if ($fullName): ?>
    <li class="nav-item">
        <span class="navbar-text me-lg-3">
            <?= htmlspecialchars($fullName) ?>
        </span>
    </li>
<?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="/Library_management_system/auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

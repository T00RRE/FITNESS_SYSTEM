<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System zarządzania salą fitness</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Własne style CSS -->
    <link href="/fitness-system/public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/fitness-system/">System Fitness</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/classes/list.php">Zajęcia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/trainers.php">Trenerzy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/memberships/types.php">Karnety</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link" href="/fitness-system/pages/admin/">Panel Admina</a>
    </li>
<?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/account.php">Moje konto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/fitness-system/pages/logout.php">Wyloguj się</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/login.php">Logowanie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/fitness-system/pages/register.php">Rejestracja</a>
                    </li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
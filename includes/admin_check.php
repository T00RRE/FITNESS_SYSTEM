<?php
// Sprawdzanie uprawnień administratora
function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        $_SESSION['error'] = 'Brak dostępu. Wymagane uprawnienia administratora.';
        header('Location: /fitness-system/index.php');
        exit();
    }
}
?>
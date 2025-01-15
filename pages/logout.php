<?php
session_start();

// Usuwanie wszystkich zmiennych sesyjnych
session_unset();

// Zniszczenie sesji
session_destroy();

// Przekierowanie na stronę główną
header("Location: ../index.php");
exit();
?>
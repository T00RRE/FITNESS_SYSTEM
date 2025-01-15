<?php
// Konfiguracja podstawowa
define('SITE_NAME', 'Fitness System');
define('BASE_URL', 'http://localhost/fitness-system');

// Ścieżki do folderów
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);
define('INCLUDES_PATH', ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR);
define('TEMPLATES_PATH', ROOT_PATH . 'templates' . DIRECTORY_SEPARATOR);
define('CLASSES_PATH', ROOT_PATH . 'classes' . DIRECTORY_SEPARATOR);

// Konfiguracja sesji
session_start();

// Funkcja autoload dla klas
spl_autoload_register(function ($class_name) {
    $file = CLASSES_PATH . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Funkcje pomocnicze
function redirect($path) {
    header("Location: " . BASE_URL . "/" . $path);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

// Obsługa błędów w trybie deweloperskim
error_reporting(E_ALL);
ini_set('display_errors', 1);
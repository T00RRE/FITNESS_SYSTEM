<?php
// Włączenie wyświetlania błędów
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Dołączenie potrzebnych plików
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Utworzenie połączenia z bazą
$database = new Database();
$db = $database->connect();

// Utworzenie nowego obiektu User
$user = new User($db);

// Ustawienie danych testowego użytkownika
$user->email = "test@example.com";
$user->password = "test123";
$user->first_name = "Jan";
$user->last_name = "Testowy";
$user->phone = "123456789";
$user->role = "client";

// Próba rejestracji
echo "<h2>Test rejestracji użytkownika</h2>";

// Najpierw sprawdzamy czy email już istnieje
if($user->emailExists($user->email)) {
    echo "Email już istnieje w bazie!";
} else {
    // Próba rejestracji
    if($user->register()) {
        echo "Użytkownik został zarejestrowany pomyślnie!";
        
        // Test logowania
        echo "<h2>Test logowania</h2>";
        if($user->login("test@example.com", "test123")) {
            echo "Logowanie udane!<br>";
            echo "ID: " . $user->id . "<br>";
            echo "Imię: " . $user->first_name . "<br>";
            echo "Nazwisko: " . $user->last_name . "<br>";
            echo "Rola: " . $user->role . "<br>";
        } else {
            echo "Błąd logowania!";
        }
    } else {
        echo "Błąd podczas rejestracji!";
    }
}
?>
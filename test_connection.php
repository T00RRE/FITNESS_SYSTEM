<?php
// Włączenie wyświetlania błędów
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Dołączenie klasy Database
require_once 'classes/Database.php';

// Utworzenie instancji klasy Database
$database = new Database();

// Próba połączenia
$db = $database->connect();

// Jeśli połączenie się udało, spróbujmy wykonać proste zapytanie
if($db) {
    try {
        $query = "SELECT @@VERSION as version";
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<br><br>Wersja SQL Server: " . $result['version'];
    } catch(PDOException $e) {
        echo "<br>Błąd zapytania: " . $e->getMessage();
    }
}
?>
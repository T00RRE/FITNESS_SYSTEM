<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_POST['registration_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie']);
    exit;
}

$database = new Database();
$db = $database->connect();

try {
    // Sprawdzenie czy rezerwacja należy do użytkownika
    $query = "UPDATE class_registrations 
              SET status = 'cancelled' 
              WHERE id = :registration_id 
              AND user_id = :user_id 
              AND status = 'registered'";
    
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        ':registration_id' => $_POST['registration_id'],
        ':user_id' => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Rezerwacja została anulowana']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie można anulować tej rezerwacji']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
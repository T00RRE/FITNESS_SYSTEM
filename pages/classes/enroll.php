<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['class_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie']);
    exit;
}

$database = new Database();
$db = $database->connect();

try {
    // Sprawdzenie czy użytkownik nie jest już zapisany
    $checkQuery = "SELECT id FROM class_registrations 
                  WHERE user_id = :user_id AND class_id = :class_id";
    $stmt = $db->prepare($checkQuery);
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':class_id' => $_POST['class_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Jesteś już zapisany na te zajęcia']);
        exit;
    }

    // Dodanie zapisu na zajęcia
    $query = "INSERT INTO class_registrations (class_id, user_id, registration_date, status) 
              VALUES (:class_id, :user_id, GETDATE(), 'registered')";
    
    $stmt = $db->prepare($query);
    $result = $stmt->execute([
        ':class_id' => $_POST['class_id'],
        ':user_id' => $_SESSION['user_id']
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Zostałeś zapisany na zajęcia!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas zapisu']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
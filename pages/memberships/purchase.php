<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Musisz być zalogowany, aby kupić karnet';
    header('Location: /fitness-system/pages/login.php');
    exit();
}

// Sprawdzenie czy przekazano ID karnetu
if (!isset($_GET['type']) || empty($_GET['type'])) {
    $_SESSION['error'] = 'Nieprawidłowy typ karnetu';
    header('Location: /fitness-system/pages/memberships/types.php');
    exit();
}

$success = false;
$error = '';

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

try {
    // Pobierz dane o typie karnetu
    $query = "SELECT * FROM membership_types WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['type']);
    $stmt->execute();
    $membershipType = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$membershipType) {
        throw new Exception('Nie znaleziono wybranego karnetu');
    }

    // Obsługa formularza
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $membership_type_id = $membershipType['id'];
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+" . $membershipType['duration'] . " days"));

        // Dodawanie karnetu
        $query = "INSERT INTO memberships (user_id, membership_type_id, start_date, end_date, payment_status) 
                 VALUES (:user_id, :membership_type_id, :start_date, :end_date, 'completed')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':membership_type_id', $membership_type_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Karnet został pomyślnie dodany!';
            header('Location: /fitness-system/pages/account.php');
            exit();
        } else {
            throw new Exception('Błąd podczas dodawania karnetu');
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

require_once '../../templates/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Potwierdzenie zakupu karnetu</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($membershipType): ?>
                        <div class="purchase-summary mb-4">
                            <h4>Szczegóły karnetu:</h4>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Typ karnetu:</strong> <?php echo htmlspecialchars($membershipType['name']); ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Czas trwania:</strong> <?php echo $membershipType['duration']; ?> dni
                                </li>
                                <li class="list-group-item">
                                    <strong>Cena:</strong> <?php echo number_format($membershipType['price'], 2); ?> zł
                                </li>
                            </ul>
                        </div>

                        <form method="POST" action="">
                            <input type="hidden" name="membership_type_id" value="<?php echo htmlspecialchars($membershipType['id']); ?>">
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Akceptuję regulamin i zobowiązuję się do jego przestrzegania
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Potwierdź zakup</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$success = false;
$error = '';

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobranie danych użytkownika
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Walidacja danych
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Aktualizacja podstawowych danych
        $updateQuery = "UPDATE users SET 
                       first_name = :first_name,
                       last_name = :last_name,
                       phone = :phone";
        $params = [
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':phone' => $phone,
            ':id' => $_SESSION['user_id']
        ];

        // Sprawdzenie czy hasło jest zmieniane
        if (!empty($current_password) && !empty($new_password)) {
            // Sprawdzenie aktualnego hasła
            $checkPassword = "SELECT password FROM users WHERE id = :id";
            $stmt = $db->prepare($checkPassword);
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $currentHash = $stmt->fetchColumn();

            if (password_verify($current_password, $currentHash)) {
                if ($new_password === $confirm_password) {
                    if (strlen($new_password) >= 6) {
                        $updateQuery .= ", password = :password";
                        $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    } else {
                        throw new Exception('Nowe hasło musi mieć minimum 6 znaków');
                    }
                } else {
                    throw new Exception('Nowe hasła nie są identyczne');
                }
            } else {
                throw new Exception('Aktualne hasło jest nieprawidłowe');
            }
        }

        $updateQuery .= " WHERE id = :id";
        $stmt = $db->prepare($updateQuery);
        
        if ($stmt->execute($params)) {
            $success = true;
            $_SESSION['success'] = 'Profil został zaktualizowany pomyślnie!';
            header('Location: edit-profile.php');
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require_once '../../templates/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <!-- Menu boczne -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Menu
                </div>
                <div class="list-group list-group-flush">
                    <a href="../account.php" class="list-group-item list-group-item-action">Moje konto</a>
                    <a href="reservations.php" class="list-group-item list-group-item-action">Moje rezerwacje</a>
                    <a href="membership.php" class="list-group-item list-group-item-action">Mój karnet</a>
                    <a href="edit-profile.php" class="list-group-item list-group-item-action active">Edytuj profil</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Wyloguj się</a>
                </div>
            </div>
        </div>

        <!-- Zawartość główna -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edycja profilu</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Imię</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Nazwisko</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small class="text-muted">Email nie może być zmieniony</small>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>

                        <hr class="my-4">

                        <h5>Zmiana hasła</h5>
                        <p class="text-muted">Wypełnij poniższe pola tylko jeśli chcesz zmienić hasło</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Aktualne hasło</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="new_password" class="form-label">Nowe hasło</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <small class="text-muted">Minimum 6 znaków</small>
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label">Powtórz nowe hasło</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Walidacja formularza
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Sprawdzenie haseł
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (newPassword.value || confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                event.preventDefault();
                alert('Nowe hasła nie są identyczne');
                return;
            }
            if (newPassword.value.length < 6) {
                event.preventDefault();
                alert('Nowe hasło musi mieć minimum 6 znaków');
                return;
            }
            if (!document.getElementById('current_password').value) {
                event.preventDefault();
                alert('Musisz podać aktualne hasło');
                return;
            }
        }

        form.classList.add('was-validated');
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>
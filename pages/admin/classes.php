<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';
require_once '../../includes/admin_check.php';

// Sprawdzenie uprawnień
requireAdmin();

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobieranie typów zajęć
$queryTypes = "SELECT id, name FROM class_types ORDER BY name";
$stmtTypes = $db->prepare($queryTypes);
$stmtTypes->execute();
$classTypes = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

// Pobieranie trenerów
$queryTrainers = "SELECT t.id, u.first_name, u.last_name 
                 FROM trainers t 
                 JOIN users u ON t.user_id = u.id 
                 ORDER BY u.last_name";
$stmtTrainers = $db->prepare($queryTrainers);
$stmtTrainers->execute();
$trainers = $stmtTrainers->fetchAll(PDO::FETCH_ASSOC);

// Obsługa formularza dodawania zajęć
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $query = "INSERT INTO classes (class_type_id, trainer_id, start_time, end_time, room, status) 
                 VALUES (:class_type_id, :trainer_id, :start_time, :end_time, :room, 'scheduled')";
        
        $stmt = $db->prepare($query);
        
        // Konwersja daty i godziny na format datetime
        $startDateTime = date('Y-m-d H:i:s', strtotime($_POST['date'] . ' ' . $_POST['start_time']));
        $endDateTime = date('Y-m-d H:i:s', strtotime($_POST['date'] . ' ' . $_POST['end_time']));
        
        $stmt->bindParam(':class_type_id', $_POST['class_type']);
        $stmt->bindParam(':trainer_id', $_POST['trainer']);
        $stmt->bindParam(':start_time', $startDateTime);
        $stmt->bindParam(':end_time', $endDateTime);
        $stmt->bindParam(':room', $_POST['room']);
        
        if ($stmt->execute()) {
            $success = "Zajęcia zostały dodane pomyślnie!";
        }
    } catch (PDOException $e) {
        $error = "Błąd podczas dodawania zajęć: " . $e->getMessage();
    }
}

require_once '../../templates/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="card">
                <div class="card-header">
                    Panel Administratora
                </div>
                <div class="list-group list-group-flush">
                    <a href="index.php" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="users.php" class="list-group-item list-group-item-action">Użytkownicy</a>
                    <a href="classes.php" class="list-group-item list-group-item-action active">Zajęcia</a>
                    <a href="reports.php" class="list-group-item list-group-item-action">Raporty</a>
                    
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dodaj nowe zajęcia</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="class_type" class="form-label">Rodzaj zajęć</label>
                                <select class="form-select" id="class_type" name="class_type" required>
                                    <option value="">Wybierz rodzaj zajęć</option>
                                    <?php foreach ($classTypes as $type): ?>
                                        <option value="<?php echo $type['id']; ?>">
                                            <?php echo htmlspecialchars($type['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trainer" class="form-label">Trener</label>
                                <select class="form-select" id="trainer" name="trainer" required>
                                    <option value="">Wybierz trenera</option>
                                    <?php foreach ($trainers as $trainer): ?>
                                        <option value="<?php echo $trainer['id']; ?>">
                                            <?php echo htmlspecialchars($trainer['first_name'] . ' ' . $trainer['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date" class="form-label">Data</label>
                                <input type="date" class="form-control" id="date" name="date" required 
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="start_time" class="form-label">Godzina rozpoczęcia</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="end_time" class="form-label">Godzina zakończenia</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="room" class="form-label">Sala</label>
                            <select class="form-select" id="room" name="room" required>
                                <option value="">Wybierz salę</option>
                                <option value="Sala A">Sala A</option>
                                <option value="Sala B">Sala B</option>
                                <option value="Sala C">Sala C</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Dodaj zajęcia</button>
                        </div>
                    </form>
                </div>
            </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Walidacja formularza
    const form = document.querySelector('.needs-validation');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });

    // Walidacja czasu
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    
    endTime.addEventListener('change', function() {
        if (startTime.value >= endTime.value) {
            endTime.setCustomValidity('Czas zakończenia musi być późniejszy niż czas rozpoczęcia');
        } else {
            endTime.setCustomValidity('');
        }
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>
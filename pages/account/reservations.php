<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobieranie rezerwacji użytkownika
$query = "SELECT 
    cr.id as registration_id,
    ct.name as class_name,
    c.start_time,
    c.end_time,
    c.room,
    u.first_name + ' ' + u.last_name as trainer_name,
    cr.status,
    cr.registration_date
FROM class_registrations cr
JOIN classes c ON cr.class_id = c.id
JOIN class_types ct ON c.class_type_id = ct.id
JOIN trainers t ON c.trainer_id = t.id
JOIN users u ON t.user_id = u.id
WHERE cr.user_id = :user_id
ORDER BY c.start_time DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <a href="reservations.php" class="list-group-item list-group-item-action active">Moje rezerwacje</a>
                    <a href="membership.php" class="list-group-item list-group-item-action">Mój karnet</a>
                    <a href="edit-profile.php" class="list-group-item list-group-item-action">Edytuj profil</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Wyloguj się</a>
                </div>
            </div>
        </div>

        <!-- Zawartość główna -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Moje rezerwacje zajęć</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($reservations)): ?>
                        <div class="alert alert-info">
                            Nie masz jeszcze żadnych rezerwacji. 
                            <a href="../classes/index.php" class="alert-link">Zapisz się na zajęcia</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Zajęcia</th>
                                        <th>Data</th>
                                        <th>Godzina</th>
                                        <th>Trener</th>
                                        <th>Sala</th>
                                        <th>Status</th>
                                        <th>Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <?php 
                                            $startTime = new DateTime($reservation['start_time']);
                                            $endTime = new DateTime($reservation['end_time']);
                                            $isPast = $endTime < new DateTime();
                                        ?>
                                        <tr class="<?php echo $isPast ? 'text-muted' : ''; ?>">
                                            <td><?php echo htmlspecialchars($reservation['class_name']); ?></td>
                                            <td><?php echo $startTime->format('d.m.Y'); ?></td>
                                            <td><?php echo $startTime->format('H:i') . ' - ' . $endTime->format('H:i'); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['trainer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($reservation['room']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch($reservation['status']) {
                                                    case 'registered':
                                                        $statusClass = 'success';
                                                        $statusText = 'Potwierdzony';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'danger';
                                                        $statusText = 'Anulowany';
                                                        break;
                                                    case 'attended':
                                                        $statusClass = 'info';
                                                        $statusText = 'Obecny';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!$isPast && $reservation['status'] == 'registered'): ?>
                                                    <button class="btn btn-sm btn-danger cancel-reservation" 
                                                            data-reservation-id="<?php echo $reservation['registration_id']; ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#cancelModal">
                                                        Anuluj
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal anulowania rezerwacji -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Potwierdzenie anulowania</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Czy na pewno chcesz anulować rezerwację na te zajęcia?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Tak, anuluj</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let reservationIdToCancel = null;

    // Obsługa kliknięcia przycisku anulowania
    const cancelButtons = document.querySelectorAll('.cancel-reservation');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            reservationIdToCancel = this.getAttribute('data-reservation-id');
        });
    });

    // Obsługa potwierdzenia anulowania
    document.getElementById('confirmCancel')?.addEventListener('click', function() {
        if (reservationIdToCancel) {
            fetch('/fitness-system/pages/account/cancel_reservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'registration_id=' + reservationIdToCancel
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Wystąpił błąd podczas anulowania rezerwacji');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Wystąpił błąd podczas anulowania rezerwacji');
            });
        }
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>
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

// Pobieranie aktywnego karnetu użytkownika
$query = "SELECT 
    m.id,
    m.start_date,
    m.end_date,
    m.payment_status,
    mt.name as membership_name,
    mt.price,
    mt.description
FROM memberships m
JOIN membership_types mt ON m.membership_type_id = mt.id
WHERE m.user_id = :user_id
AND m.end_date >= GETDATE()
ORDER BY m.end_date DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$membership = $stmt->fetch(PDO::FETCH_ASSOC);

// Pobieranie historii karnetów
$historyQuery = "SELECT 
    m.id,
    m.start_date,
    m.end_date,
    m.payment_status,
    mt.name as membership_name,
    mt.price
FROM memberships m
JOIN membership_types mt ON m.membership_type_id = mt.id
WHERE m.user_id = :user_id
AND m.end_date < GETDATE()
ORDER BY m.end_date DESC";

$stmt = $db->prepare($historyQuery);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$membershipHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <a href="membership.php" class="list-group-item list-group-item-action active">Mój karnet</a>
                    <a href="edit-profile.php" class="list-group-item list-group-item-action">Edytuj profil</a>
                    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Wyloguj się</a>
                </div>
            </div>
        </div>

        <!-- Zawartość główna -->
        <div class="col-md-9">
            <!-- Aktualny karnet -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Aktualny karnet</h4>
                    <?php if (!$membership): ?>
                        <a href="../memberships/types.php" class="btn btn-primary">Kup karnet</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($membership): ?>
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title"><?php echo htmlspecialchars($membership['membership_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($membership['description']); ?></p>
                                <ul class="list-unstyled">
                                    <li><strong>Data rozpoczęcia:</strong> <?php echo date('d.m.Y', strtotime($membership['start_date'])); ?></li>
                                    <li><strong>Data zakończenia:</strong> <?php echo date('d.m.Y', strtotime($membership['end_date'])); ?></li>
                                    <li><strong>Status płatności:</strong> 
                                        <span class="badge bg-<?php echo $membership['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo $membership['payment_status'] === 'completed' ? 'Opłacony' : 'Oczekujący'; ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <?php
                                $daysLeft = ceil((strtotime($membership['end_date']) - time()) / (60 * 60 * 24));
                                $totalDays = ceil((strtotime($membership['end_date']) - strtotime($membership['start_date'])) / (60 * 60 * 24));
                                $progress = 100 - (($daysLeft / $totalDays) * 100);
                                ?>
                                <div class="text-center">
                                    <h6>Pozostało dni:</h6>
                                    <h2 class="text-primary"><?php echo $daysLeft; ?></h2>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: <?php echo $progress; ?>%" 
                                             aria-valuenow="<?php echo $progress; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p>Nie posiadasz aktywnego karnetu.</p>
                            <a href="../memberships/types.php" class="btn btn-primary">Kup karnet</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historia karnetów -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Historia karnetów</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($membershipHistory)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Typ karnetu</th>
                                        <th>Data rozpoczęcia</th>
                                        <th>Data zakończenia</th>
                                        <th>Cena</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($membershipHistory as $history): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($history['membership_name']); ?></td>
                                            <td><?php echo date('d.m.Y', strtotime($history['start_date'])); ?></td>
                                            <td><?php echo date('d.m.Y', strtotime($history['end_date'])); ?></td>
                                            <td><?php echo number_format($history['price'], 2); ?> zł</td>
                                            <td>
                                                <span class="badge bg-secondary">Zakończony</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Brak historii karnetów.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>
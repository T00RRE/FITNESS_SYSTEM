<?php
require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Pobieranie danych użytkownika
$database = new Database();
$db = $database->connect();
$user = new User($db);
$user->getById($_SESSION['user_id']);
$membershipQuery = "SELECT 
    m.start_date,
    m.end_date,
    m.payment_status,
    mt.name as membership_name,
    mt.description
FROM memberships m
JOIN membership_types mt ON m.membership_type_id = mt.id
WHERE m.user_id = :user_id
AND m.end_date >= GETDATE()
ORDER BY m.end_date DESC";

$stmt = $db->prepare($membershipQuery);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$currentMembership = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../templates/header.php';
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
            <a href="account.php" class="list-group-item list-group-item-action active">Moje konto</a>
            <a href="account/reservations.php" class="list-group-item list-group-item-action">Moje rezerwacje</a>
            <a href="account/membership.php" class="list-group-item list-group-item-action">Mój karnet</a>
            <a href="account/edit-profile.php" class="list-group-item list-group-item-action">Edytuj profil</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger">Wyloguj się</a>
        </div>
    </div>
</div>
        <!-- Główna zawartość -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Moje konto</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Dane osobowe</h5>
                            <p><strong>Imię:</strong> <?php echo htmlspecialchars($user->first_name); ?></p>
                            <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($user->last_name); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
                            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($user->phone ?? 'Nie podano'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Statystyki</h5>
                            <p><strong>Data dołączenia:</strong> <?php echo date('d.m.Y', strtotime($user->created_at)); ?></p>
                            <p><strong>Status karnetu:</strong> <span class="badge bg-success">Aktywny</span></p>
                            <p><strong>Liczba zajęć:</strong> 12</p>
                            <p><strong>Następne zajęcia:</strong> Brak zaplanowanych zajęć</p>
                        </div>
                        <div class="card mb-4">
    <div class="card-header">
        <h4>Aktualny karnet</h4>
    </div>
    <div class="card-body">
        <?php if ($currentMembership): ?>
            <div class="membership-info">
                <h5 class="text-primary"><?php echo htmlspecialchars($currentMembership['membership_name']); ?></h5>
                <p><?php echo htmlspecialchars($currentMembership['description']); ?></p>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Data rozpoczęcia:</strong><br>
                        <?php echo date('d.m.Y', strtotime($currentMembership['start_date'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Data zakończenia:</strong><br>
                        <?php echo date('d.m.Y', strtotime($currentMembership['end_date'])); ?></p>
                    </div>
                </div>
                <div class="membership-status mt-3">
                    <p><strong>Status:</strong>
                        <span class="badge bg-success">Aktywny</span>
                    </p>
                    <?php
                    $daysLeft = ceil((strtotime($currentMembership['end_date']) - time()) / (60 * 60 * 24));
                    ?>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo (30 - $daysLeft) / 30 * 100; ?>%" 
                             aria-valuenow="<?php echo $daysLeft; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="30">
                        </div>
                    </div>
                    <small class="text-muted">Pozostało <?php echo $daysLeft; ?> dni</small>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-3">
                <p>Nie posiadasz aktywnego karnetu</p>
                <a href="/fitness-system/pages/memberships/types.php" class="btn btn-primary">Kup karnet</a>
            </div>
        <?php endif; ?>
    </div>
</div>
                    </div>

                    <div class="mt-4">
                        <h5>Ostatnie aktywności</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Zajęcia</th>
                                        <th>Trener</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center">Brak aktywności</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>
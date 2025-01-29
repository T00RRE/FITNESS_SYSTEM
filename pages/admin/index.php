<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';
require_once '../../includes/admin_check.php';

// Sprawdzenie uprawnień
requireAdmin();

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobieranie statystyk
$stats = [
    'users' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'client'")->fetch()['count'],
    'trainers' => $db->query("SELECT COUNT(*) as count FROM trainers")->fetch()['count'],
    'active_memberships' => $db->query("SELECT COUNT(*) as count FROM memberships WHERE end_date >= GETDATE()")->fetch()['count'],
    'classes_today' => $db->query("SELECT COUNT(*) as count FROM classes WHERE CAST(start_time AS DATE) = CAST(GETDATE() AS DATE)")->fetch()['count']
];

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
                    <a href="index.php" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="users.php" class="list-group-item list-group-item-action">Użytkownicy</a>
                    <a href="classes.php" class="list-group-item list-group-item-action">Zajęcia</a>
                    
                    <a href="reports.php" class="list-group-item list-group-item-action">Raporty</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <h2 class="mb-4">Dashboard</h2>

            <!-- Stats cards -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Klienci</h5>
                            <h2><?php echo $stats['users']; ?></h2>
                            <p class="card-text">Aktywnych klientów</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Trenerzy</h5>
                            <h2><?php echo $stats['trainers']; ?></h2>
                            <p class="card-text">Zatrudnionych trenerów</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Karnety</h5>
                            <h2><?php echo $stats['active_memberships']; ?></h2>
                            <p class="card-text">Aktywnych karnetów</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Dzisiejsze zajęcia</h5>
                            <h2><?php echo $stats['classes_today']; ?></h2>
                            <p class="card-text">Zaplanowanych zajęć</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent activities -->
            <div class="card mt-4">
                <div class="card-header">
                    Ostatnie aktywności
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Użytkownik</th>
                                    <th>Akcja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Tu możemy dodać system logowania aktywności -->
                                <tr>
                                    <td colspan="3" class="text-center">Brak ostatnich aktywności</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>
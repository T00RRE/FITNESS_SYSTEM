<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';
require_once '../../includes/admin_check.php';

// Sprawdzenie uprawnień
requireAdmin();

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobieranie statystyk rejestracji dla każdego miesiąca
$query = "SELECT 
            MONTH(created_at) as month,
            YEAR(created_at) as year,
            COUNT(*) as count
          FROM users 
          WHERE role = 'client'
          AND YEAR(created_at) = YEAR(GETDATE())
          GROUP BY YEAR(created_at), MONTH(created_at)
          ORDER BY year DESC, month DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Przygotowanie danych dla wykresu
$months = [];
$counts = [];
foreach ($registrations as $reg) {
    $monthName = date('F', mktime(0, 0, 0, $reg['month'], 1));
    $months[] = $monthName;
    $counts[] = $reg['count'];
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
                    <a href="classes.php" class="list-group-item list-group-item-action">Zajęcia</a>
                    <a href="reports.php" class="list-group-item list-group-item-action active">Raporty</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Raport rejestracji klientów</h5>
                </div>
                <div class="card-body">
                    <!-- Wykres -->
                    <div style="height: 400px;">
                        <canvas id="registrationChart"></canvas>
                    </div>

                    <!-- Tabela z danymi -->
                    <div class="table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Miesiąc</th>
                                    <th>Liczba rejestracji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registrations as $reg): ?>
                                    <tr>
                                        <td><?php echo date('F Y', mktime(0, 0, 0, $reg['month'], 1, $reg['year'])); ?></td>
                                        <td><?php echo $reg['count']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dodaj Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Liczba rejestracji',
                data: <?php echo json_encode($counts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Liczba rejestracji klientów w poszczególnych miesiącach'
                }
            }
        }
    });
});
</script>

<?php require_once '../../templates/footer.php'; ?>
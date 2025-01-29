<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';
require_once '../../includes/admin_check.php';

// Sprawdzenie uprawnień
requireAdmin();

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobieranie użytkowników
$query = "SELECT 
    u.id,
    u.first_name,
    u.last_name,
    u.email,
    u.phone,
    u.role,
    u.created_at,
    CASE 
        WHEN m.end_date >= GETDATE() THEN 'Aktywny'
        ELSE 'Nieaktywny'
    END as membership_status
FROM users u
LEFT JOIN (
    SELECT user_id, MAX(end_date) as end_date
    FROM memberships
    GROUP BY user_id
) m ON u.id = m.user_id
ORDER BY u.created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <a href="users.php" class="list-group-item list-group-item-action active">Użytkownicy</a>
                    <a href="classes.php" class="list-group-item list-group-item-action">Zajęcia</a>
                    <a href="reports.php" class="list-group-item list-group-item-action">Raporty</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista użytkowników</h5>
                    <a href="add_user.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Dodaj użytkownika
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtrowanie i wyszukiwanie -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchInput" placeholder="Szukaj...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="roleFilter">
                                <option value="">Wszystkie role</option>
                                <option value="client">Klient</option>
                                <option value="trainer">Trener</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">Wszystkie statusy</option>
                                <option value="Aktywny">Aktywny karnet</option>
                                <option value="Nieaktywny">Nieaktywny karnet</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabela użytkowników -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imię i Nazwisko</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>Rola</th>
                                    <th>Status karnetu</th>
                                    <th>Data rejestracji</th>
                                    <th>Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                        <td>
                                            <span class="badge <?php echo getRoleBadgeClass($user['role']); ?>">
                                                <?php echo getRoleDisplayName($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $user['membership_status'] === 'Aktywny' ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo htmlspecialchars($user['membership_status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
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

<!-- Modal potwierdzenia usunięcia -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Potwierdź usunięcie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Czy na pewno chcesz usunąć tego użytkownika?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Usuń</button>
            </div>
        </div>
    </div>
</div>

<?php
// Funkcje pomocnicze
function getRoleBadgeClass($role) {
    switch ($role) {
        case 'admin':
            return 'bg-danger';
        case 'trainer':
            return 'bg-primary';
        default:
            return 'bg-info';
    }
}

function getRoleDisplayName($role) {
    switch ($role) {
        case 'admin':
            return 'Administrator';
        case 'trainer':
            return 'Trener';
        default:
            return 'Klient';
    }
}

require_once '../../templates/footer.php';
?>

<!-- Dodatkowe skrypty -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicjalizacja wyszukiwania
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('usersTable');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value;
        
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            const nameCell = row.cells[1].textContent.toLowerCase();
            const emailCell = row.cells[2].textContent.toLowerCase();
            const roleCell = row.cells[4].textContent.toLowerCase();
            const statusCell = row.cells[5].textContent.trim();
            
            const matchesSearch = nameCell.includes(searchText) || emailCell.includes(searchText);
            const matchesRole = !roleValue || roleCell.includes(roleValue);
            const matchesStatus = !statusValue || statusCell === statusValue;
            
            row.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Obsługa usuwania użytkownika
    let userIdToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    window.deleteUser = function(userId) {
        userIdToDelete = userId;
        deleteModal.show();
    }

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (userIdToDelete) {
            // Tutaj dodaj kod do usuwania użytkownika poprzez AJAX
            console.log('Usuwanie użytkownika o ID:', userIdToDelete);
            deleteModal.hide();
        }
    });
});
</script>
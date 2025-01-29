<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

// Połączenie z bazą danych
$database = new Database();
$db = $database->connect();

// Pobierz wszystkie typy karnetów
$query = "SELECT * FROM membership_types WHERE is_active = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$membershipTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../templates/header.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Nasze karnety</h2>
    
    <div class="row justify-content-center">
        <?php foreach ($membershipTypes as $membershipType): ?>
            <div class="col-md-4 mb-4">
                <div class="card membership-card h-100 <?php echo $membershipType['name'] === 'STANDARD' ? 'border-primary' : ''; ?>">
                    <div class="card-header text-center py-3 <?php echo getHeaderClass($membershipType['name']); ?>">
                        <h4 class="my-0"><?php echo htmlspecialchars($membershipType['name']); ?></h4>
                        <?php if ($membershipType['name'] === 'STANDARD'): ?>
                            <span class="badge bg-warning">Najpopularniejszy</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h1 class="card-title text-center"><?php echo number_format($membershipType['price'], 2); ?> zł<small class="text-muted fw-light">/msc</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <?php
                            $features = getFeatures($membershipType['name']);
                            foreach ($features as $feature => $included):
                            ?>
                                <li>
                                    <i class="fas fa-<?php echo $included ? 'check text-success' : 'times text-danger'; ?> me-2"></i>
                                    <?php echo $feature; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="mt-auto">
                            <a href="purchase.php?type=<?php echo $membershipType['id']; ?>" class="btn btn-lg <?php echo $membershipType['name'] === 'STANDARD' ? 'btn-primary' : 'btn-outline-primary'; ?> w-100">
                                Wybierz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Dodatkowe informacje -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dodatkowe informacje</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-calendar-alt me-2"></i>Okres umowy</h6>
                            <p>Minimum 1 miesiąc</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-clock me-2"></i>Godziny otwarcia</h6>
                            <p>Pon-Pt: 6:00-23:00<br>Sob-Nd: 8:00-22:00</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Ważne</h6>
                            <p>Możliwość zamrożenia karnetu na 14 dni</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Funkcje pomocnicze
function getHeaderClass($type) {
    switch ($type) {
        case 'STANDARD':
            return 'bg-primary text-white';
        case 'PREMIUM':
            return 'bg-dark text-white';
        default:
            return '';
    }
}

function getFeatures($type) {
    $features = [
        'BASIC' => [
            'Dostęp do siłowni' => true,
            '2 wejścia na zajęcia grupowe' => true,
            'Szafka' => true,
            'Trening personalny' => false,
            'Sauna' => false
        ],
        'STANDARD' => [
            'Dostęp do siłowni' => true,
            'Nielimitowane zajęcia grupowe' => true,
            'Szafka' => true,
            '1 trening personalny' => true,
            'Sauna' => false
        ],
        'PREMIUM' => [
            'Dostęp do siłowni' => true,
            'Nielimitowane zajęcia grupowe' => true,
            'Szafka' => true,
            '2 treningi personalne' => true,
            'Sauna' => true
        ]
    ];
    
    return $features[$type] ?? $features['BASIC'];
}

require_once '../../templates/footer.php';
?>
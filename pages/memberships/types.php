<?php
require_once '../../config/config.php';
require_once '../../templates/header.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Nasze karnety</h2>
    
    <div class="row justify-content-center">
        <!-- Karnet BASIC -->
        <div class="col-md-4 mb-4">
            <div class="card membership-card h-100">
                <div class="card-header text-center py-3">
                    <h4 class="my-0">BASIC</h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <h1 class="card-title text-center">99 zł<small class="text-muted fw-light">/msc</small></h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Dostęp do siłowni</li>
                        <li><i class="fas fa-check text-success me-2"></i>2 wejścia na zajęcia grupowe</li>
                        <li><i class="fas fa-check text-success me-2"></i>Szafka</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Trening personalny</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Sauna</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="purchase.php?type=basic" class="btn btn-lg btn-outline-primary w-100">Wybierz</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Karnet STANDARD -->
        <div class="col-md-4 mb-4">
            <div class="card membership-card h-100 border-primary">
                <div class="card-header text-center py-3 bg-primary text-white">
                    <h4 class="my-0">STANDARD</h4>
                    <span class="badge bg-warning">Najpopularniejszy</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h1 class="card-title text-center">149 zł<small class="text-muted fw-light">/msc</small></h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Dostęp do siłowni</li>
                        <li><i class="fas fa-check text-success me-2"></i>Nielimitowane zajęcia grupowe</li>
                        <li><i class="fas fa-check text-success me-2"></i>Szafka</li>
                        <li><i class="fas fa-check text-success me-2"></i>1 trening personalny</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Sauna</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="purchase.php?type=standard" class="btn btn-lg btn-primary w-100">Wybierz</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Karnet PREMIUM -->
        <div class="col-md-4 mb-4">
            <div class="card membership-card h-100">
                <div class="card-header text-center py-3 bg-dark text-white">
                    <h4 class="my-0">PREMIUM</h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <h1 class="card-title text-center">199 zł<small class="text-muted fw-light">/msc</small></h1>
                    <ul class="list-unstyled mt-3 mb-4">
                        <li><i class="fas fa-check text-success me-2"></i>Dostęp do siłowni</li>
                        <li><i class="fas fa-check text-success me-2"></i>Nielimitowane zajęcia grupowe</li>
                        <li><i class="fas fa-check text-success me-2"></i>Szafka</li>
                        <li><i class="fas fa-check text-success me-2"></i>2 treningi personalne</li>
                        <li><i class="fas fa-check text-success me-2"></i>Sauna</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="purchase.php?type=premium" class="btn btn-lg btn-dark w-100">Wybierz</a>
                    </div>
                </div>
            </div>
        </div>
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

<?php require_once '../../templates/footer.php'; ?>
<?php
require_once 'config/config.php';
require_once 'templates/header.php';
?>

<div class="banner">
    <div class="banner-content">
        <h1>Witaj w <?php echo SITE_NAME; ?>!</h1>
        <p class="lead">System zarządzania salą fitness - znajdź swoje zajęcia już dziś!</p>
        <?php if (!isLoggedIn()): ?>
            <a class="btn btn-primary btn-lg" href="<?php echo BASE_URL; ?>/pages/register.php">Dołącz do nas</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <!-- Sekcja statystyk -->
    <div class="row text-center py-5">
        <div class="col-md-4">
            <div class="stat-box">
                <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                <h2 class="counter">1000+</h2>
                <p>Zadowolonych klientów</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <i class="fas fa-dumbbell fa-3x mb-3 text-primary"></i>
                <h2 class="counter">50+</h2>
                <p>Zajęć tygodniowo</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <i class="fas fa-medal fa-3x mb-3 text-primary"></i>
                <h2 class="counter">15+</h2>
                <p>Doświadczonych trenerów</p>
            </div>
        </div>
    </div>

    <!-- Główne karty informacyjne -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card feature-card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Zajęcia grupowe</h5>
                    <p class="card-text">Sprawdź nasz bogaty harmonogram zajęć grupowych.</p>
                    <a href="<?php echo BASE_URL; ?>/pages/classes/list.php" class="btn btn-primary">Zobacz zajęcia</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card">
                <div class="card-body text-center">
                    <i class="fas fa-user-friends fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Trenerzy</h5>
                    <p class="card-text">Poznaj naszych doświadczonych trenerów.</p>
                    <a href="<?php echo BASE_URL; ?>/pages/trainers.php" class="btn btn-primary">Zobacz trenerów</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card">
                <div class="card-body text-center">
                    <i class="fas fa-ticket-alt fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">Karnety</h5>
                    <p class="card-text">Wybierz karnet dopasowany do Twoich potrzeb.</p>
                    <a href="<?php echo BASE_URL; ?>/pages/memberships/types.php" class="btn btn-primary">Zobacz karnety</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekcja Dlaczego My? -->
    <div class="why-us-section py-5 mt-5">
        <h2 class="text-center mb-5">Dlaczego warto ćwiczyć z nami?</h2>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="why-us-box text-center">
                    <i class="fas fa-clock fa-2x mb-3 text-primary"></i>
                    <h4>Elastyczne godziny</h4>
                    <p>Zajęcia od wczesnych godzin porannych do późnego wieczora</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="why-us-box text-center">
                    <i class="fas fa-graduation-cap fa-2x mb-3 text-primary"></i>
                    <h4>Profesjonalna kadra</h4>
                    <p>Wykwalifikowani trenerzy z wieloletnim doświadczeniem</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="why-us-box text-center">
                    <i class="fas fa-gear fa-2x mb-3 text-primary"></i>
                    <h4>Nowoczesny sprzęt</h4>
                    <p>Najwyższej jakości wyposażenie i sprzęt treningowy</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="why-us-box text-center">
                    <i class="fas fa-heart fa-2x mb-3 text-primary"></i>
                    <h4>Przyjazna atmosfera</h4>
                    <p>Społeczność wspierająca się w dążeniu do celów</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
?>
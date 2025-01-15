<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/config.php';
require_once '../templates/header.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-5">Nasi trenerzy</h2>

    <!-- Trener 1 -->
    <div class="trainer-card mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="/fitness-system/public/images/trainer1.jpg" alt="Jan Kowalski" class="trainer-image">
            </div>
            <div class="col-md-6">
                <div class="trainer-info">
                    <h3>Jan Kowalski</h3>
                    <h5 class="text-primary mb-3">Trener personalny, Instruktor GT Training</h5>
                    <p>Certyfikowany trener z 8-letnim doświadczeniem. Specjalizuje się w treningu funkcjonalnym i przygotowaniu motorycznym. Absolwent AWF z pasją do ciągłego rozwoju.</p>
                    <ul class="trainer-specialties">
                        <li>Trening funkcjonalny</li>
                        <li>Przygotowanie motoryczne</li>
                        <li>Rehabilitacja sportowa</li>
                    </ul>
                    <div class="trainer-certs">
                        <span class="badge bg-secondary me-2">Certyfikat GT Training</span>
                        <span class="badge bg-secondary me-2">CrossFit Level 1</span>
                        <span class="badge bg-secondary">FMS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trener 2 -->
    <div class="trainer-card mb-5">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-6">
                <img src="/fitness-system/public/images/trainer2.jpg" alt="Anna Nowak" class="trainer-image">
            </div>
            <div class="col-md-6">
                <div class="trainer-info">
                    <h3>Anna Nowak</h3>
                    <h5 class="text-primary mb-3">Instruktor Les Mills, Pilates</h5>
                    <p>Instruktorka z 5-letnim doświadczeniem w prowadzeniu zajęć grupowych. Jej energia i profesjonalizm motywują uczestników do przekraczania własnych granic.</p>
                    <ul class="trainer-specialties">
                        <li>Les Mills BODYPUMP</li>
                        <li>Pilates</li>
                        <li>Stretching</li>
                    </ul>
                    <div class="trainer-certs">
                        <span class="badge bg-secondary me-2">Les Mills BODYPUMP</span>
                        <span class="badge bg-secondary me-2">Pilates Mat</span>
                        <span class="badge bg-secondary">Stretching</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trener 3 -->
    <div class="trainer-card mb-5">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="/fitness-system/public/images/trainer3.jpg" alt="Marek Wiśniewski" class="trainer-image">
            </div>
            <div class="col-md-6">
                <div class="trainer-info">
                    <h3>Marek Wiśniewski</h3>
                    <h5 class="text-primary mb-3">Instruktor sztuk walki, Trener personalny</h5>
                    <p>Posiadacz czarnego pasa w karate i wieloletni praktyk sztuk walki. Łączy tradycyjne techniki z nowoczesnym podejściem do treningu.</p>
                    <ul class="trainer-specialties">
                        <li>Karate</li>
                        <li>Kickboxing</li>
                        <li>Samoobrona</li>
                    </ul>
                    <div class="trainer-certs">
                        <span class="badge bg-secondary me-2">Czarny pas Karate</span>
                        <span class="badge bg-secondary me-2">Instruktor Kickboxingu</span>
                        <span class="badge bg-secondary">Trener personalny</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
require_once '../templates/footer.php';
?>
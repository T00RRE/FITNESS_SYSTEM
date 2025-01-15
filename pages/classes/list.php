<?php
require_once '../../config/config.php';
require_once '../../templates/header.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Nasze zajęcia</h2>

    <div class="row">
        <!-- GT TRAINING -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/gt-training.jpg" class="card-img-top" alt="GT Training" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">GT TRAINING</h5>
                    <p class="card-text">Intensywny trening funkcjonalny, który łączy elementy treningu siłowego i wytrzymałościowego.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 60 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Średnio zaawansowany</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 12</li>
                    </ul>
                    <a href="/fitness-system/pages/schedule/calendar.php" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>

        <!-- LES MILLS -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/les-mills.jpg" class="card-img-top" alt="Les Mills" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">LES MILLS</h5>
                    <p class="card-text">Światowej klasy programy treningowe, które łączą muzykę z efektywnym treningiem całego ciała.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 55 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Wszystkie poziomy</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 20</li>
                    </ul>
                    <a href="/fitness-system/pages/classes/register.php?class=les-mills" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>

        <!-- PILATES -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/pilates.jpg" class="card-img-top" alt="Pilates" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">PILATES</h5>
                    <p class="card-text">Ćwiczenia wzmacniające mięśnie głębokie, poprawiające postawę i elastyczność ciałaaaaaaa.

                    </p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 50 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Początkujący/Średni</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 15</li>
                    </ul>
                    <a href="/fitness-system/pages/classes/register.php?class=pilates" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>

        <!-- SZTUKI WALKI -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/martial-arts.jpg" class="card-img-top" alt="Sztuki walki" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">SZTUKI WALKI</h5>
                    <p class="card-text">Treningi łączące techniki różnych sztuk walki z intensywnym treningiem kondycyjnym.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 75 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Wszystkie poziomy</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 16</li>
                    </ul>
                    <a href="/fitness-system/pages/classes/register.php?class=martial-arts" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>

        <!-- TENIS STOŁOWY -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/table-tennis.jpg" class="card-img-top" alt="Tenis stołowy" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">TENIS STOŁOWY</h5>
                    <p class="card-text">Zajęcia dla miłośników tenisa stołowego, połączenie treningu technicznego z elementami taktyki gry.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 60 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Wszystkie poziomy</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 8</li>
                    </ul>
                    <a href="/fitness-system/pages/classes/register.php?class=table-tennis" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>

        <!-- SPINNING -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="/fitness-system/public/images/spinning.jpg" class="card-img-top" alt="Spinning" onerror="this.src='/fitness-system/public/images/placeholder.jpg'">
                <div class="card-body">
                    <h5 class="card-title">SPINNING®/INDOOR CYCLING</h5>
                    <p class="card-text">Intensywny trening kardio na rowerach stacjonarnych przy energetycznej muzyce.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock"></i> 45 minut</li>
                        <li><i class="fas fa-fire"></i> Poziom: Wszystkie poziomy</li>
                        <li><i class="fas fa-users"></i> Max uczestników: 20</li>
                    </ul>
                    <a href="/fitness-system/pages/classes/register.php?class=spinning" class="btn btn-primary w-100">Zapisz się</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../../templates/footer.php';
?>
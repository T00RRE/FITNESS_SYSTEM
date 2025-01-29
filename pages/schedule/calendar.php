<?php
require_once '../../config/config.php';
require_once '../../classes/Database.php';

// Pobieranie aktualnego miesiąca i roku
$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

// Pobieranie pierwszego i ostatniego dnia miesiąca
$firstDay = new DateTime("$year-$month-01");
$lastDay = new DateTime("$year-$month-" . $firstDay->format('t'));

// Pobieranie zajęć z bazy danych
$database = new Database();
$db = $database->connect();

$query = "SELECT 
    c.id,
    ct.name as class_name,
    c.start_time,
    c.end_time,
    c.room,
    u.first_name + ' ' + u.last_name as trainer_name
FROM classes c
JOIN class_types ct ON c.class_type_id = ct.id
JOIN trainers t ON c.trainer_id = t.id
JOIN users u ON t.user_id = u.id
WHERE c.start_time BETWEEN :start_date AND :end_date
ORDER BY c.start_time";

$stmt = $db->prepare($query);
$stmt->bindValue(':start_date', $firstDay->format('Y-m-d'));
$stmt->bindValue(':end_date', $lastDay->format('Y-m-d 23:59:59'));
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organizowanie zajęć według dat
$classesByDate = [];
foreach ($classes as $class) {
    $date = date('Y-m-d', strtotime($class['start_time']));
    if (!isset($classesByDate[$date])) {
        $classesByDate[$date] = [];
    }
    $classesByDate[$date][] = $class;
}

require_once '../../templates/header.php';

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Nagłówek kalendarza -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Kalendarz zajęć</h2>
                <div class="btn-group">
                    <?php
                    $prevMonth = new DateTime($firstDay->format('Y-m-d'));
                    $prevMonth->modify('-1 month');
                    
                    $nextMonth = new DateTime($firstDay->format('Y-m-d'));
                    $nextMonth->modify('+1 month');
                    ?>
                    <a href="?month=<?php echo $prevMonth->format('m'); ?>&year=<?php echo $prevMonth->format('Y'); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <button class="btn btn-outline-primary" disabled>
                        <?php echo $firstDay->format('F Y'); ?>
                    </button>
                    <a href="?month=<?php echo $nextMonth->format('m'); ?>&year=<?php echo $nextMonth->format('Y'); ?>" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            <!-- Kalendarz -->
            <div class="calendar-container">
                <table class="table table-bordered calendar-table">
                    <thead>
                        <tr>
                            <th>Poniedziałek</th>
                            <th>Wtorek</th>
                            <th>Środa</th>
                            <th>Czwartek</th>
                            <th>Piątek</th>
                            <th>Sobota</th>
                            <th>Niedziela</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $firstDayOfWeek = clone $firstDay;
                        $firstDayOfWeek->modify('last monday');
                        $lastDayOfWeek = clone $lastDay;
                        $lastDayOfWeek->modify('next sunday');

                        $currentDay = clone $firstDayOfWeek;

                        while ($currentDay <= $lastDayOfWeek) {
                            echo "<tr>";
                            for ($i = 0; $i < 7; $i++) {
                                $dayClass = $currentDay->format('m') != $month ? 'other-month' : '';
                                $isToday = $currentDay->format('Y-m-d') === date('Y-m-d') ? ' today' : '';
                                
                                echo "<td class='calendar-day $dayClass$isToday'>";
                                echo "<div class='day-number'>" . $currentDay->format('j') . "</div>";
                                echo "<div class='day-events'>";

                                // Wyświetlanie zajęć dla danego dnia
                                $currentDate = $currentDay->format('Y-m-d');
                                if (isset($classesByDate[$currentDate])) {
                                    foreach ($classesByDate[$currentDate] as $class) {
                                        $startTime = date('H:i', strtotime($class['start_time']));
                                        $className = htmlspecialchars($class['class_name']);
                                        $room = htmlspecialchars($class['room']);
                                        
                                        // Określanie klasy CSS na podstawie typu zajęć
                                        $eventClass = '';
                                        switch ($className) {
                                            case 'GT TRAINING':
                                                $eventClass = 'event-gt';
                                                break;
                                            case 'LES MILLS':
                                                $eventClass = 'event-les-mills';
                                                break;
                                            case 'PILATES':
                                                $eventClass = 'event-pilates';
                                                break;
                                            case 'SZTUKI WALKI':
                                                $eventClass = 'event-martial-arts';
                                                break;
                                            case 'SPINNING':
                                                $eventClass = 'event-spinning';
                                                break;
                                            default:
                                                $eventClass = 'event-default';
                                        }
                                        
                                        // Tworzenie tablicy z danymi zajęć dla JavaScript
                                        $classData = htmlspecialchars(json_encode([
                                            'id' => $class['id'],
                                            'name' => $className,
                                            'time' => $startTime,
                                            'trainer' => $class['trainer_name'],
                                            'room' => $room
                                        ]));
                                        
                                        echo "<div class='event-wrapper'>";
                                        echo "<div class='event $eventClass p-1 mb-1 rounded'>";
                                        echo "<small>$startTime</small> $className";
                                        if (isset($_SESSION['user_id'])) {
                                            echo "<button class='btn btn-sm btn-success float-end enroll-btn' 
                                                      data-bs-toggle='modal' 
                                                      data-bs-target='#enrollModal' 
                                                      data-class-info='{$classData}'>
                                                    Zapisz się
                                                </button>";
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                }
                                
                                echo "</div>";
                                echo "</td>";
                                
                                $currentDay->modify('+1 day');
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Legenda -->
            <div class="calendar-legend mt-4">
                <h5>Legenda:</h5>
                <div class="d-flex flex-wrap">
                    <div class="legend-item">
                        <div class="legend-color event-gt"></div>
                        <span>GT Training</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color event-les-mills"></div>
                        <span>Les Mills</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color event-pilates"></div>
                        <span>Pilates</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color event-martial-arts"></div>
                        <span>Sztuki Walki</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color event-spinning"></div>
                        <span>Spinning</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal zapisu na zajęcia -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zapisz się na zajęcia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Czy chcesz zapisać się na zajęcia:</p>
                <ul class="list-unstyled">
                    <li><strong>Zajęcia:</strong>  <span id="classNameConfirm"></span></li>
                    <li><strong>Godzina:</strong> <span id="classTimeConfirm"></span></li>
                    <li><strong>Trener:</strong> <span id="trainerConfirm"></span></li>
                    <li><strong>Sala:</strong> <span id="roomConfirm"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-success" id="confirmEnroll">Potwierdź zapis</button>
            </div>
        </div>
    </div>
</div>

<script src="/fitness-system/public/js/calendar.js"></script>

<?php require_once '../../templates/footer.php'; ?>
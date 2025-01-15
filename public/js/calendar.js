document.addEventListener('DOMContentLoaded', function() {
    // Inicjalizacja tooltipów
    initializeTooltips();
    
    // Inicjalizacja eventów kalendarza
    initializeCalendarEvents();
});

function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function initializeCalendarEvents() {
    // Dodanie obsługi kliknięć na wydarzenia
    document.querySelectorAll('.event').forEach(function(event) {
        event.addEventListener('click', function(e) {
            // Zapobieganie uruchomieniu tooltip przy kliknięciu
            e.stopPropagation();
            
            // Tutaj możemy dodać obsługę kliknięcia w wydarzenie
            // Na przykład otwarcie modalu ze szczegółami
            const eventDetails = {
                title: this.textContent,
                trainer: this.dataset.trainer,
                room: this.dataset.room,
                time: this.dataset.time
            };
            
            // TODO: Dodać wyświetlanie szczegółów wydarzenia
            console.log('Kliknięto wydarzenie:', eventDetails);
        });
    });
}

// Funkcja do odświeżania kalendarza (przydatna przy filtrowaniu)
function refreshCalendar() {
    // TODO: Implementacja odświeżania kalendarza
}

// Funkcja do filtrowania zajęć
function filterClasses(type) {
    // TODO: Implementacja filtrowania zajęć
}
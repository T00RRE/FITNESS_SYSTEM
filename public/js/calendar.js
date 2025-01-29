document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('enrollModal');
    
    if (modal) {
        modal.addEventListener('show.bs.modal', function(event) {
            try {
                const button = event.relatedTarget;
                const classInfo = JSON.parse(button.getAttribute('data-class-info'));
                console.log('Class Info:', classInfo); // Debugowanie

                // Uzupełnianie danych w modalu
                modal.querySelector('#classNameConfirm').textContent = classInfo.name || '';
                modal.querySelector('#classTimeConfirm').textContent = classInfo.time || '';
                modal.querySelector('#trainerConfirm').textContent = classInfo.trainer || '';
                modal.querySelector('#roomConfirm').textContent = classInfo.room || '';

                // Dodawanie ID zajęć do przycisku potwierdzenia
                const confirmButton = modal.querySelector('#confirmEnroll');
                confirmButton.setAttribute('data-class-id', classInfo.id);
            } catch (error) {
                console.error('Error parsing class info:', error);
            }
        });

        // Dodawanie handlera do przycisku potwierdzenia
        modal.querySelector('#confirmEnroll').addEventListener('click', function() {
            const classId = this.getAttribute('data-class-id');
            if (classId) {
                enrollInClass(classId);
            }
        });
    }
});

function enrollInClass(classId) {
    console.log('Enrolling in class:', classId);
    
    fetch('/fitness-system/pages/classes/enroll.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'class_id=' + classId
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('enrollModal'));
        if (modalInstance) {
            modalInstance.hide();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Wystąpił błąd podczas zapisu na zajęcia');
    });
}
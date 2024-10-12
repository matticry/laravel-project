document.addEventListener('DOMContentLoaded', function () {
    const options = {
        display: {
            components: {
                calendar: true,
                date: true,
                month: true,
                year: true,
                decades: true,
                clock: true,
                hours: true,
                minutes: true,
                seconds: false
            },
            icons: {
                type: 'icons',
                time: 'fas fa-clock',
                date: 'fas fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-check',
                clear: 'fas fa-trash',
                close: 'fas fa-xmark'
            }
        },
        localization: {
            locale: 'es',
            format: 'dd/MM/yyyy HH:mm'
        },
        restrictions: {
            minDate: new Date()  // Opcional: restringe la selección a fechas futuras
        }
    };

    const startPicker = new tempusDominus.TempusDominus(document.getElementById('start_datetime'), options);
    const endPicker = new tempusDominus.TempusDominus(document.getElementById('end_datetime'), options);

    // Opcional: Vincular los dos datepickers para que la fecha final no pueda ser anterior a la inicial
    startPicker.subscribe(tempusDominus.Namespace.events.change, (e) => {
        endPicker.updateOptions({
            restrictions: {
                minDate: e.date
            }
        });
    });
});

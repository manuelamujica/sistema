
console.log('JS');

/* =======================================
FILTRADO POR FECHAS DESCARGA DE PRODUCTOS
==========================================*/
$('#daterange-btn').daterangepicker({
    locale: {
        format: 'YYYY-MM-DD',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Rango Personalizado',
        weekLabel: 'S',
        firstDay: 1
    },
    ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment()
}, function(start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    // Guardar fechas en campos ocultos
    $('#fechaInicio').val(start.format('YYYY-MM-DD'));
    $('#fechaFin').val(end.format('YYYY-MM-DD'));
});

//Cuando el form se envie
$('#form').on('submit', function(e) {
    const fechaInicio = $('#fechaInicio').val();
    const fechaFin = $('#fechaFin').val();
    // Convertir las fechas a objetos Date para comparación
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    // Validar que la fecha de inicio no sea posterior a la fecha de fin
    if (inicio > fin) {
        Swal.fire({
                title: 'Error',
                text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
                icon: 'warning'
            });
        e.preventDefault();
        return;
    }else{
        Swal.fire({
                title: 'Exito',
                text: 'Reporte Generado.',
                icon: 'success'
            });
        }
});

// Restablecer el rango de fechas DESCARGA
$('#reset-btn').on('click', function() {
    $('#fechaInicio').val('');
    $('#fechaFin').val('');
    $('#daterange-btn span').html('Rango de fecha'); 
});


/* ====================================
FILTRADO POR FECHAS CARGA DE PRODUCTOS
======================================= */
$('#daterangec-btn').daterangepicker({
    locale: {
        format: 'YYYY-MM-DD',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Rango Personalizado', // Cambia el texto aquí
        weekLabel: 'S',
        firstDay: 1
    },
    ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment()
}, function(start, end) {
    $('#daterangec-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    // Guardar fechas en campos ocultos
    $('#fechaInicio1').val(start.format('YYYY-MM-DD'));
    $('#fechaFin1').val(end.format('YYYY-MM-DD'));
});

$('#form1').on('submit', function(e) {
    const fechaInicio = $('#fechaInicio1').val();
    const fechaFin = $('#fechaFin1').val();
    // Convertir las fechas a objetos Date para comparación
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    // Validar que la fecha de inicio no sea posterior a la fecha de fin
    if (inicio > fin) {
        Swal.fire({
                title: 'Error',
                text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
                icon: 'warning'
            });
        e.preventDefault(); // Evitar que el formulario se envíe
        return;
    }else{
        Swal.fire({
                title: 'Exito',
                text: 'Reporte Generado.',
                icon: 'success'
            });
    }
});

// Restablecer el rango de fechas al hacer clic en el botón CARGA
$('#resetc-btn').on('click', function() {
    $('#fechaInicio1').val('');
    $('#fechaFin1').val('');
    $('#daterangec-btn span').html('Rango de fecha'); // Cambia el texto del botón
});



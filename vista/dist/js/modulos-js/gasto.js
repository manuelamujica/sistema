//Validar registrar NUEVO 29/04/2025
$('#descripcion').blur(function (e) {
    var buscar = $('#descripcion').val();
    $.post('index.php?pagina=gastos', { buscar }, function (response) {
        if (response != '') {
            Swal.fire({
                title: 'Error',
                text: 'No se puede registrar un gasto existente.',
                icon: 'warning'
            });
        }
    }, 'json');
});

$(document).ready(function () {
    $('#formregistrarFrecuancia').on('input', '#frecuencia', function () {

        var frecuencia = $('#frecuencia').val().toLowerCase();

        var diasPlazo;

        switch (frecuencia) {
            case 'diario':
                diasPlazo = '1';
                break;
            case 'semanal':
                diasPlazo = '7';
                break;
            case 'quincenal':
                diasPlazo = '15';
                break;
            case 'mensual':
                diasPlazo = '30';

                break;
            case 'bimestral':
                diasPlazo = '60';

                break;
            case 'trimestral':
                diasPlazo = '90';

                break;
            case 'semestral':
                diasPlazo = '180';

                break;
            case 'anual':
                diasPlazo = '365';

                break;
            case 'cuatrimestral':
                diasPlazo = '120';

                break;
            default:
                diasPlazo = '';
        }

        $('#dias').val(diasPlazo);

    });


});

$(document).ready(function () {
    $('#frecuenciaC').on('change', function () {
        var codigoFrecuencia = $(this).val();
        var nombreFrecuencia = $(this).find('option:selected').data('nombre');
        var now = new Date();
        var fechaRegistro;
        console.log("Frecuencia seleccionada:", nombreFrecuencia);

        switch (nombreFrecuencia) {
            case 'diario':
            case 'semanal':
            case 'quincenal':
                fechaRegistro = now;
                console.log("Frecuencia seleccionada:", frecuencia, "Fecha registrada:", fechaRegistro);
                break;
            case 'mensual':
                now.setMonth(now.getMonth() + 1);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a mensual. Nueva fecha:", fechaRegistro);
                break;
            case 'bimestral':
                now.setMonth(now.getMonth() + 2);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a bimestral. Nueva fecha:", fechaRegistro);
                break;
            case 'trimestral':
                now.setMonth(now.getMonth() + 3);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a trimestral. Nueva fecha:", fechaRegistro);
                break;
            case 'semestral':
                now.setMonth(now.getMonth() + 6);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a semestral. Nueva fecha:", fechaRegistro);
                break;
            case 'anual':
                now.setFullYear(now.getFullYear() + 1);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a anual. Nueva fecha:", fechaRegistro);
                break;
            case 'cuatrimestral':
                now.setMonth(now.getMonth() + 4);
                now.setDate(1);
                fechaRegistro = now;
                console.log("Avanzando a cuatrimestral. Nueva fecha:", fechaRegistro);
                break;
            default:
                fechaRegistro = now;
                console.log("Frecuencia no válida. Fecha registrada:", fechaRegistro);
        }

        var fechaFormateada = fechaRegistro.getFullYear() + '-' +
            String(fechaRegistro.getMonth() + 1).padStart(2, '0') + '-' +
            String(fechaRegistro.getDate()).padStart(2, '0');

        $('#fecha').val(fechaFormateada);
        $('#fecha-hora').val(fechaFormateada);

        console.log("Fecha calculada:", fechaFormateada);
    });
});

//AJUSTE DE GASTOS LISTOS

//Nuevo
$(document).ready(function () {
    $('#categoriaG').on('change', function () {
        var codCategoria = $(this).val();
        console.log(codCategoria);
        $.ajax({
            url: 'index.php?pagina=gastos',
            type: 'POST',
            data: { mostrarTporC: codCategoria },
            success: function (response) {
                try {
                    console.log(response.tipo_gasto + "cod_tipo");
                    if (response.tipo_gasto) {
                        $('#Tgasto').val(response.tipo_gasto);
                    } else {
                        $('#Tgasto').val('');
                    }
                } catch (error) {
                    console.error('Error al procesar la respuesta:', error);
                }
            },
            error: function () {
                console.error('Error al obtener el tipo de gasto');
            }
        });
    });
});

//PAGOS DE GASTOS
$('#pagoGModal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_gasto');
    var total = button.data('total');
    var fecha = new Date();
    var nombre = button.data('nombre');
    var fechaFormateada = fecha.getFullYear() + '-' +
        String(fecha.getMonth() + 1).padStart(2, '0') + '-' +
        String(fecha.getDate()).padStart(2, '0');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod_gasto1').val(codigo);
    modal.find('.modal-body #cod_gasto').val(codigo);
    modal.find('.modal-body #monto_pagar').val(total);
    modal.find('.modal-body #total-pago').text(total + 'Bs');
    modal.find('.modal-body #fecha_del_pago').val(fechaFormateada);
    modal.find('.modal-body #nombre_gasto').val(nombre);
});

//PAGO PARCIAL
$('#partesModal').on('show.bs.modal', function (event) {
    console.log("Modal de pago parcial abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('codgasto');
    var fecha = button.data('fecha');
    var codp = button.data('codpago');



    var total = button.data('totalgastos');
    var monto = button.data('montop');
    var nombre = button.data('nombregasto');
    var mpagar = Math.abs(total - monto);


    console.log(button.data('codgasto'));
    console.log(button.data('codpago'));
    console.log(button.data('totalgastos'));
    console.log(button.data('montop'));
    console.log(mpagar + " esto es lo que se debe pagar");



    console.log(button.data('fecha'));
    console.log(button.data('nombregasto'));


    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro_gasto').val(codigo);
    modal.find('.modal-body #montoT').text(monto);
    modal.find('.modal-body #monto_pagar1').val(mpagar.toFixed(2));
    modal.find('.modal-body #total-gasto').text(total + 'Bs');
    modal.find('.modal-body #t-parcial').val(mpagar);
    modal.find('.modal-body #monto-pagar').text(mpagar.toFixed(2) + 'Bs');
    modal.find('.modal-body #fecha_cuota').val(fecha);
    modal.find('.modal-body #nombreG').val(nombre);
    modal.find('.modal-body #codigop').val(codp);

});

$('#vueltoModal').on('show.bs.modal', function (event) {
    console.log("Modal de pago parcial abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('codgasto');
    var fecha = button.data('fecha');
    var codp = button.data('codpago');



    var total = button.data('totalgastos');
    var monto = button.data('montop');
    var nombre = button.data('nombregasto');
    var mpagar = Math.abs(total - monto);


    console.log(button.data('codgasto'));
    console.log(button.data('codpago'));
    console.log(button.data('totalgastos'));
    console.log(button.data('montop'));
    console.log(mpagar + " esto es lo que se debe pagar");



    console.log(button.data('fecha'));
    console.log(button.data('nombregasto'));


    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro_gasto').val(codigo);
    modal.find('.modal-body #montoT').text(monto);
    modal.find('.modal-body #monto_pagar1').val(mpagar.toFixed(2));
    modal.find('.modal-body #total-gasto').text(total + 'Bs');
    modal.find('.modal-body #t-parcial').val(mpagar);
    modal.find('.modal-body #monto-pagar').text(mpagar.toFixed(2) + 'Bs');
    modal.find('.modal-body #fecha_cuota').val(fecha);
    modal.find('.modal-body #nombreG').val(nombre);
    modal.find('.modal-body #codigop').val(codp);

});


function calcularTotalpago() {
    let totalBs = 0;

    document.querySelectorAll('.monto-bs:not(.monto-con)').forEach(function (input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;
    });

    document.querySelectorAll('.monto-divisa').forEach(function (inputDivisa) {
        let index = inputDivisa.id.split('-').pop();

        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        let tasaConversion = parseFloat(document.getElementById('tasa-conversion-' + index).value) || 1;


        let montoConvertidoBs = montoDivisa * tasaConversion;


        document.getElementById('monto-bs-con-' + index).value = montoConvertidoBs.toFixed(2);


        totalBs += montoConvertidoBs;
    });


    document.getElementById('monto_pagado').value = totalBs.toFixed(2);


    let montoPagar = parseFloat(document.getElementById('monto_pagar').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia').value = diferencia.toFixed(2);

    if (diferencia < 0) {
        let vuelto = Math.abs(diferencia);
        document.getElementById('vuelto').value = vuelto.toFixed(2);
    } else {
        document.getElementById('vuelto').value = '0.00';
    }



}

function calcularTotalpago1() {
    let totalBs = 0;

    document.querySelectorAll('.monto-bs1:not(.monto-con1)').forEach(function (input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;
    });

    document.querySelectorAll('.monto-divisa1').forEach(function (inputDivisa) {
        let index = inputDivisa.id.split('-').pop();
        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        let tasaConversion = parseFloat(document.getElementById('tasa-conversion1-' + index).value) || 1;

        let montoConvertidoBs = montoDivisa * tasaConversion;

        document.getElementById('monto-bs-con-1' + index).value = parseFloat(montoConvertidoBs.toFixed(2));

        totalBs += montoConvertidoBs;
    });

    document.getElementById('monto_pagadoxcuotas').value = totalBs.toFixed(2);

    let montoPagar = parseFloat(document.getElementById('monto_pagar1').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia1').value = diferencia.toFixed(2);
    if (diferencia < 0) {
        let vuelto = Math.abs(diferencia);
        document.getElementById('vuelto1').value = vuelto.toFixed(2);
    } else {
        document.getElementById('vuelto1').value = '0.00';
    }
}

//EDITAR
/* NUEVO EN DESARROLLO */
$('#modificargasto').on('show.bs.modal', function (event) {
    console.log("Modal de EDICIÓN abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_gasto');
    var nombre = button.data('nombre');

    console.log("Nombre del gasto:", nombre);
    console.log("Código del gasto:", codigo);

    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod_gastoE').val(codigo);
    modal.find('.modal-body #nombreG').val(nombre);
    modal.find('.modal-body #cod_gasto_oculto').val(codigo);
    modal.find('.modal-body #nombreOculto').val(nombre);

});

/* NUEVO EN DESARROLLO */
$('#finalizarPagoBtn').on('click', function (event) {
    event.preventDefault();

    let formData = $('#pagoForm').serializeArray();
    let data = {};

    formData.forEach(function (item) {
        if (item.name.includes('pago')) {
            let match = item.name.match(/pago\[(\d+)\]\[(.+)\]/);
            if (match) {
                let index = match[1];
                let key = match[2];
                if (!data.pago) data.pago = [];
                if (!data.pago[index]) data.pago[index] = {};
                data.pago[index][key] = item.value;
            }
        } else {
            data[item.name] = item.value;
        }
    });

    $.ajax({
        url: 'index.php?pagina=gastos',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function (registrarPG) {
            //console.log('Pago registrado:', response);
            if (registrarPG.status === 'success') {
                Swal.fire({
                    title: registrarPG,
                    text: registrarPG.data.message,
                    icon: registrarPG.data.icon
                }).then(() => {
                    $('#modalregistrardetallep').modal('hide'); // Cerrar el modal
                    $('#formRegistrarDetalle')[0].reset(); // Reiniciar el formulario
                });

            } else {
                console.error('Error al registrar el pago:', registrarPG.message);
                Swal.fire({
                    title: 'Error',
                    text: registrarPG.message,
                    icon: 'error'
                });
            }
        },
        error: function (error) {
            console.error('Error al registrar el pago:', error);

        }
    });
});


$(document).ready(function () {
    // FUNCIONES
    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
            'display': 'block',
            'color': 'red',
        });
    }

    function hideError(selector) {
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display', 'none');
    }
    // FIN FUNCIONES


    $('#tipo_medida1').on('blur', function () {
        var tipo_medida1 = $(this).val();
        if (tipo_medida1.trim() === '') {
            hideError('#tipo_medida1');
        } else if (tipo_medida1.length > 10) {
            showError('#tipo_medida1', 'El texto no debe exceder los 10 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida1)) {
            showError('#tipo_medida1', 'Solo se permiten letras');
        } else {
            hideError('#tipo_medida1');
        }
    });

    $('#tipo_medida').on('blur', function () {
        var tipo_medida = $(this).val();
        if (tipo_medida.trim() === '') {
            hideError('#tipo_medida');
        } else if (tipo_medida.length > 10) {
            showError('#tipo_medida', 'El texto no debe exceder los 10 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida)) {
            showError('#tipo_medida', 'Solo se permiten letras');
        } else {
            hideError('#tipo_medida');
        }
    });
});






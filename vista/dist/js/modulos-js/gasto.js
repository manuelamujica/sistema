
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
    var modal = $(this);
    /* LIMPIO EL MODAL Y EL CALCULO DE ESTE */
    modal.find('.modal-body #total-pago1').text('0.00 Bs');
    modal.find('.modal-body #total-pago2').text('0.00 Bs');
    modal.find('.modal-body #total-pago').text('0.00 Bs');
    modal.find('.modal-body #cod_gasto1').val('');
    modal.find('.modal-body #cod_gasto').val('');
    modal.find('.modal-body #monto_pagar').val('');
    modal.find('.modal-body #fecha_del_pago').val('');
    modal.find('.modal-body #nombre_gasto').val('');
    modal.find('.modal-body .monto-section').hide();
    modal.find('.modal-body #monto_pagado').val('0.00');
    modal.find('.modal-body #diferencia').val('0.00');
    modal.find('.modal-body #vuelto').val('0.00');

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_gasto');
    var codp = button.data('codpago');
    var gasto = 'gasto';
    modal.find('.modal-body #gasto').val(gasto);
    var fecha = new Date();
    var fechaFormateada = fecha.getFullYear() + '-' +
        String(fecha.getMonth() + 1).padStart(2, '0') + '-' +
        String(fecha.getDate()).padStart(2, '0');
    var nombre = button.data('nombre');
    var montop = button.data('montop') || 0;
    var total = button.data('totalgastos');
    var modal = $(this);

    modal.find('.modal-body #cod_gasto1').val(codigo);
    modal.find('.modal-body #cod_gasto').val(codigo);

    modal.find('.modal-body #total-gasto').text(total + 'Bs');
    modal.find('.modal-body #total-gasto-oculto').val(total);

    modal.find('.modal-body #fecha_del_pago').val(fechaFormateada);
    modal.find('.modal-body #nombre_gasto').val(nombre);

    var totalGastoOculto = $('#total-gasto-oculto').val();

    $.ajax({
        url: 'index.php?pagina=gastos',
        method: 'POST',
        data: { cod_gasto: codigo },
        dataType: 'json',
        success: function (response) {
            var modal = $('#pagoGModal');
            if (response.success) {
                var montoTotal = parseFloat(response.monto_total) || 0; 

                console.log("Monto total como número:", montoTotal);


                modal.find('.modal-body #total-pago1').text(montoTotal.toFixed(2) + ' Bs');
                modal.find('.modal-body #total-gasto').text(total.toFixed(2) + ' Bs');

                var montopagar = Math.abs(total - montoTotal);
                modal.find('.modal-body #total-pago').text(montopagar.toFixed(2) + ' Bs');
                modal.find('.modal-body #monto_pagar').val(montopagar.toFixed(2));

                modal.find('.modal-body .monto-section').show();
            } else {
                modal.find('.modal-body #monto_pagar').val(total);
                modal.find('.modal-body .monto-section').hide();

            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);

            var modal = $('#pagoGModal');
            modal.find('.modal-body .monto-section').hide();
        }
    });

});


$('#vueltoModal').on('show.bs.modal', function (event) {
    console.log("Modal de pago parcial abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_gasto');
    var vuelto = button.data('vuelto');

    console.log("Código del gasto:", codigo);
    console.log("Total del vuelto:", vuelto);

    var modal = $(this);
    modal.find('.modal-body #nro_gasto').val(codigo);
    modal.find('.modal-body #montoV').text(vuelto);
    modal.find('.modal-body #monto_vuelto').val(vuelto);
    

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
        console.log('Index:', index);
        console.log('Monto Divisa:', montoDivisa);
        console.log('Tasa de Conversión:', tasaConversion);


        let montoConvertidoBs = montoDivisa * tasaConversion;


        document.getElementById('monto-bs-con-' + index).value = montoConvertidoBs.toFixed(2);


        totalBs += montoConvertidoBs;
    });


    document.getElementById('monto_pagado').value = totalBs.toFixed(2);


    let montoPagar = parseFloat(document.getElementById('monto_pagar').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia').value = diferencia.toFixed(2);

    var vuelto = 0;
    if (diferencia < 0) {
        vuelto = Math.abs(diferencia);
        document.getElementById('vuelto').value = vuelto.toFixed(2);
    } else {
        document.getElementById('vuelto').value = '0.00';
    }

    console.log('Vuelto:', vuelto);

    const registrarVueltoBtn = document.getElementById('registrarVueltoBtn');
    if (vuelto > 0) {
        registrarVueltoBtn.style.display = 'inline-block';
        console.log('Botón Registrar Vuelto mostrado');
        registrarVueltoBtn.setAttribute('data-cod_gasto', document.getElementById('cod_gasto').value);
        registrarVueltoBtn.setAttribute('data-vuelto', vuelto.toFixed(2));
    } else {
        registrarVueltoBtn.style.display = 'none';
        console.log('Botón Registrar Vuelto oculto');
    }

}

function calcularTotalvuelto() {
    let totalBs = 0;

    document.querySelectorAll('.monto-bs1:not(.monto-con1)').forEach(function (input) {
        let montoBs = parseFloat(input.value) || 0;
        console.log('Monto en Bs:', montoBs);
        totalBs += montoBs;
    });

    document.querySelectorAll('.monto-divisa1').forEach(function (inputDivisa) {
        let index = inputDivisa.id.split('-').pop();

        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        let tasaConversion = parseFloat(document.getElementById('tasa-conversion1-' + index).value) || 1;
        console.log('Index:', index);
        console.log('Monto Divisa:', montoDivisa);
        console.log('Tasa de Conversión:', tasaConversion);


        let montoConvertidoBs = montoDivisa * tasaConversion;


        document.getElementById('monto-bs-con1-' + index).value = montoConvertidoBs.toFixed(2);


        totalBs += montoConvertidoBs;
    });

    console.log('Total en Bs:', totalBs);
    document.getElementById('monto_pagado1').value = totalBs.toFixed(2);


    let montoPagar = parseFloat(document.getElementById('monto_vuelto').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia1').value = diferencia.toFixed(2);

}

$(document).ready(function () {
    $('#vueltoModalBtn').on('click', function (e) {
        e.preventDefault(); 

        // Obtén los datos del formulario
        let vuelto = $('#monto_vuelto').val();

 
        if (!vuelto) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
            });
            return;
        }

        $.ajax({
            url: 'index.php?pagina=gastos',
            method: 'POST',
            data: {
                vuelto: vuelto,
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'El vuelto se registró correctamente.',
                        icon: 'success',
                    }).then(() => {
                        
                        $('#vueltoModal').modal('hide');
                        $('#vueltoForm')[0].reset();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Hubo un error al registrar el vuelto.',
                        icon: 'error',
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar el vuelto. Intenta nuevamente.',
                    icon: 'error',
                });
            },
        });
    });
});


//EDITAR
/* Listo */
$('#modificat').on('show.bs.modal', function (event) {
    console.log("Modal de EDICIÓN abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');
    var status = button.data('status');

    console.log("Nombre del gasto:", nombre);
    console.log("Código del gasto:", codigo);

    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod_cat_gasto').val(codigo);
    modal.find('.modal-body #nombre').val(nombre);
    modal.find('.modal-body #cod_cat_gasto_oculto').val(codigo);
    modal.find('.modal-body #origin').val(nombre);


});


$('#modaleliminar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');

    var modal = $(this);
    modal.find('.modal-body #cod_eliminar').val(codigo);
    modal.find('.modal-body #categoria').text(nombre);

    console.log(codigo);
});

//GASTO

$('#modificargasto').on('show.bs.modal', function (event) {
    console.log("Modal de EDICIÓN abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('codigo_gasto');
    var nombre = button.data('nombre');

    console.log("Nombre del gasto:", nombre);
    console.log("Código del gasto:", codigo);

    // Modal
    var modal = $(this);
    modal.find('.modal-body #cod_gastoE').val(codigo);
    modal.find('.modal-body #nombreG').val(nombre);
    modal.find('.modal-body #cod_gasto_oculto').val(codigo);
    modal.find('.modal-body #origin').val(nombre);


});

$('#eliminarG').on('show.bs.modal', function (event) {
    console.log("MODAL DE ELIMINACIÓN");
    var button = $(event.relatedTarget);
    var codigo = button.data('cod');
    var nombre = button.data('eliminar');

    var modal = $(this);
    modal.find('.modal-body #cod_eliminar').val(codigo);
    modal.find('.modal-body #gasto').text(nombre);

    console.log(codigo);
    console.log(nombre);
});






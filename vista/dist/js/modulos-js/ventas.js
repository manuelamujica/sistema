
/* =======================
SCRIPT DE VENTA
============================= */

var productoIndex = 1; 

// Función para agregar una nueva fila a la tabla
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" id="codigoProducto${index}" name="productos[${index}][codigo]" placeholder="Código del producto" readonly required>
                <input type="hidden" class="form-control" id="tipoProducto${index}">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="nombreProducto${index}" name="productos[${index}][nombre]" placeholder="Nombre del producto" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                    </div>
                </div>
                <div id="lista-productos${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
            </td>
            <td>
                <input type="hidden" class="form-control" id="stockproducto${index}" step="0.001">
                <div class="input-group">
                    <input type="number" class="form-control" name="productos[${index}][cantidad]" id="cantidadProducto${index}" min="0" step="0.001" onchange="calcularTotal(${index})" oninput="calcularTotal(${index})" required>
                    <div class="invalid-feedback" style="display: none; position: absolute; top: 100%; margin-top: 2px; width: calc(100% - 2px); font-size: 0.875em; text-align: left;"></div>
                    <div class="input-group-append">
                        <span id="unidadm${index}" class="input-group-text" value=" "></span>
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="precioProducto${index}" name="productos[${index}][precio]" placeholder="Precio" onchange="calcularTotal(${index})" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text">Bs</span>
                    </div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="total${index}" name="productos[${index}][total]" placeholder="Total" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text">Bs</span>
                    </div>
                </div>
            </td>
            <td>
                <button type="button" class="btn-sm btn-danger" onclick="eliminarFila(${index})">&times;</button>
            </td>
        </tr>
    `;
    
}

function agregarFila() {
    var nuevaFila = crearfila(productoIndex);
    $('#ventaProductosBody').append(nuevaFila);
    productoIndex++; 
}

function inicializarFilas() {
    for (let i = 1; i <= 1; i++) {
        agregarFila();
    }
}

$(document).ready(function() {
    inicializarFilas(); 
});

function eliminarFila(index) {
    // Eliminar la fila del DOM usando el identificador de fila
    var fila = document.getElementById(`fila${index}`);
    if (fila) {
        fila.remove();
    }
    calcularTotal();
}

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

$(document).ready(function() {
    $(document).on('input', '[id^=cantidadProducto]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('cantidadProducto', ''); // Extrae el índice de la cantidad
        var cantidad = parseFloat($(this).val()) || 0;
        var negativo=$(this).val();
        var stock = parseFloat($('#stockproducto' + index).val()) || 0;

        if (cantidad > stock) {
            showError('#' + inputId, 'stock insuficiente');
        } else if(!/^(?!-)\d*\.?\d+$/.test(negativo) || cantidad==0){
            showError('#' + inputId, 'cantidad invalida');
        }else {
            hideError('#' + inputId);
        }
    });
    
    $('#cedula-rif').on('input', function() {
        var cedula_rif = $(this).val();
        if (cedula_rif.length > 12) {
            showError('#cedula-rif', 'debe contener maximo 12 números');
        } else if (!/^\d+$/.test(cedula_rif)) {
            showError('#cedula-rif', 'debe contener solo numeros');
        }else {
            hideError('#cedula-rif');
        }
    });
});


// Calcular el total de cada fila
function calcularTotal(index) {
    var cantidad = parseFloat($(`[name="productos[${index}][cantidad]"]`).val()) || 0;
    var precio = parseFloat($(`[name="productos[${index}][precio]"]`).val()) || 0;
    var total = cantidad * precio;
    $(`[name="productos[${index}][total]"]`).val(total.toFixed(2));
    actualizarResumen(); 
}


// Función para actualizar el resumen de venta
function actualizarResumen() { 
    var subtotal = 0;
    var exento = 0;
    var baseImponible = 0;
    var iva = 0;

    //recorrer todas las filas 
    for (var i = 1; i < productoIndex; i++) {
        var totalProducto = parseFloat($('#total' + i).val()) || 0;
        console.log('Total del producto ' + i + ':', totalProducto);
        if (isNaN(totalProducto)) {
            totalProducto = 0;
        }
        subtotal += totalProducto;
        var tipoProducto = parseFloat($('#tipoProducto' + i).val());
    if (tipoProducto === 1) {
            exento += totalProducto;
        } else if (tipoProducto === 2) {
            baseImponible += totalProducto;
        }
    }
    iva = baseImponible * 0.16;
    var totalGeneral = subtotal + iva;

    console.log('Subtotal:', subtotal);
    console.log('Exento:', exento);
    console.log('Base Imponible:', baseImponible);
    console.log('IVA:', iva);
    console.log('Total General:', totalGeneral);

    //asignacion de valores 
    $('#subtotal').text(subtotal.toFixed(2));
    $('#exento').text(exento.toFixed(2));
    $('#base-imponible').text(baseImponible.toFixed(2));
    $('#iva').text(iva.toFixed(2));
    $('#total-span').text(totalGeneral.toFixed(2));
    $('#total-general').val(totalGeneral.toFixed(2));
}


    $('#cedula-rif').on('blur keydown', function (e) {
        if (e.type === 'blur' || (e.type === 'keydown' && e.keyCode === 13)) {
            e.preventDefault(); 
            var buscar = $('#cedula-rif').val();
            $.post('index.php?pagina=clientes', { buscar }, function(response) {
                if(response !== false){
                    if (response['status'] != 0) {
                    var nombre = response['nombre'] + " " + response['apellido'];
                    var telefono = response['telefono'];
                    var apellido = response['apellido'];
                    var codigo = response['cod_cliente'];
                    var modal = $('#ventaModal');
                    modal.find('.modal-body #numero-cliente').val(telefono);
                    modal.find('.modal-body #nombre-cliente').val(nombre);
                    modal.find('.modal-body #cod_cliente').val(codigo);
                    modal.find('.modal-body [id^="nombreProducto"]').first().focus();
                    }else{
                        limpiarCamposCliente();
                        Swal.fire({
                            title: 'Error',
                            text: 'El cliente se encuentra inactivo.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }else{
                    limpiarCamposCliente();
                    Swal.fire({
                        title: 'Error',
                        text: 'Cliente no encontrado. Por favor, verifica la cédula.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            }, 'json');
        }
    });

    function limpiarCamposCliente() {
        var modal = $('#ventaModal');
        modal.find('.modal-body #numero-cliente').val('');
        modal.find('.modal-body #nombre-cliente').val('');
        modal.find('.modal-body #cod_cliente').val('');
    }


    $(document).ready(function() {
    $(document).on('input', '[id^=nombreProducto]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', '');
        var query = $(this).val();

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=venta',
                method: 'POST',
                data: {buscar: query},
                dataType: 'json', 
                success: function(data) {
                    var listaProductos = $('#lista-productos' + index);
                    listaProductos.empty(); 

                    if (data.length > 0) {
                        $.each(data, function(key, producto) {
                            var costo = parseFloat(producto.costo);
                            var precioVenta = costo + (costo * producto.porcen_venta / 100);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="'+producto.producto_nombre+'" ' +
                                'data-tipo="'+producto.excento+'" ' +
                                'data-codigo="'+producto.cod_presentacion+'" ' +
                                'data-marca="'+producto.marca+'" ' +
                                'data-stock="'+producto.total_stock+'" ' +
                                'data-unidad="'+producto.tipo_medida+'" ' +
                                'data-precio="'+precioVenta+'">' +
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion+' - '+precioVenta.toFixed(2) +'.Bs'+' - '+producto.total_stock+producto.tipo_medida+ '</a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                }
            });
        } else {
            $('#lista-productos' + index).fadeOut();
        }
    });
    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(){
        var selectedProduct = $(this).data('nombre'); 
        var codigo = $(this).data('codigo'); 
        var precio = $(this).data('precio'); 
        var tipo = $(this).data('tipo');
        var unidad = $(this).data('unidad');
        var stock=$(this).data('stock');
        var cant=1;


        var inputId = $(this).closest('.list-group').attr('id'); 
        var index = inputId.replace('lista-productos', ''); 

        $('#nombreProducto' + index).val(selectedProduct); 
        $('#codigoProducto' + index).val(codigo); 
        $('#tipoProducto' + index).val(tipo);
        $('#precioProducto' + index).val(precio.toFixed(2));
        $('#stockproducto'+index).val(stock);
        $('#unidadm'+index).text(unidad);
        $('#cantidadProducto' + index).val(cant).trigger('change');
        $(this).closest('.list-group').fadeOut(); 
    });
});

$(document).ready(function() {
    // Obtener la fecha y hora actual
    var now = new Date();
    var fecha = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0');

// Formatea la hora en el formato HH:MM:SS
    var hora=String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    
    var fechaHora = fecha + ' ' + hora;
    $('#fecha-hora').val(fechaHora);
});

$(function() {//inicio de los alertas peque;os
    var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
    });

$(document).ready(function() {
    $('#ventamodal').on('submit', function(event) {
        event.preventDefault();
        var datosVenta = $(this).serialize();
        console.log('form:', datosVenta);

        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=venta',
            data: datosVenta,
            dataType: 'json',
            success: function(response) {
                if (response.success===true) {
                    $('#ventaModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    console.log('cod:', response.cod_venta);
                    console.log('total:', response.total);
                    
                    $('#pagoModal').modal('show');
                    $('#nro-venta').val(response.cod_venta);
                    $('#nombre_cliente').val(response.cliente); 
                    $('#fecha_venta').val(response.fecha);
                    $('#total-pago').text(response.total+ ' Bs');
                    $('#monto_pagar').val(response.total);

                } else {
                    Swal.fire({
                        title: 'Error al registrar la venta',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function(jqXHR, xhr, status, error) {
                console.error('Estado:', status);
                console.error('Error:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                console.log('Response Text:', jqXHR.responseText);
                alert('Hubo un problema al registrar la venta. Inténtalo de nuevo.');
            }
        });
    });
});

});//fin de los alertas peque;os

function calcularTotalpago() {
    let totalBs = 0;
    // 1. Procesar las entradas que ya están en bolívares (sin conversión)
    document.querySelectorAll('.monto-bs:not(.monto-con)').forEach(function(input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;  // Sumar cada monto en bolívares directo
    });
    // 2. Procesar las entradas en divisas (convertirlas a bolívares)
    document.querySelectorAll('.monto-divisa').forEach(function(inputDivisa) {
        let index = inputDivisa.id.split('-').pop();  // Obtener el índice de la fila actual

        // Obtener el monto en divisa de la fila
        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        // Obtener la tasa de conversión de la misma fila
        let tasaConversion = parseFloat(document.getElementById('tasa-conversion-' + index).value) || 1;

        // Calcular el monto en bolívares
        let montoConvertidoBs = montoDivisa * tasaConversion;

        // Actualizar el campo de bolívares convertido en esa fila
        document.getElementById('monto-bs-con-' + index).value = montoConvertidoBs.toFixed(2);

        // Sumar al total de bolívares
        totalBs += montoConvertidoBs;
    });

    // 3. Mostrar el total en el campo "Monto Pagado"
    document.getElementById('monto_pagado').value = totalBs.toFixed(2);

    // 4. Calcular y mostrar la diferencia con el monto a pagar
    let montoPagar = parseFloat(document.getElementById('monto_pagar').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia').value = diferencia.toFixed(2);
}

$(document).ready(function() {
    $('#pagoModal').on('hidden.bs.modal', function () {
        // Recargar la página cuando el modal se cierra
        location.reload();
    });
});

$('#pagoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var total = button.data('totalv');
    var fecha = button.data('fecha');
    var nombre = button.data('nombre');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro-venta').val(codigo);
    modal.find('.modal-body #monto_pagar').val(total);
    modal.find('.modal-body #total-pago').text(total+ 'Bs');
    modal.find('.modal-body #fecha_venta').val(fecha);
    modal.find('.modal-body #nombre_cliente').val(nombre);
});

$('#abonoModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var codp=button.data('codpago');
    var total = button.data('totalv');
    var monto=button.data('montop');
    var fecha = button.data('fecha');
    var nombre = button.data('nombre');
    var mpagar=total-monto;
    console.log(button.data('codventa'));
    // Modal
    var modal = $(this);
    modal.find('.modal-body #nro-venta1').val(codigo);
    modal.find('.modal-body #monto_pagar1').val(mpagar.toFixed(2));
    modal.find('.modal-body #total-venta1').text(total+ 'Bs');
    modal.find('.modal-body #t-parcial').val(mpagar);
    modal.find('.modal-body #total-pago1').text(mpagar.toFixed(2)+ 'Bs');
    modal.find('.modal-body #fecha_venta1').val(fecha);
    modal.find('.modal-body #nombre_cliente1').val(nombre);
    modal.find('.modal-body #codigop').val(codp);

});

function calcularTotalpago1() {
    let totalBs = 0;

    // 1. Procesar las entradas que ya están en bolívares (sin conversión)
    document.querySelectorAll('.monto-bs1:not(.monto-con1)').forEach(function(input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;  // Sumar cada monto en bolívares directo
    });

    // 2. Procesar las entradas en divisas (convertirlas a bolívares)
    document.querySelectorAll('.monto-divisa1').forEach(function(inputDivisa) {
        let index = inputDivisa.id.split('-').pop();  // Obtener el índice de la fila actual

        // Obtener el monto en divisa de la fila
        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        // Obtener la tasa de conversión de la misma fila
        let tasaConversion = parseFloat(document.getElementById('tasa-conversion1-' + index).value) || 1;

        // Calcular el monto en bolívares
        let montoConvertidoBs = montoDivisa * tasaConversion;

        // Actualizar el campo de bolívares convertido en esa fila
        document.getElementById('monto-bs-con-1' + index).value = parseFloat(montoConvertidoBs.toFixed(2));

        // Sumar al total de bolívares
        totalBs += montoConvertidoBs;
    });

    // 3. Mostrar el total en el campo "Monto Pagado"
    document.getElementById('monto_pagado1').value = totalBs.toFixed(2);

    // 4. Calcular y mostrar la diferencia con el monto a pagar
    let montoPagar = parseFloat(document.getElementById('monto_pagar1').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia1').value = diferencia.toFixed(2);
}


$('#anularventa').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codventa');
    var status=button.data('status');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #cventa').val(codigo);
    modal.find('.modal-body #statusv').val(status);
    modal.find('.modal-body #codv').text(codigo);

});



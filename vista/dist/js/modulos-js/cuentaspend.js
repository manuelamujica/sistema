$(document).ready(function() {
    $('#detallemodal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var codCliente = button.data('cliente');
        console.log('Codigo cliente: ',codCliente);
        $('#detalleBody').empty();

        $.ajax({
            url: 'index.php?pagina=cuentaspend',
            method: 'POST',
            data: { detallecuenta: codCliente },
            dataType: 'json',
            success: function(data) {
                if (data.length === 0) {
                    $('#detalleBody').append('<tr><td colspan="9" class="text-center">No hay Vven pendientes.</td></tr>');
                } else {
                    $.each(data, function(index, c) {
                        $('#detalleBody').append(
                            '<tr>' +
                                '<td>' + c.cod_venta + '</td>' +
                                '<td>' + c.fecha + '</td>' +
                                '<td>' + c.total + '</td>' +
                                '<td>' + c.monto_pagado + '</td>' +
                                '<td>' + c.saldo_pendiente + '</td>' +
                                '<td>' + c.fecha_vencimiento + '</td>' +
                                '<td>' + c.dias_restantes + '</td>' +
                                '<td><span class="badge bg-' + (c.estado == '1' ? 'danger' : 'warning') + '">' + c.estado + '</span></td>' +
                                '<td>' +
                                    '<button class="btn-sm btn btn-primary">' +
                                        '<i class="fas fa-money-bill-wave"></i>' +
                                    '</button>' +
                                    '<button class="btn-sm btn btn-primary btn-factura"' +
                                        ' data-codventa="' + c.cod_venta + '"' +
                                        ' data-total="' + c.total + '"' +
                                        ' data-fecha="' + c.fecha + '"' +
                                        ' data-cliente="' + c.nombre + ' ' + c.apellido + '"' +
                                        ' data-cedula="' + c.cedula_rif + '"' +
                                        ' data-direccion="' + c.direccion + '"' +
                                        ' data-telefono="' + c.telefono + '">' +
                                        '<i class="fas fa-file-invoice"></i>' +
                                    '</button>' +
                                '</td>' +
                            '</tr>'
                        );
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar detalles:', error);
            }
        });
    });
});



// Abrir reporte PDF
$(document).on('click', '.btn-factura', function () {
    const datosFactura = {
        cod_venta: $(this).data('codventa'),
        total: $(this).data('total'),
        fecha: $(this).data('fecha'),
        cliente: $(this).data('cliente'),
        cedula: $(this).data('cedula'),
        direccion: $(this).data('direccion'),
        telefono: $(this).data('telefono')
    };

    // Crear el formulario
    const form = $('<form>', {
        action: 'index.php?pagina=factura',
        method: 'POST',
        target: '_blank'
    });

    // Agregar campos ocultos al formulario
    $.each(datosFactura, function (key, value) {
        form.append($('<input>', {
            type: 'hidden',
            name: key,
            value: value
        }));
    });

    // Agregar, enviar y eliminar el formulario
    $('body').append(form);
    form.submit();
    form.remove();
});

$(document).ready(function() {
    // Configuración inicial de DataTables
    $('#tablaCajas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        "order": [[0, "desc"]]
    });

    // Configurar fecha actual en el modal de abrir caja
    $('#modalAbrirCaja').on('show.bs.modal', function() {
        let now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('fecha_apertura').value = now.toISOString().slice(0, 16);
    });

    // Mostrar modal para editar caja
    $(document).on('click', '.editar-caja', function() {
        let cod_caja = $(this).data('codigo');
        let nombre = $(this).data('nombre');
        let divisa = $(this).data('divisa');
        let monto = $(this).data('monto');
        let estado = $(this).data('estado');
        
        $('#cod_caja_editar').val(cod_caja);
        $('#nombre_caja_editar').val(nombre);
        $('#cod_divisa_editar').val(divisa);
        $('#monto_apertura_editar').val(monto);
        $('#estado_caja_editar').val(estado);
        
        $('#modalEditarCaja').modal('show');
    });

    // Mostrar modal para eliminar caja
    $(document).on('click', '.eliminar-caja', function() {
        let cod_caja = $(this).data('codigo');
        let nombre = $(this).data('nombre');
        
        $('#cod_caja_eliminar').val(cod_caja);
        $('#nombre_caja_eliminar').text(nombre);
        
        $('#modalEliminarCaja').modal('show');
    });

    // Ver detalles/movimientos de la caja
    $(document).on('click', '.ver-detalle', function() {
        let cod_caja = $(this).data('codigo');
        let fila = $('#tablaCajas tr:has(td:contains("'+cod_caja+'"))');
        
        // Llenar información general de la caja
        $('#codigoCajaDetalle').text(cod_caja);
        $('#nombreCajaDetalle').text(fila.find('td:eq(1)').text());
        $('#usuarioCajaDetalle').text(fila.find('td:eq(2)').text());
        $('#divisaCajaDetalle').text(fila.find('td:eq(3)').text());
        $('#fechaAperturaDetalle').text(fila.find('td:eq(4)').text());
        $('#fechaCierreDetalle').text(fila.find('td:eq(5)').text());
        $('#montoAperturaDetalle').text(fila.find('td:eq(6)').text());
        $('#montoCierreDetalle').text(fila.find('td:eq(7)').text());
        
        let estado = fila.find('td:eq(8)').text().trim();
        $('#estadoDetalle').text(estado);
        $('#estadoDetalle').removeClass('badge-success badge-secondary').addClass(estado === 'Abierta' ? 'badge-success' : 'badge-secondary');
        
        // Cargar movimientos de la caja
        $.post('caja', {detalle_caja: cod_caja}, function(response) {
            let detalles = JSON.parse(response);
            let tabla = $('#tablaDetalleCaja tbody');
            tabla.empty();
            
            if (detalles.length > 0) {
                detalles.forEach(function(movimiento) {
                    let fila = $('<tr>');
                    fila.append($('<td>').text(movimiento.fecha));
                    fila.append($('<td>').text(movimiento.descripcion));
                    fila.append($('<td>').html(
                        movimiento.tipo === 'ingreso' ? 
                        '<span class="badge badge-success">Ingreso</span>' : 
                        '<span class="badge badge-danger">Egreso</span>'
                    ));
                    fila.append($('<td>').text(parseFloat(movimiento.monto).toFixed(2) + ' Bs'));
                    tabla.append(fila);
                });
            } else {
                tabla.append($('<tr>').append($('<td colspan="4" class="text-center">').text('No hay movimientos registrados')));
            }
            
            $('#modalDetalleCaja').modal('show');
        });
    });
});
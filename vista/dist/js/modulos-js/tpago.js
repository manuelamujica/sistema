$(document).ready(function() {
    console.log('js');
    
    // Evento para cambiar entre bancos y cajas según el tipo de moneda
    $('input[name="tipo_moneda"]').on('change', function() {
        var tipoMoneda = $(this).val();
        console.log(tipoMoneda);
        if (tipoMoneda == 2) { // Digital - Mostrar bancos
            $('.bancos-container').show();
            $('.cajas-container').hide();
            $('#banco').prop('required', true);
            $('#caja').prop('required', false);
        } else if (tipoMoneda == 1) { // Efectivo - Mostrar cajas
            $('.bancos-container').hide();
            $('.cajas-container').show();
            $('#banco').prop('required', false);
            $('#caja').prop('required', true);
        }
    });
    
    /* Verificar si el tipo de pago ya está registrado
    $('#nombre_tipo_pago').change(function (e){
        var buscar = $('#nombre_tipo_pago option:selected').text();
        $.post('index.php?pagina=tpago', {buscar}, function(response){
            if(response != ''){
                Swal.fire({
                    title: 'Advertencia',
                    text: 'El tipo de pago ya se encuentra registrado',
                    icon: 'warning'
                });
            }
        },'json');
    });*/

    // Código existente para editar modal
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var codigo = button.data('codigo');
        var tpago = button.data('medio');
        var status = button.data('status');
        var origin = button.data('medio');
        var nombre = button.data('desc');
        var cod=button.data('cod_metodo');
        // Modal
        var modal = $(this);
        modal.find('.modal-body #codigo').val(codigo);
        modal.find('.modal-body #tpago').val(tpago);
        modal.find('.modal-body #status').val(status);
        modal.find('.modal-body #origin').val(origin);
        modal.find('.modal-body #descripcion').val(nombre);
        modal.find('.modal-body #cod_metodo').val(cod);
    });

    // Código existente para eliminar modal
    $('#eliminartpago').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var medio = button.data('medio');
        var codigo = button.data('codigo');

        var modal = $(this);
        modal.find('#tpagonombre').text(medio);
        modal.find('.modal-body #tpagoCodigo').val(codigo);
    });

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

    // Por defecto mostrar bancos (digital está seleccionado por defecto)
    $('.bancos-container').show();
    $('.cajas-container').hide();
});
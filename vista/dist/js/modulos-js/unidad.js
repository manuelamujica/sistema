//Validar registrar
$('#tipo_medida1').blur(function (e) {
    var buscar = $('#tipo_medida1').val();
    $.post('index.php?pagina=unidad', { buscar }, function (response) {
        if (response != '') {
            Swal.fire({
                title: 'Error',
                text: 'La unidad de medida ya se encuentra registrada.',
                icon: 'warning'
            });
        }
    }, 'json');
});


//VALIDACIÓN
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


    $('#tipo_medida1').on('blur', function() {
        var tipo_medida1 = $(this).val();
        if(tipo_medida1.trim() === ''){
            hideError('#tipo_medida1');
        }else if (tipo_medida1.length > 10) {
            showError('#tipo_medida1', 'El texto no debe exceder los 10 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida1)) {
            showError('#tipo_medida1', 'Solo se permiten letras');
        } else {
            hideError('#tipo_medida1');
        }
    });

    $('#tipo_medida').on('blur', function() {
        var tipo_medida = $(this).val();
        if(tipo_medida.trim() === ''){
            hideError('#tipo_medida');
        }else if (tipo_medida.length > 10) {
            showError('#tipo_medida', 'El texto no debe exceder los 10 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida)) {
            showError('#tipo_medida', 'Solo se permiten letras');
        } else {
            hideError('#tipo_medida');
        }
    });
});

//EDITAR
$(document).ready(function () {
    // Cuando se hace clic en el botón de editar
    $('.editar').click(function () {
        var cod = $(this).data('cod');
        var tipo = $(this).data('tipo');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal
        $('#cod_unidad').val(cod);
        $('#cod_unidad_oculto').val(cod);
        $('#tipo_medida').val(tipo);
        $('#status').val(status);
        $('#origin').val(tipo);   
    });


});
//ELIMINAR
$('.eliminar').click(function () {
    var codUnidad = $(this).data('cod'); // Obtener el código de la unidad
    $('#cod_eliminar').val(codUnidad); // Asignar el valor al campo oculto
});






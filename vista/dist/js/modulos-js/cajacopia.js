//Validar registrar
$('#nombre1').blur(function (e) {
    var buscar = $('#nombre1').val();
    $.post('index.php?pagina=cajacopia', { buscar }, function (response) {
        if (response != '') {
            Swal.fire({
                title: 'Error',
                text: 'La caja ya se encuentra registrada.',
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


    $('#nombre1').on('blur', function() {
        var nombre1 = $(this).val();
        if(nombre1.trim() === ''){
            hideError('#nombre1');
        }else if (nombre1.length > 20) {
            showError('#nombre1', 'El texto no debe exceder los 20 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nombre1)) {
            showError('#nombre1', 'Solo se permiten letras');
        } else {
            hideError('#nombre1');
        }
    });

    $('#nombre').on('blur', function() {
        var tipo_medida = $(this).val();
        if(tipo_medida.trim() === ''){
            hideError('#nombre');
        }else if (tipo_medida.length > 10) {
            showError('#nombre', 'El texto no debe exceder los 20 caracteres'); // Validar longitud máxima
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida)) {
            showError('#nombre', 'Solo se permiten letras');
        } else {
            hideError('#nombre');
        }
    });
    
});
// Modificar la validación del saldo
$('#saldo').on('input', function() {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    if(this.value.trim() === '' || isNaN(this.value)) {
        showError('#saldo', 'Ingrese un valor numérico válido');
    } else {
        hideError('#saldo');
    }
});
//EDITAR
$(document).ready(function () {
    // Cuando se hace clic en el botón de editar
    $('.editar').click(function () {
        var cod = $(this).data('cod');
        var nombre = $(this).data('nombre');
        var saldo = $(this).data('saldo');
        var divisa = $(this).data('divisa');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal
        $('#cod_caja').val(cod);
        $('#cod_caja_oculto').val(cod);
        $('#nombre').val(nombre);
        $('#saldo').val(saldo);
        $('#divisa').val(divisa);
        $('#status').val(status);
      
    });


});
//ELIMINAR
$('#modaleliminar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('cod');
    var status = button.data('status');
    var nombre = button.data('nombre'); 

    var modal = $(this);
    modal.find('.modal-body #cod_eliminar').val(codigo);
    modal.find('#nombreD').text(nombre);
    modal.find('.modal-body #status_e').val(codigo);

    console.log(nombre,codigo);
});





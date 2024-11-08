//BUSCAR
$('#tipo_medida1').blur(function (e) {
    var buscar = $('#tipo_medida1').val();
    $.post('index.php?pagina=unidad', { buscar }, function (response) {
        if (response != '') {
            Swal.fire({
                title: 'Error',
                text: 'La unidad de medida ya se encuentra registrada.',
                icon: 'error'
            });
        }
    }, 'json');
});

//VALIDACIÓN
function validacion() {

    const valor = document.getElementById("tipo_medida1").value;
    if (valor == null || valor.length === 0 || /^\s+$/.test(valor)) {
        Swal.fire({
            title: 'Error',
            text: 'El campo Unindad de medida es obligatorio y no puede estar vacío.',
            icon: 'warning'
        });
        return false; // Evita el envío del formulario
    }

    // Verificar si el valor contiene solo caracteres especiales
    if (/^[^a-zA-Z\s]+$/.test(valor)) {
        Swal.fire({
            title: 'Error',
            text: 'El campo Unindad de medida no puede contener solo caracteres especiales.',
            icon: 'warning'
        });
        return false; // Evita el envío del formulario
    }


    return true; // Permite el envío del formulario

};

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






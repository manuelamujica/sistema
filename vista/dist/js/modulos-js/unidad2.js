//BUSCAR
$('#tipo_medida').blur(function (e){
    var buscar=$('#tipo_medida').val();
    $.post('index.php?pagina=unidad', {buscar}, function(response){
    if(response != ''){
        alert('La unidad de medida ya se encuentra registrada');
    }
    },'json');
});
//EDITAR
$(document).ready(function() {
    // Cuando se hace clic en el botón de editar
    $('.editar').click(function() {
        // Obtener los datos del botón
        var cod = $(this).data('cod');
        var tipo = $(this).data('tipo');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal
        $('#cod_unidad').val(cod);
        $('#cod_unidad_oculto').val(cod);
        $('#tipo_medida').val(tipo).data('tipoOriginal', tipo); // Guardar el tipo original
        $('#status').val(status);
    });

    // Validación al enviar el formulario de edición
    $('#form-editar-unidad').submit(function(e) {
        const tipoMedidaActual = $('#tipo_medida').val();
        const tipoMedidaOriginal = $('#tipo_medida').data('tipoOriginal');

        // Validaciones                                                                    NO ENTIENDO !?
        if (tipoMedidaActual !== tipoMedidaOriginal) {
            alert('No puedes cambiar el nombre de la unidad de medida.');
            e.preventDefault(); // Evitar el envío del formulario
            return false;
        }

        // Validación adicional para el campo 'Tipo de Medida'
        if (!tipoMedidaActual || /^\s*$/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' es obligatorio y no puede estar vacío.");
            e.preventDefault(); // Evitar el envío del formulario
            return false; // Evitar el envío del formulario
        }

        if (/\d/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' no puede contener números.");
            e.preventDefault(); // Evitar el envío del formulario
            return false; // Evitar el envío del formulario
        }

        // Verificar si el valor contiene solo caracteres especiales
        if (/^[^a-zA-Z0-9\s]+$/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' no puede contener solo caracteres especiales.");
            e.preventDefault(); // Evitar el envío del formulario
            return false; // Evitar el envío del formulario
        }

        // Si todas las validaciones pasan, permitir el envío del formulario
        return true;
    });
});
//ELIMINAR
$('.eliminar').click(function(){
    var codUnidad = $(this).data('cod'); // Obtener el código de la unidad
    $('#cod_eliminar').val(codUnidad); // Asignar el valor al campo oculto
});

//VALIDACIÓN
function validacion(){
    
    const valor = document.getElementById("tipo_medida1").value;
    if (valor == null || valor.length === 0 || /^\s+$/.test(valor)) {
        alert("El campo 'Tipo de Medida' es obligatorio y no puede estar vacío.");
        return false; // Evita el envío del formulario
    }
    if (/\d/.test(valor)) {
        alert("El campo 'Tipo de Medida' no puede contener números.");
        return false; // Evita el envío del formulario
    }

    // Verificar si el valor contiene solo caracteres especiales
    if (/^[^a-zA-Z0-9\s]+$/.test(valor)) {
        alert("El campo 'Tipo de Medida' no puede contener solo caracteres especiales.");
        return false; // Evita el envío del formulario
    }
    return true; // Permite el envío del formulario
    
};

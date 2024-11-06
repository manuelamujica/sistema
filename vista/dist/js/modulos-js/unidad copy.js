//BUSCAR
$('#tipo_medida1').blur(function (e){
    var buscar=$('#tipo_medida1').val();
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
        var cod = $(this).data('cod');
        var tipo = $(this).data('tipo');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal
        $('#cod_unidad').val(cod);
        $('#cod_unidad_oculto').val(cod);
        $('#tipo_medida').val(tipo).data('tipoOriginal', tipo); // Guardar el tipo original
        $('#status').val(status);
    });

    $('#form-editar-unidad').submit(function(e) {
        e.preventDefault(); 

        const tipoMedidaActual = $('#tipo_medida').val();
        const tipoOriginal = $('#tipo_medida').data('tipoOriginal');

        // Validaciones
        if (!tipoMedidaActual || /^\s*$/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' es obligatorio y no puede estar vacío.");
            return false; // Evitar el envío del formulario
        }

        if (/\d/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' no puede contener números.");
            return false; 
        }

        if (/^[^a-zA-Z0-9\s]+$/.test(tipoMedidaActual)) {
            alert("El campo 'Tipo de Medida' no puede contener solo caracteres especiales.");
            return false; 
        }

        // Verificación de unidad existente solo si se cambia el tipo de medida
        if (tipoMedidaActual !== $('#tipo_medida').data('tipoOriginal')) {
            $.post('index.php?pagina=unidad', { buscar: tipoMedidaActual }, function(response) {
                if (response != '') {
                    alert('La unidad de medida ya se encuentra registrada');
                    return false; // Evitar el envío del formulario
                } else {
                    // Si no hay respuesta, enviar el formulario mediante AJAX
                    enviarFormulario();
                }
            }, 'json');
        } else {
            // Si no se cambia el tipo de medida, simplemente enviar el formulario
            enviarFormulario();
        }
    });

    function enviarFormulario() {
        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=unidad',
            data: $('#form-editar-unidad').serialize() + '&editar=true', // Agregar campo 'editar'
            success: function(response) {
                alert('Unidad de medida editada con éxito');
                location.reload(); // Recargar la página para ver los cambios
            },
            error: function() {
                alert('Error al editar la unidad de medida');
            }
        });
    }
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

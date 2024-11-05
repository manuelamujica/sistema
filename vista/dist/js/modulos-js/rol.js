$('#rol1').blur(function (e){
    var buscar=$('#rol1').val();
    $.post('index.php?pagina=roles', {buscar}, function(response){
    if(response != ''){
        alert('Este rol ya se encuentra registrado');
    }
    },'json');
});

//VALIDACIÓN
function validacion(){
    
    const valor = document.getElementById("rol1").value;
    if (valor == null || valor.length === 0 || /^\s+$/.test(valor)) {
        alert("El campo 'Rol' es obligatorio y no puede estar vacío.");
        return false; // Evita el envío del formulario
    }
    
    // Verificar si el valor contiene solo caracteres especiales
    if (/^[^a-zA-Z\s]+$/.test(valor)) {
        alert("El campo 'Rol' no puede contener solo caracteres especiales.");
        return false; // Evita el envío del formulario
    }

     //Validar los checkboxes
     const checkboxes = document.querySelectorAll('input[name="permisos[]"]');
     const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
     
     if (!isChecked) {
         alert("Debes seleccionar al menos un permiso.");
         return false; // Evita el envío del formulario
     }

    return true; // Permite el envío del formulario
    
};  

//EDITAR NO QUIERE NADA
$(document).ready(function() {
    $('.editar').click(function() {
        // Obtener los datos del botón
        var cod = $(this).data('cod');
        var rol = $(this).data('rol');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal
        $('#cod').val(cod);
        $('#cod_oculto').val(cod);
        $('#rol').val(rol);
        $('#status').val(status);
        $('#rol_origin').val(rol);

    });
});


    //ELIMINAR
    $('.eliminar').click(function(){
        //ESTE SI FUNCIONA
        var cod = $(this).data('cod'); // Obtener el código de la unidad
        $('#cod_eliminar').val(cod); // Asignar el valor al campo oculto
    });
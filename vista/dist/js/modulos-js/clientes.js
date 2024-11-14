

$('#editModal').on('show.bs.modal', function (event) {
        var button=$(event.relatedTarget);
        var codigo=button.data('codigo');
        var nombre=button.data('nombre');
        var apellido=button.data('apellido');
        var cedula_rif=button.data('cedula_rif');
        var telefono=button.data('telefono');
        var email=button.data('email');
        var direccion=button.data('direccion');
        var status=button.data('status');
        var origin=button.data('cedula_rif');

        // Modal
        var modal = $(this);
        modal.find('.modal-body #codigo').val(codigo);
        modal.find('.modal-body #nombre1').val(nombre);
        modal.find('.modal-body #apellido1').val(apellido);
        modal.find('.modal-body #cedularif1').val(cedula_rif);
        modal.find('.modal-body #telefono1').val(telefono);
        modal.find('.modal-body #email1').val(email);
        modal.find('.modal-body #direccion1').val(direccion);
        modal.find('.modal-body #status').val(status);
        modal.find('.modal-body #origin').val(origin);
    });

$('#eliminarcliente').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var nombre = button.data('nombre');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#clienteNombre').text(nombre);
    modal.find('.modal-body #clienteCodigo').val(codigo);
});
$('#cedula_rif').blur(function (e){
    var buscar=$('#cedula_rif').val();
    $.post('index.php?pagina=clientes', {buscar}, function(response){
        if(response != ''){
            alert('El cliente ya se encuentra registrado');
        }
    },'json');
});

$('#cedularif').blur(function (e){
    var buscar=$('#cedularif').val();
    $.post('index.php?pagina=clientes', {buscar}, function(response){
        if(response != ''){
            alert('La cedula ya existe');
        }
    },'json');
});

//validacions del registro del cliente
$(document).ready(function() {
    // Validación de Cédula o Rif
    $('#cedula_rif').on('input', function() {
        var cedula_rif = $(this).val();
        if (!/^[\w\s()+-]*$/.test(cedula_rif) || cedula_rif.length < 2 || cedula_rif.length > 12) {
            showError('#cedula_rif', 'debe contener maximo 12 números');
        } else {
            hideError('#cedula_rif');
        }
    });

    // Validación de Nombre
    $('#nombre').on('blur', function() {
        var nombre = $(this).val();
        if (!nombre || !/^[a-zA-Z\s]+$/.test(nombre) || nombre.length < 1 || nombre.length > 12) {
            showError('#nombre', 'debe contener  minimo 6 y maximo  12  letras ');
        } else {
            hideError('#nombre');
        }
    });

    // Validación de Apellido
    $('#apellido').on('blur', function() {
        var apellido = $(this).val();
        if (!apellido || !/^[a-zA-Z\s]+$/.test(apellido) || apellido.length < 1 || apellido.length > 12) {
            showError('#apellido', 'debe contener entre minimo 6 o 12 letras ');
        } else {
            hideError('#apellido');
        }
    });

    // Validación de Teléfono
    $('#telefono').on('blur', function() {
        var telefono = $(this).val();
        if (!telefono || !/^[\d\s()+-]*$/.test(telefono)) {
            showError('#telefono', 'solo números');
        } else {
            hideError('#telefono');
        }
    });

   
    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toLowerCase()).css({
            'display': 'block',
            'color': 'red',
            'background-color': 'white'
        });
    }

   
    function hideError(selector) {
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display', 'none');
    }
});





//validacion de editar cliente 
$(document).ready(function() {
    // Validación de Nombre
    $('#nombre1').on('blur', function() {
        var nombre = $(this).val();
        if (!nombre) {
            showError('#nombre1', 'Este campo no puede estar vacío');
        } else if (!/^[a-zA-Z\s]+$/.test(nombre) || nombre.length > 12) {
            showError('#nombre1', 'Solo letras y espacios, máximo 12 caracteres');
        } else {
            hideError('#nombre1');
        }
        toggleEditButton();
    });

    // Validación de Apellido
    $('#apellido1').on('blur', function() {
        var apellido = $(this).val();
        if (!apellido) {
            showError('#apellido1', 'Este campo no puede estar vacío');
        } else if (!/^[a-zA-Z\s]+$/.test(apellido) || apellido.length > 12) {
            showError('#apellido1', 'Solo letras y espacios, máximo 12 caracteres');
        } else {
            hideError('#apellido1');
        }
        toggleEditButton();
    });

    // Validación de Cédula-Rif
    $('#cedularif1').on('blur', function() {
        var cedularif = $(this).val();
        if (!cedularif) {
            showError('#cedularif1', 'Este campo no puede estar vacío');
        } else if (!/^[\w\s-]+$/.test(cedularif)) {
            showError('#cedularif1', 'Solo letras, números y caracteres especiales permitidos');
        } else {
            hideError('#cedularif1');
        }
        toggleEditButton();
    });

    // Validación de Teléfono
    $('#telefono1').on('blur', function() {
        var telefono = $(this).val();
        if (telefono && !/^[\d\s()+-]*$/.test(telefono)) {
            showError('#telefono1', 'Solo números y caracteres especiales permitidos');
        } else {
            hideError('#telefono1');
        }
        toggleEditButton();
    });

    // Función para mostrar el error
    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toLowerCase()).css({
            'display': 'block',
            'color': 'red',
            'background-color': 'white'
        });
    }

    // Función para ocultar el error
    function hideError(selector) {
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display', 'none');
    }

    // Función para habilitar o deshabilitar el botón de editar
    function toggleEditButton() {
        var isValid = true;
        $('#editForm input[required]').each(function() {
            if ($(this).hasClass('is-invalid') || !$(this).val()) {
                isValid = false;
            }
        });
        $('button[name="actualizar"]').prop('disabled', !isValid);
    }
});
// Validar entradas
$(document).ready(function() {

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

    // Registrar
    $('#nombre').on('blur', function() {
        var nombre = $(this).val();
        if (nombre.trim() === '') {
            hideError('#nombre'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
            showError('#nombre', 'Solo letras');
        } else {
            hideError('#nombre');
        }
    });

    $('#user').on('blur', function() {
        var user = $(this).val();
        if (user.trim() === '') {
            hideError('#user'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z0-9]+$/.test(user)) {
            showError('#user', 'Solo letras y numeros');
        } else {
            hideError('#user');
        }
    });

    //Validar password REGISTRAR
    $('#passU').on('blur', function(e) {
        var user = $('#user').val();
        var password = $('#passU').val();
        var isValid = true;

        if (password.trim() === '') {
            hideError('#passU'); // Si está vacío, oculta el error

        }else if (password.length < 8) {
            showError('#passU', 'La contraseña debe tener al menos 8 caracteres');
            isValid = false;
        } else if (password === user) {
            showError('#passU', 'La contraseña no puede ser igual al nombre de usuario');
            isValid = false;
        } else if (!/[!@#$%^&*()/,.?":{}|<>]/.test(password)) {
            showError('#passU', 'La contraseña debe contener al menos un carácter especial');
            isValid = false;
        } else {
            hideError('#passU');
        }

        if (!isValid) {
            e.preventDefault(); // Previene el envío del formulario si hay errores
        }
    });

    // Editar
    $('#nombreE').on('blur', function() {
        var nombre = $(this).val();
        if (nombre.trim() === '') {
            hideError('#nombreE'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
            showError('#nombreE', 'Solo letras');
        } else {
            hideError('#nombreE');
        }
    });

    $('#userE').on('blur', function() {
        var user = $(this).val();
        if (user.trim() === '') {
            hideError('#userE'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z0-9]+$/.test(user)) {
            showError('#userE', 'Solo letras y numeros');
        } else {
            hideError('#userE');
        }
    });

//Validar password EDITAR
$('#passE').on('blur', function(e) {
    var user = $('#userE').val();
    var password = $('#passE').val();
    var isValid = true;

    if (password.trim() === '') {
        hideError('#passE'); // Si está vacío, oculta el error

    }else if (password.length < 8) {
        showError('#passE', 'La contraseña debe tener al menos 8 caracteres');
        isValid = false;
    } else if (password === user) {
        showError('#passE', 'La contraseña no puede ser igual al nombre de usuario');
        isValid = false;
    } else if (!/[!@#$%^&*()/,.?":{}|<>]/.test(password)) {
        showError('#passE', 'La contraseña debe contener al menos un carácter especial');
        isValid = false;
    } else {
        hideError('#passE');
    }

    if (!isValid) {
        e.preventDefault(); // Previene el envío del formulario si hay errores
    }
});


});

//Validar USER existentes en registro
$('#user').blur(function (e){
    var buscar=$('#user').val();
    $.post('index.php?pagina=usuarios', {buscar}, function(response){
        if(response != ''){
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'El usuario ya se encuentra registrado',
                confirmButtonText: 'Aceptar'
            });
        }
    },'json');
});


//Validar USER existentes en editar
$('#userE').blur(function (e){
    var buscar=$('#userE').val();
    $.post('index.php?pagina=usuarios', {buscar}, function(response){

        if(response != ''){
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'El usuario ya se encuentra registrado',
                confirmButtonText: 'Aceptar'
            });
        }
    },'json');
});

//Editar
$('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var codigo = button.data('codigo');
        var nombre = button.data('nombre');
        var user = button.data('user'); 
        var rol = button.data('cod'); 
        var status = button.data('status');
        var origin = button.data('user');
        
        // Modal
        var modal = $(this); 
        modal.find('.modal-body #codigo').val(codigo);
        modal.find('.modal-body #nombreE').val(nombre);
        modal.find('.modal-body #userE').val(user);
        modal.find('.modal-body #rolesE').val(rol);            
        modal.find('.modal-body #statusE').val(status);
        modal.find('.modal-body #origin').val(origin); // Campo oculto con el valor original del user
    });

//Contrasena
$(document).ready(function() {
    // Al hacer clic en el botón "Cambiar contraseña"
    $('#changePasswordBtn').on('click', function() {
        // alterna la visibilidad del campo de la nueva contraseña.
        $('#passwordField').toggle(); 
    });
});

//Eliminar
$('#eliminarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');      

    var modal = $(this);
    modal.find('#username').text(nombre);
    modal.find('.modal-body #usercode').val(codigo);
});

/*OJITO CONTRASEÑA (LOGIN Y USUARIO)*/
const icons = document.querySelectorAll('.icon-password');

icons.forEach(icon => {
    icon.addEventListener('click', function() {
        const inputId = this.dataset.target; //Data-target con el ID del input
        const passInput = document.getElementById(inputId);
        
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});


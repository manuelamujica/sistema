// Validaciones y funciones generales para cuentas bancarias
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

    /*============================================
    VALIDACIONES PARA EL FORMULARIO DE REGISTRO
    ============================================*/
    $('#numero_cuenta').on('blur', function() {
        var numero = $(this).val();
        if (numero.trim() === '') {
            showError('#numero_cuenta', 'El número de cuenta es requerido');
        } else if (!/^[0-9]+$/.test(numero)) {
            showError('#numero_cuenta', 'Solo se permiten números');
        } else if (numero.length < 5 || numero.length > 20) {
            showError('#numero_cuenta', 'El número debe tener entre 5 y 20 dígitos');
        } else {
            hideError('#numero_cuenta');
        }
    });

    $('#saldo').on('blur', function() {
        var saldo = $(this).val();
        if (saldo.trim() === '') {
            showError('#saldo', 'El saldo es requerido');
        } else if (!/^\d+(\.\d{1,2})?$/.test(saldo)) {
            showError('#saldo', 'Formato inválido. Ej: 1000.50');
        } else if (parseFloat(saldo) < 0) {
            showError('#saldo', 'El saldo no puede ser negativo');
        } else {
            hideError('#saldo');
        }
    });

    /*============================================
    VALIDACIONES PARA EL FORMULARIO DE EDICIÓN
    ============================================*/
    $('#numero_cuentaE').on('blur', function() {
        var numero = $(this).val();
        if (numero.trim() === '') {
            showError('#numero_cuentaE', 'El número de cuenta es requerido');
        } else if (!/^[0-9]+$/.test(numero)) {
            showError('#numero_cuentaE', 'Solo se permiten números');
        } else if (numero.length < 5 || numero.length > 20) {
            showError('#numero_cuentaE', 'El número debe tener entre 5 y 20 dígitos');
        } else {
            hideError('#numero_cuentaE');
        }
    });

    $('#saldoE').on('blur', function() {
        var saldo = $(this).val();
        if (saldo.trim() === '') {
            showError('#saldoE', 'El saldo es requerido');
        } else if (!/^\d+(\.\d{1,2})?$/.test(saldo)) {
            showError('#saldoE', 'Formato inválido. Ej: 1000.50');
        } else if (parseFloat(saldo) < 0) {
            showError('#saldoE', 'El saldo no puede ser negativo');
        } else {
            hideError('#saldoE');
        }
    });

    /*============================================
    MODAL DE EDICIÓN - CARGAR DATOS
    ============================================*/
    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        
        var modal = $(this);
        modal.find('.modal-body #cod_cuenta').val(button.data('codigo'));
        modal.find('.modal-body #bancoE').val(button.data('banco'));
        modal.find('.modal-body #tipo_cuentaE').val(button.data('tipocuenta'));
        modal.find('.modal-body #numero_cuentaE').val(button.data('numero'));
        modal.find('.modal-body #saldoE').val(button.data('saldo'));
        modal.find('.modal-body #divisaE').val(button.data('divisa'));
        modal.find('.modal-body #statusE').val(button.data('status'));
    });

    /*============================================
    MODAL DE ELIMINACIÓN - CARGAR DATOS
    ============================================*/
    $('#eliminarModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var codigo = button.data('codigo');
        var banco = button.data('banco');

        var modal = $(this);
        modal.find('#nombre_banco').text(banco);
        modal.find('.modal-body #cod_cuenta_eliminar').val(codigo);
    });

    /*============================================
    BOTÓN DESHACER - LIMPIAR FORMULARIO
    ============================================*/
    $('#deshacer').on('click', function() {
        // Restablecer formulario de registro
        $('#formRegistrarCuenta')[0].reset();
        $('.invalid-feedback').css('display', 'none');
        $('.is-invalid').removeClass('is-invalid');
    });

    /*============================================
    VALIDAR FORMULARIOS ANTES DE ENVIAR
    ============================================*/
    $('#formRegistrarCuenta').on('submit', function(e) {
        var valid = true;
        
        // Validar número de cuenta
        if ($('#numero_cuenta').val().trim() === '' || $('#numero_cuenta').hasClass('is-invalid')) {
            showError('#numero_cuenta', 'Número de cuenta inválido');
            valid = false;
        }
        
        // Validar saldo
        if ($('#saldo').val().trim() === '' || $('#saldo').hasClass('is-invalid')) {
            showError('#saldo', 'Saldo inválido');
            valid = false;
        }
        
        // Validar selects
        if ($('#banco').val() === null || $('#banco').val() === '') {
            showError('#banco', 'Seleccione un banco');
            valid = false;
        }
        
        if ($('#tipo_cuenta').val() === null || $('#tipo_cuenta').val() === '') {
            showError('#tipo_cuenta', 'Seleccione un tipo de cuenta');
            valid = false;
        }
        
        if ($('#divisa').val() === null || $('#divisa').val() === '') {
            showError('#divisa', 'Seleccione una divisa');
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor complete correctamente todos los campos',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    $('#editForm').on('submit', function(e) {
        var valid = true;
        
        // Validar número de cuenta
        if ($('#numero_cuentaE').val().trim() === '' || $('#numero_cuentaE').hasClass('is-invalid')) {
            showError('#numero_cuentaE', 'Número de cuenta inválido');
            valid = false;
        }
        
        // Validar saldo
        if ($('#saldoE').val().trim() === '' || $('#saldoE').hasClass('is-invalid')) {
            showError('#saldoE', 'Saldo inválido');
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor complete correctamente todos los campos',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});


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

    // Validar nombre en modal registrar
    $('#nombre').on('blur', function() {
        var tipo_medida = $(this).val();
        if(tipo_medida.trim() === ''){
            hideError('#nombre');
        }else if (tipo_medida.length > 20) {
            showError('#nombre', 'El texto no debe exceder los 20 caracteres');
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(tipo_medida)) {
            showError('#nombre', 'Solo se permiten letras');
        } else {
            hideError('#nombre');
        }
    });

    // Validar nombre en modal editar
    $('#nombre1').on('blur', function() {
        var nombre1 = $(this).val();
        if(nombre1.trim() === ''){
            hideError('#nombre1');
        }else if (nombre1.length > 20) {
            showError('#nombre1', 'El texto no debe exceder los 20 caracteres');
        } else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nombre1)) {
            showError('#nombre1', 'Solo se permiten letras');
        } else {
            hideError('#nombre1');
        }
    });
    
    // Validar saldo en modal registrar
    $('#saldo').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        if(this.value.trim() === '' || isNaN(this.value)) {
            showError('#saldo', 'Ingrese un valor numérico válido');
        } else {
            hideError('#saldo');
        }
    });

    // Validar saldo en modal editar
    $('#saldo1').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        if(this.value.trim() === '' || isNaN(this.value)) {
            showError('#saldo1', 'Ingrese un valor numérico válido');
        } else {
            hideError('#saldo1');
        }
    });

    // Validar si la caja ya existe (registrar)
    $('#nombre').blur(function (e) {
        var buscar = $('#nombre').val();
        if(buscar.trim() !== '') {
            $.post('index.php?pagina=cajacopia', { buscar }, function (response) {
                if (response != '') {
                    showError('#nombre', 'La caja ya está registrada');
                    Swal.fire({
                        title: 'Error',
                        text: 'La caja ya se encuentra registrada.',
                        icon: 'warning'
                    });
                }
            }, 'json');
        }
    });

    //EDITAR - Cargar datos en modal
    $('.editar').click(function () {
        var cod = $(this).data('cod');
        var nombre = $(this).data('nombre');
        var saldo = $(this).data('saldo');
        var divisa = $(this).data('divisa');
        var status = $(this).data('status');

        // Asignar los valores al formulario del modal de edición
        $('#modalmodificarcaja #cod_caja').val(cod);
        $('#modalmodificarcaja #nombre1').val(nombre);
        $('#modalmodificarcaja #saldo1').val(saldo);
        $('#modalmodificarcaja #divisa1').val(divisa);
        $('#modalmodificarcaja #status').val(status);
    });

    // Validar formulario de edición antes de enviar
    $('#form-editar-caja').submit(function(e) {
        var isValid = true;
        
        // Validar nombre
        if ($('#nombre1').val().trim() === '') {
            showError('#nombre1', 'El nombre es requerido');
            isValid = false;
        }
        
        // Validar saldo
        if ($('#saldo1').val().trim() === '' || isNaN($('#saldo1').val())) {
            showError('#saldo1', 'Ingrese un saldo válido');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        return true;
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

    
});
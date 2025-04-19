$(document).ready(function() {
    // Editar cuenta bancaria - llenar datos en modal
    $('#editModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        
        let codigo = button.data('codigo');
        let numeroCuenta = button.data('nombre');
        let tipoCuenta = button.data('user');
        let banco = button.data('rol');

        let modal = $(this);
        modal.find('#codigo').val(codigo);
        modal.find('#numero_cuentaE').val(numeroCuenta);
        modal.find('#tipo_cuentaE').val(tipoCuenta);
        modal.find('#bancoE').val(banco);
    });

    // Eliminar cuenta bancaria - llenar datos en modal
    $('#eliminarModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let codigo = button.data('codigo');
        let nombre = button.data('nombre');

        let modal = $(this);
        modal.find('#usercode').val(codigo);
        modal.find('#username').text(nombre);
    });

    // Validación del formulario registrar
    $('#formregistrarusuario').on('submit', function() {
        let banco = $('#banco').val();
        let tipoCuenta = $('#tipo_cuenta').val();
        let numeroCuenta = $('#numero_cuenta').val();
        let saldo = $('#saldo').val();
        let divisa = $('#divisa').val();

        if (!banco || !tipoCuenta || !numeroCuenta || !saldo || !divisa) {
            Swal.fire({
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos obligatorios.',
                icon: 'warning'
            });
            return false;
        }
    });

    // Validación del formulario editar
    $('#editForm').on('submit', function() {
        let banco = $('#bancoE').val();
        let tipoCuenta = $('#tipo_cuentaE').val();
        let numeroCuenta = $('#numero_cuentaE').val();
        let saldo = $('#saldoE').val();
        let divisa = $('#divisaE').val();
        let status = $('#status').val();

        if (!banco || !tipoCuenta || !numeroCuenta || !saldo || !divisa || !status) {
            Swal.fire({
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos en el formulario de edición.',
                icon: 'warning'
            });
            return false;
        }
    });
});

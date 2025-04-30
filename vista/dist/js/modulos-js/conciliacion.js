$(document).ready(function() {
    // Mostrar nombre del archivo seleccionado
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    // Validar formulario antes de enviar
    $('#formSubirExtracto').on('submit', function(e) {
        let isValid = true;
        
        if ($('#pdfFile').val() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe seleccionar un archivo PDF',
                confirmButtonText: 'Aceptar'
            });
            isValid = false;
        }
        
        if ($('#cuentaBancaria').val() === null) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe seleccionar una cuenta bancaria',
                confirmButtonText: 'Aceptar'
            });
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Confirmar importación de transacciones
    $('#btnConfirmarImportacion').on('click', function() {
        const cuentaId = $('#cuentaBancaria').val();
        const transactions = <?php echo json_encode($transactions ?? []); ?>;
        
        if (transactions.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No hay transacciones para importar',
                confirmButtonText: 'Aceptar'
            });
            return;
        }
        
        Swal.fire({
            title: 'Confirmar importación',
            html: `¿Está seguro que desea importar <b>${transactions.length} transacciones</b> a la cuenta seleccionada?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, importar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                importarTransacciones(cuentaId, transactions);
            }
        });
    });
    
    function importarTransacciones(cuentaId, transactions) {
        $.ajax({
            url: 'controlador/importarextracto.php?action=importar',
            method: 'POST',
            data: {
                cuentaId: cuentaId,
                transactions: transactions
            },
            dataType: 'json',
            beforeSend: function() {
                $('#btnConfirmarImportacion').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Importando...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Importación exitosa',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            },
            complete: function() {
                $('#btnConfirmarImportacion').prop('disabled', false).html('<i class="fas fa-check mr-2"></i> Confirmar importación');
            }
        });
    }
});
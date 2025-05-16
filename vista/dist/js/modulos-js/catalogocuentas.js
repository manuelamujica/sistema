$(document).ready(function () {
    $('#nivel').on('change', function () {
        var nivel = parseInt($(this).val());
            // Reiniciar campos visuales
            $('#codigoContable').val('');
            $('#grupoNaturaleza').hide();
            $('#grupoCuentaPadre').hide();
            $('#listaPadres').empty();
            $('#naturaleza').val('').prop('disabled', true);

            if (nivel === 1) {
                // ES CUENTA PADRE / GENERAR NUEVO CODIGO
                $('#grupoNaturaleza').show();
                $('#naturaleza').prop('disabled', false);

                const codPadre = $('#listaPadres').val();
                const nivel = $('#nivel').val();

                console.log('codPadre:', codPadre, 'nivel:', nivel);
                $.ajax({
                    url: 'index.php?pagina=catalogocuentas',
                    method: 'POST',
                    data: { generarRaiz: true,
                            cod_padre: codPadre,
                            nivel: nivel },
                    dataType: 'json',
                    success: function(data) {
                        console.log('Respuesta JSON:', data); // NULL
                        $('#codigoContable').val(data);
                    },
                    error: function() {
                        $('#codigoContable').val('Error al generar código raíz');
                    }
                });

            } else if (nivel > 1) {
            $('#grupoCuentaPadre').show();
            $('#grupoNaturaleza').show();
            //$('#naturaleza').prop('readonly', true);
            //TRAIGO LAS CUENTAS PADRES POR NIVEL
            $.ajax({
                url: 'index.php?pagina=catalogocuentas',
                method: 'POST',
                data:{ padre:nivel},
                dataType: 'json',
                success: function(data) {
                    const listaPadres = $('#listaPadres');
                    listaPadres.empty();
                    listaPadres.append('<option value="">Seleccione una cuenta padre</option>');
                    
                    if (data.length > 0) {
                        $.each(data, function(index, cuenta) {
                            listaPadres.append(
                                '<option value="' + cuenta.cod_cuenta + '" data-naturaleza="' + cuenta.naturaleza +  '">' +
                                cuenta.codigo_contable + ' - ' + cuenta.nombre_cuenta +
                                '</option>'
                            );
                        });
                    } else {
                        listaPadres.append('<option value="">No hay cuentas de nivel ' + (nivel - 1) + '</option>');
                    }
                },
                error: function() {
                    $('#listaPadres').html('<option value="">Error al cargar cuentas</option>');
                }
            });
        } else {
            $('#grupoCuentaPadre').hide();
            $('#listaPadres').empty();
        }
    });

    $('#listaPadres').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        const naturaleza = selectedOption.data('naturaleza');

        if (naturaleza) {
            $('#naturalezaHidden').val(naturaleza); // valor que va al backend
            $('#naturaleza').val(naturaleza); // valor visible
        } else {
            $('#naturalezaHidden').val('');
            $('#naturaleza').val('');
        }
        generarCodigoHija();
    });

    // GENERAR CODIGO CONTABLE HIJA
    function generarCodigoHija() {
        const codPadre = $('#listaPadres').val();
        const nivel = $('#nivel').val();

        if (codPadre && nivel > 1) {
            $.ajax({
                url: 'index.php?pagina=catalogocuentas',
                method: 'POST',
                data: {
                    cod_padre: codPadre,
                    nivel: nivel,
                    codigohija: true
                },  
                success: function(codigo) {
                    $('#codigoContable').val(codigo);
                    $('#naturaleza').prop('readonly', true);
                    console.log('Naturaleza:', $('#naturalezaHidden').val());
                },
                error: function() {
                    $('#codigoContable').val('Error generando código');
                }
            });
        }
    }
});
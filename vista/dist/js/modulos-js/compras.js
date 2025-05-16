
$('#rif-r').on('blur keydown', function (e) {
    if (e.type === 'blur' || (e.type === 'keydown' && e.keyCode === 13)) {
        e.preventDefault();
        var buscar = $('#rif-r').val();
        // Buscamos la razón social (si busca razon ,pero no telefono)y teléfono del proveedor por cod_prov
        $.post('index.php?pagina=proveedores', { buscar: buscar }, function (response) {
            if (response) {
                var razon_social = response['razon_social'];
                var codigo = response['cod_prov'];
                var telefono = response['telefono'];

                var modal = $('#modalcom');
                modal.find('.modal-body #razon-social').val(razon_social);
                modal.find('.modal-body #cod_prov').val(codigo);
                if (telefono) {
                    modal.find('.modal-body #telefono').val(telefono);
                } else {
                    modal.find('.modal-body #telefono').val('');
                }
                modal.find('.modal-body [id^="nombreProducto"]').first().focus();
            } else {
                limpiarCamposProv();
                Swal.fire({
                    title: 'Error',
                    text: 'Proveedor no encontrado. Por favor, verifica el rif o la cedula.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        }, 'json');
    }
});

function limpiarCamposProv() {
    var modal = $('#modalcom');
    modal.find('.modal-body #razon-social').val('');
    modal.find('.modal-body #cod_prov').val('');
    modal.find('.modal-body #telefono').val('');
}


var productoIndex = 1;

// Función para crear una nueva fila en formato de matriz
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" name="productos[${index}][cod_presentacion]" id="codigoProducto${index}" placeholder="Código de la presentacion" required readonly>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" name="productos[${index}][nombre_producto]" id="nombreProducto${index}" placeholder="Nombre del producto" required>
                    
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modalRegistrarProducto">+</button>
                    </div>
                </div>
                <div id="lista${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
            </td>
            <td>
                <div class="input-group">
                    <input type="date" id="fecha-v${index}" class="form-control" name="productos[${index}][fecha_v]" placeholder="Fecha" onchange="validarfecha(${index})" min="<?= date('Y-m-d'); ?>">
                    <div class="invalid-feedback" style="display: none; position: absolute; top: 100%; margin-top: 2px; width: calc(100% - 2px); font-size: 0.875em; text-align: left;"></div>
                </div>
            </td>
            <td>
                <input type="hidden" id="cod-dp${index}" class="form-control" name="productos[${index}][cod-dp]">
                <input type="text" id="lotes${index}" class="form-control" name="productos[${index}][lote]" placeholder="Lote">
                <div id="lista-lotes${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
            </td>
            <td>
                <div class="input-group">
                    <input type="number" class="form-control" name="productos[${index}][cantidad]" value="1" step="0.001" oninput="calcularMontos(${index})" required>
                    <div class="input-group-append">
                        <span id="unidadm${index}" class="input-group-text" value=" "></span>
                    </div>
                </div>
            </td>
            <td class="col-divisa" style="display: none;">
                <input type="number" step="0.001" class="form-control precio-divisa" placeholder="Precio en divisa" id="precio_divisa${index}" oninput="calcularMontos(${index})">
            </td>
            <td>
                <input type="number" class="form-control" step="0.001" name="productos[${index}][precio]" placeholder="Precio" oninput="calcularMontos(${index})" required>
            </td>
            <td>
                <select class="form-control" id="tipoProducto${index}" name="productos[${index}][iva]" onchange="calcularMontos(${index})" required>
                <option value="1"> E </option>
                <option value="2"> G </option>
                </select>
            </td>
            <td><input type="number" class="form-control" id="total${index}" name="productos[${index}][total]" placeholder="Total" readonly required></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(${index})">&times;</button></td>
        </tr>
    `;
}

// Función para agregar una nueva fila
function agregarFila() {
    var abreviatura = $('#selectDivisa').find('option:selected').data('abreviatura');
    var tasa = $('#selectDivisa').find('option:selected').data('tasa');
    var cod = $('#selectDivisa').find('option:selected').data('cod');
    var nuevaFila = crearfila(productoIndex);

    $('#ProductosBody').append(nuevaFila);
    if (cod != 1) {
        $('#fila' + productoIndex + ' .col-divisa').show();
        $('#fila' + productoIndex + ' .precio-divisa').show().attr('data-tasa', tasa);
    } else {
        $('#fila' + productoIndex + ' .col-divisa').hide();
        $('#fila' + productoIndex + ' .precio-divisa').hide();
    }
    productoIndex++;
}

// Función de inicialización de filas
function inicializarFilas() {
    agregarFila(); // Se añade una fila inicial
}

$(document).ready(function () {
    inicializarFilas();
});

function eliminarFila(index) {
    // Eliminar la fila del DOM usando el identificador de fila
    var fila = document.getElementById(`fila${index}`);
    if (fila) {
        fila.remove();
    }
    calcularMontos();
}

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

$(document).ready(function () {
    // Manejar el cambio de la divisa seleccionada
    $('#selectDivisa').on('change', function () {
        var selectedOption = $(this).find('option:selected');
        var tasa = parseFloat(selectedOption.data('tasa'));
        var abreviatura = selectedOption.data('abreviatura');
        var cod = selectedOption.data('cod') || 1;
        console.log(cod);

        if (cod != 1) {
            // Mostrar la columna de precio en divisa
            $('.col-divisa').show();
            $('#labelDivisa').text(abreviatura);
            $('#ProductosBody .precio-divisa').show().attr('data-tasa', tasa);
        } else {
            // Ocultar la columna de precio en divisa
            $('.col-divisa').hide();
            $('#ProductosBody .precio-divisa').hide();
        }
    });

    $(document).on('input', '[id^=precio_divisa]', function () {
        var inputId = $(this).attr('id');
        var index = inputId.replace('precio_divisa', '');  // Extraemos el índice de la fila actual
        var tasa = parseFloat($(this).attr('data-tasa'));  // Obtenemos la tasa de cambio de la divisa
        var precioDivisa = parseFloat($(this).val()) || 0;  // Obtenemos el valor del precio en divisa
        var precioBs = (precioDivisa * tasa).toFixed(2);  // Convertimos a Bs

        // Actualizamos el campo correspondiente del precio en Bs de la fila actual
        $(`[name="productos[${index}][precio]"]`).val(precioBs);

        // Llamamos a calcularMontos para la fila específica
        calcularMontos(index);
    });

    $('#rif-r').on('input', function () {
        var cedula_rif = $(this).val();
        if (cedula_rif.length > 1) {
            if (cedula_rif.length > 12) {
                showError('#rif-r', 'debe contener maximo 12 números');
            } else if (!/^[VJEvje]\d+$/.test(cedula_rif)) {
                showError('#rif-r', 'debe comenzar con "J", "V" , "E" Y luego numeros');
            } else {
                hideError('#rif-r');
            }
        }
    });
});

function validarfecha(index) {
    var fecha = new Date($(`[name="productos[${index}][fecha_v]"]`).val());
    var actual = new Date();
    actual.setHours(0, 0, 0, 0);
    if (fecha <= actual) {
        showError(`#fecha-v${index}`, 'La fecha debe ser futura');
    } else {
        hideError(`#fecha-v${index}`);
    }

}


function calcularMontos(index) {
    var cantidad = parseFloat($(`[name="productos[${index}][cantidad]"]`).val()) || 0;
    var precio = parseFloat($(`[name="productos[${index}][precio]"]`).val()) || 0;
    var total = cantidad * precio;
    $(`[name="productos[${index}][total]"]`).val(total.toFixed(2));
    actualizarResumen();
}

function actualizarResumen() {
    var subtotal = 0;
    var exento = 0;
    var baseImponible = 0;
    var iva = 0;

    for (var i = 1; i < productoIndex; i++) {
        var totalProducto = parseFloat($('#total' + i).val()) || 0;


        console.log('Total del producto ' + i + ':', totalProducto);


        if (isNaN(totalProducto)) {
            totalProducto = 0;
        }

        subtotal += totalProducto;

        var tipoProducto = parseFloat($('#tipoProducto' + i).val());

        if (tipoProducto === 1) {
            exento += totalProducto;
        } else if (tipoProducto === 2) {
            baseImponible += totalProducto;
        }
    }
    iva = baseImponible * 0.16;
    var totalGeneral = subtotal + iva;

    // Verificar los valores calculados para el resumen (para depuración)
    console.log('Subtotal:', subtotal);
    console.log('Exento:', exento);
    console.log('Base Imponible:', baseImponible);
    console.log('IVA:', iva);
    console.log('Total General:', totalGeneral);


    // Actualizar el DOM con los valores calculados
    $('#subtotal').text(subtotal.toFixed(2));
    $('#exento').text(exento.toFixed(2));
    $('#iva').text(iva.toFixed(2));
    $('#total-span').text(totalGeneral.toFixed(2));
    $('#total-general').val(totalGeneral.toFixed(2));
    $('#base-imponible').text(baseImponible.toFixed(2));
    $('#subt').val(subtotal.toFixed(2));
    $('#impuesto_total').val(iva.toFixed(2));

    /*Actualizar los inputs ocultos correspondientes
    $('input[name="subtotal"]').val(subtotal.toFixed(2));
    $('input[name="impuesto_total"]').val(iva.toFixed(2));*/
}

$(document).ready(function () {
    // Actualizar el resumen general al cambiar el total de un producto
    $('#ProductosBody').on('input', 'input[name^="productos"][name$="[total]"]', function () {
        actualizarResumen();
    });
});

$(document).on('input', 'input[name^="productos"][name$="[cantidad]"], input[name^="productos"][name$="[precio]"]', function () {
    // Obtén el índice del nombre del input, asumiendo que sigue el patrón "productos[index][campo]"
    var name = $(this).attr('name');
    var index = name.match(/\[([0-9]+)\]/)[1]; // Extrae el índice del nombre usando una expresión regular
    calcularMontos(index); // Llama a calcularMontos con el índice correcto
});


$(document).ready(function () {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=nombreProducto]', function () {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario
        console.log(query);

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=compras',
                method: 'POST',
                data: { buscar: query },
                dataType: 'json',
                success: function (data) {
                    console.log("Enviando búsqueda con valor:", query); // Depuración
                    console.log(data);
                    var listaProductos = $('#lista' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        console.log("entra en el condicional");
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function (key, producto) {
                            console.log(producto);
                            console.log(listaProductos);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="' + producto.producto_nombre + '" ' +
                                'data-tipo="' + producto.excento + '" ' +
                                'data-codigo="' + producto.cod_presentacion + '" ' +
                                'data-unidad="' + producto.tipo_medida + '" ' +
                                'data-marca="' + producto.marca + '">' +
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion + ' </a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.fadeOut();
                    }
                }
            });
        } else {
            $('#lista' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function () {
        var selectedProduct = $(this).data('nombre');
        var codigo = $(this).data('codigo');
        var tipo = $(this).data('tipo');
        var unidad = $(this).data('unidad');
        var cant = 1;

        // Encuentra el índice de la entrada de producto en la que se seleccionó
        var inputId = $(this).closest('.list-group').attr('id'); // Obtiene 'listaX' donde X es el índice
        var index = inputId.replace('lista', ''); // Extrae el número de índice

        // Coloca el producto seleccionado en el campo correspondiente usando el índice
        $('#nombreProducto' + index).val(selectedProduct);
        $('#codigoProducto' + index).val(codigo);
        $('#tipoProducto' + index).val(tipo);
        $('#unidadm' + index).text(unidad);
        $(this).closest('.list-group').fadeOut();
    });
});


$(document).ready(function () {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=lotes]', function () {
        var inputId = $(this).attr('id');
        var index = inputId.replace('lotes', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario
        var cod_detalle = $('#codigoProducto' + index).val();
        $('#cod-dp' + index).val('');
        console.log(query);
        console.log(cod_detalle);
        if (query.length > 2) {
            console.log("es mayor de 2");
            $.ajax({
                url: 'index.php?pagina=compras',
                method: 'POST',
                data: { b_lotes: query, cod: cod_detalle },
                dataType: 'json',
                success: function (data) {
                    console.log("Enviando búsqueda con valor:", query); // Depuración
                    console.log(data);
                    var listaProductos = $('#lista-lotes' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        console.log("entra en el condicional");
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function (key, producto) {
                            console.log(producto);
                            console.log(listaProductos);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="' + producto.lote + '" ' +
                                'data-fecha="' + producto.fecha_vencimiento + '" ' +
                                'data-codigo="' + producto.cod_detallep + '">' +
                                producto.lote + ' </a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.fadeOut();
                    }
                }
            });
        } else {
            $('#lista-lotes' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
        // Cuando el usuario selecciona un producto
        $(document).on('click', '.producto-item', function () {
            var selectedProduct = $(this).data('nombre');
            var codigo = $(this).data('codigo');
            var fecha = $(this).data('fecha');


            var inputId = $(this).closest('.list-group').prev('input').attr('id');
            var index = inputId.replace('lotes', ''); // Extrae el índice del campo
            $('#' + inputId).val(selectedProduct);

            $('#cod-dp' + index).val(codigo);
            $('#fecha-v' + index).val(fecha);
            $(this).closest('.list-group').fadeOut();
        });
    });
});

$(document).ready(function () {
    // Obtener la fecha y hora actual
    var now = new Date();
    var fecha = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0');

    // Asignar el valor al input de fecha-hora
    $('#fecha-hora').val(fecha);
});

//Modal detalle
$(document).ready(function () {
    // Evento al abrir el modal
    $('#detallemodal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que abrió el modal
        var codigo = button.data('codigo'); // Extraer el cod_presentacion
        var fecha = button.data('fecha');
        var nombre = button.data('nombre');
        var total = button.data('total');

        var modal = $(this);
        modal.find('.modal-body #nro-compra').val(codigo);
        modal.find('.modal-body #r_social').val(nombre);
        modal.find('.modal-body #fecha_compra').val(fecha);
        modal.find('.modal-body #total_compra').text(total);

        $('#detalleBody').empty();

        // Hacer una llamada AJAX para obtener los detalles del producto
        $.ajax({
            url: 'index.php?pagina=compras',
            method: 'POST',
            data: { detallep: codigo },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                // Verificar si hay datos en la respuesta
                if (data.length === 0) {
                    // Si no hay detalles mostrar un mensaje 
                    $('#detalleBody').append(
                        '<tr>' +
                        '<td colspan="7" class="text-center">No hay detalles disponibles para este producto</td>' +
                        '</tr>'
                    );
                } else {
                    // Recorrer los datos devueltos y llenar la tabla
                    $.each(data, function (index, detalle) {

                        $('#detalleBody').append(
                            '<tr>' +
                            '<td>' + detalle.cod_detallep + '</td>' +
                            '<td>' + detalle.presentacion + '</td>' +
                            '<td>' + detalle.fecha_vencimiento + '</td>' +
                            '<td>' + detalle.lote + '</td>' +
                            '<td class="stock">' + detalle.cantidad + '</td>' +
                            '<td>' + detalle.monto + '</td>' +

                            '<td>' + (detalle.monto * detalle.cantidad).toFixed(2) + '</td>' +
                            '</tr>'
                        );
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los detalles:', error);
            }
        });
    });
});

//FUNCIONES DE PAGOS PARA COMPRAS
$('#pagoGModal').on('show.bs.modal', function (event) {
    var modal = $(this);
    console.log("abri el modal");
    /* LIMPIO EL MODAL Y EL CALCULO DE ESTE */
    modal.find('.modal-body #total-pago1').text('0.00 Bs');
    modal.find('.modal-body #total-pago2').text('0.00 Bs');
    modal.find('.modal-body #total-pago').text('0.00 Bs');
    modal.find('.modal-body #cod_gasto1').val('');
    modal.find('.modal-body #cod_gasto').val('');
    modal.find('.modal-body #monto_pagar').val('');
    modal.find('.modal-body #fecha_del_pago').val('');
    modal.find('.modal-body #nombre_gasto').val('');
    modal.find('.modal-body .monto-section').hide();
    modal.find('.modal-body #monto_pagado').val('0.00');
    modal.find('.modal-body #diferencia').val('0.00');
    modal.find('.modal-body #vuelto').val('0.00');

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_compra');
    console.log("codigo: " + codigo);
    var codp = button.data('codpago');
    var compra = 'compra';
    console.log("compra: " + compra);
    modal.find('.modal-body #compra').val(compra);
    var fecha = new Date();
    var fechaFormateada = fecha.getFullYear() + '-' +
        String(fecha.getMonth() + 1).padStart(2, '0') + '-' +
        String(fecha.getDate()).padStart(2, '0');
    var nombre = button.data('nombre');
    var montop = parseFloat(button.data('montop')) || 0;
    console.log("muestra el montop? " + montop);
    var total = parseFloat(button.data('total')) || 0;
    var modal = $(this);

    modal.find('.modal-body #cod_compra1').val(codigo);
    modal.find('.modal-body #cod_compra').val(codigo);

    modal.find('.modal-body #total-gasto').text(total + 'Bs');
    modal.find('.modal-body #total-gasto-oculto').val(total);

    modal.find('.modal-body #fecha_del_pago').val(fechaFormateada);
    modal.find('.modal-body #nombre_gasto').val(nombre);

    var totalGastoOculto = $('#total-gasto-oculto').val();


    $.ajax({
        url: 'index.php?pagina=compras',
        method: 'POST',
        data: { cod_compra: codigo },
        dataType: 'json',
        success: function (response) {
            var modal = $('#pagoGModal');

            if (response.success) {
                var montoTotal = parseFloat(response.monto_total) || 0;

                console.log("Monto total como número:", montoTotal);
                console.log(montop);


                modal.find('.modal-body #total-pago1').text(montop.toFixed(2) + ' Bs');
                modal.find('.modal-body #total-pagop').val(montop.toFixed(2) + ' Bs');
                modal.find('.modal-body #total-gasto').text(total.toFixed(2) + ' Bs');

                var montopagar = Math.abs(total - montop);
                modal.find('.modal-body #total-pago').text(montopagar.toFixed(2) + ' Bs');
                modal.find('.modal-body #monto_pagar').val(montopagar.toFixed(2));

                modal.find('.modal-body .monto-section').show();
            } else {
                modal.find('.modal-body #monto_pagar').val(total);
                modal.find('.modal-body .monto-section').hide();

            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);

            var modal = $('#pagoGModal');
            modal.find('.modal-body .monto-section').hide();
        }
    });

});


$('#vueltoModal').on('show.bs.modal', function (event) {
    console.log("Modal de pago parcial abierto");

    var button = $(event.relatedTarget);
    var codigo = button.data('cod_compra');
    var vuelto = button.data('vuelto');

    console.log("Código del gasto:", codigo);
    console.log("Total del vuelto:", vuelto);

    var modal = $(this);
    modal.find('.modal-body #nro_compra').val(codigo);
    modal.find('.modal-body #montoV').text(vuelto);
    modal.find('.modal-body #monto_vuelto').val(vuelto);


});


function calcularTotalpago() {
    let totalBs = 0;

    document.querySelectorAll('.monto-bs:not(.monto-con)').forEach(function (input) {
        let montoBs = parseFloat(input.value) || 0;
        totalBs += montoBs;
    });

    document.querySelectorAll('.monto-divisa').forEach(function (inputDivisa) {
        let index = inputDivisa.id.split('-').pop();

        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        let tasaConversion = parseFloat(document.getElementById('tasa-conversion-' + index).value) || 1;
        console.log('Index:', index);
        console.log('Monto Divisa:', montoDivisa);
        console.log('Tasa de Conversión:', tasaConversion);


        let montoConvertidoBs = montoDivisa * tasaConversion;


        document.getElementById('monto-bs-con-' + index).value = montoConvertidoBs.toFixed(2);


        totalBs += montoConvertidoBs;
    });


    document.getElementById('monto_pagado').value = totalBs.toFixed(2);


    let montoPagar = parseFloat(document.getElementById('monto_pagar').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia').value = diferencia.toFixed(2);

    var vuelto = 0;
    if (diferencia < 0) {
        vuelto = Math.abs(diferencia);
        document.getElementById('vuelto').value = vuelto.toFixed(2);
    } else {
        document.getElementById('vuelto').value = '0.00';
    }

    console.log('Vuelto:', vuelto);

    const registrarVueltoBtn = document.getElementById('registrarVueltoBtn');
    if (vuelto > 0) {
        registrarVueltoBtn.style.display = 'inline-block';
        console.log('Botón Registrar Vuelto mostrado');
        registrarVueltoBtn.setAttribute('data-cod_compra', document.getElementById('cod_compra').value);
        registrarVueltoBtn.setAttribute('data-vuelto', vuelto.toFixed(2));
    } else {
        registrarVueltoBtn.style.display = 'none';
        console.log('Botón Registrar Vuelto oculto');
    }

}

function calcularTotalvuelto() {
    let totalBs = 0;

    document.querySelectorAll('.monto-bs1:not(.monto-con1)').forEach(function (input) {
        let montoBs = parseFloat(input.value) || 0;
        console.log('Monto en Bs:', montoBs);
        totalBs += montoBs;
    });

    document.querySelectorAll('.monto-divisa1').forEach(function (inputDivisa) {
        let index = inputDivisa.id.split('-').pop();

        let montoDivisa = parseFloat(inputDivisa.value) || 0;

        let tasaConversion = parseFloat(document.getElementById('tasa-conversion1-' + index).value) || 1;
        console.log('Index:', index);
        console.log('Monto Divisa:', montoDivisa);
        console.log('Tasa de Conversión:', tasaConversion);


        let montoConvertidoBs = montoDivisa * tasaConversion;


        document.getElementById('monto-bs-con1-' + index).value = montoConvertidoBs.toFixed(2);


        totalBs += montoConvertidoBs;
    });

    console.log('Total en Bs:', totalBs);
    document.getElementById('monto_pagado1').value = totalBs.toFixed(2);


    let montoPagar = parseFloat(document.getElementById('monto_vuelto').value) || 0;
    let diferencia = montoPagar - totalBs;
    document.getElementById('diferencia1').value = diferencia.toFixed(2);

}

$(document).ready(function () {
    $('#vueltoModalBtn').on('click', function (e) {
        e.preventDefault();

        let vuelto = $('#monto_vuelto').val();
        let montoPagado = parseFloat($('#monto_pagado1').val()) || 0;
        console.log("Vuelto:", vuelto);
        console.log("Monto Pagado:", montoPagado);

        if (parseFloat(vuelto) > montoPagado) {
            Swal.fire({
                title: 'Error',
                text: 'El vuelto no puede ser mayor que el monto pagado.',
                icon: 'error',
            }).then(() => {
                location.reload();
            });
            return;
        } else if (parseFloat(vuelto) < montoPagado) {
            Swal.fire({
                title: 'Error',
                text: 'El vuelto no puede ser menor que el monto pagado.',
                icon: 'error',
            }).then(() => {
                location.reload();
            });
            return;
        }


        if (!vuelto) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, completa todos los campos.',
                icon: 'error',
            });
            return;
        }

        let arrayConDetalles = [];

        $('.monto-bs1').each(function () {
            let monto = parseFloat($(this).val()) || 0;
            let cod_tipo_pago = $(this).closest('.form-group').find('input[type="hidden"][name*="cod_tipo_pago"]').val();
            console.log('Bs:', monto, 'Tipo:', cod_tipo_pago);
            if (monto > 0 && cod_tipo_pago) {
                arrayConDetalles.push({
                    cod_tipo_pago: cod_tipo_pago,
                    monto: monto
                });
            }
        });

        $('.monto-divisa1').each(function () {
            let montoDivisa = parseFloat($(this).val()) || 0;
            let cod_tipo_pago = $(this).closest('.form-group').find('input[type="hidden"][name*="cod_tipo_pago"]').val();
            let index = $(this).attr('id').split('-').pop();
            let montoBs = parseFloat($('#monto-bs-con1-' + index).val()) || 0;
            console.log('Divisa:', montoDivisa, 'Bs:', montoBs, 'Tipo:', cod_tipo_pago);
            if (montoBs > 0 && cod_tipo_pago) {
                arrayConDetalles.push({
                    cod_tipo_pago: cod_tipo_pago,
                    monto: montoBs
                });
            }
        });
        console.log(arrayConDetalles);

        $.ajax({
            url: 'index.php?pagina=compras',
            method: 'POST',
            data: {
                vuelto: vuelto,
                pago: arrayConDetalles
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'El vuelto se registró correctamente.',
                        icon: 'success',
                    }).then(() => {

                        $('#vueltoModal').modal('hide');
                        $('#vueltoForm')[0].reset();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Hubo un error al registrar el vuelto.',
                        icon: 'error',
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar el vuelto. Intenta nuevamente.',
                    icon: 'error',
                });
            },
        });
    });
});

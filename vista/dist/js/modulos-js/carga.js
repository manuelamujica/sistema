//Modal detalle MUESTRA LOS DETALLES DE UNA CARGA
$(document).ready(function() {

    // Evento al abrir el modal
    $('#detallemodal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que abrió el modal
        var cod = button.data('codigo'); // Extraer el cod_presentacion

        // Limpiar la tabla de detalles antes de cargar nuevos datos
        $('#detalleBody').empty();

        // Hacer una llamada AJAX para obtener los detalles del producto
        $.ajax({
            url: 'index.php?pagina=carga',
            method: 'POST',
            data: {
                detalle: cod
            },
            dataType: 'json',
            success: function(data) {
                //console.log(data);
                // Verificar si hay datos en la respuesta
                if (data.length === 0) {
                    // Si no hay detalles mostrar un mensaje 
                    $('#detalleBody').append(
                        '<tr>' +
                        '<td colspan="6" class="text-center">No hay detalles disponibles para esta carga</td>' +
                        '</tr>'
                    );
                } else {
                    // Recorrer los datos devueltos y llenar la tabla
                    $.each(data, function(index, detalle) {
                        //console.log(detalle);

                        var statusText = detalle.status == '1' //Si el status es 1 mostrar activo sino inactivo
                            ?
                            '<span class="badge badge-success">Activo</span>' :
                            '<span class="badge badge-danger">Inactivo</span>' //NECESITO MEJORAR LA LOGIA Y EL FILTRADO DEL CONSULTAR
                        
                        $('#detalleBody').append(
                            '<tr>' +
                            '<td>' + detalle.cod_det_carga + '</td>' +
                            '<td>' + detalle.nombre + ' x ' + detalle.presentacion + '</td>' +
                            '<td>' + detalle.cantidad + '</td>' +
                            '</tr>'
                        );

                    });
                }

            },
            error: function(xhr, status, error) {
                console.error('Error al cargar los detalles:', error);
            }
        });


    });
});


$(document).ready(function () {

    var productoIndex = 1; // Contador para las filas de productos

    // Función para crear una nueva fila
    function crearFila(index) {
        return `
        <tr id="fila${index}">
        <td>
            <input type="text" class="form-control" id="codigoProducto${index}" name="productos[${index}][codigo1]" placeholder="Código del producto" readonly>
            
        </td>
             <td>
            <div class="input-group">
                <input type="text" class="form-control" id="nombreProducto${index}" name="productos[${index}][nombre]" placeholder="Nombre del producto" maxlength="30">
                <div id="lista-productos${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                </div>
            </div>
        </td>
            <td>
                <input type="number" class="form-control" name="productos[${index}][cantidad]" id="cantidad${index}" required min="1" placeholder="Cantidad">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(${index})">&times;</button>
            </td>
        </tr>
    `;
    }

    // Evento al abrir el modal de registrar carga
    $('#modalregistrarCarga').on('show.bs.modal', function (event) {
        // Limpiar la tabla de productos
        $('#productosCarga tbody').empty();  //FUNCIONA

        // Agregar una fila vacía al abrir el modal
        var nuevaFila = crearFila(productoIndex);
        $('#productosCarga tbody').append(nuevaFila);
    });
     // Función para agregar una nueva fila a la tabla
    $('#add-product').on('click', function() {
        var nuevaFila = crearFila(productoIndex);
        $('#productosCarga tbody').append(nuevaFila);
        productoIndex++;
    });


    // Función para eliminar una fila
    window.eliminarFila = function (index) {
        $('#fila' + index).remove();
    };

    // Manejo del envío del formulario para registrar la carga
    $('#formregistrarCarga').on('submit', function (event) {


        // Validar la fecha y hora listo
        var fechaInput = $('#fecha').val();
        var fechaSeleccionada = new Date(fechaInput);
        var fechaActual = new Date();

        // Comparar la fecha y hora seleccionadas con la fecha y hora actuales
        if (fechaSeleccionada > fechaActual) {
            Swal.fire({
                title: 'Fecha y hora no válidas',
                text: 'La fecha y hora seleccionadas no pueden ser futuras.',
                icon: 'warning'
            });
            event.preventDefault();
            return; // Salir de la función si la fecha es futura
        }

        var productosTabla = [];
        $('#productosCarga tbody tr').each(function () {
            var codigo = $(this).find('input[name^="productos["][name$="[codigo]"]').val(); // Obtener el código
            var cantidad = $(this).find('input[name^="productos["][name$="[cantidad]"]').val(); // Obtener la cantidad
            if (codigo && cantidad) {
                productosTabla.push({
                    codigo: codigo,
                    cantidad: cantidad
                });
            }
        });
        var detallesVerificados = 0;
        var totalProductos = productosTabla.length;
        $.each(productosTabla, function (index, producto) {
            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=carga',
                data: {
                    verificarDetalle: true,
                    id: producto.codigo // Usar el código del producto para verificar
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        detallesVerificados++;
                        console.log('no');
                    } else {
                        Swal.fire({
                            title: 'Producto Sin información',
                            text: 'El producto con código "' + producto.codigo + '" no tiene información registrada.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Registrar Detalle',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#cod_presentacion').val(producto.codigo); // Llenar el campo del modal
                                $('#modalregistrardetallep').modal('show'); // Mostrar el modal
                            }
                        });
                    }

                    // Si se han verificado todos los productos
                    if (detallesVerificados === totalProductos) {
                        console.log("Pasa por la funcion de registrar");
                        //return true // Enviar el formulario si todos los detalles están verificados
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al verificar los detalles del producto.',
                        icon: 'error'
                    });
                    event.preventDefault();
                    return;
                }
            });
        });


    });

    // Manejo del envío del formulario para registrar detalle de producto
    $('#formRegistrarDetalle').on('submit', function (event) {
        event.preventDefault(); // Prevenir el envío del formulario por defecto
        enviarFormularioDetalle(); // Llamar a la función para registrar el detalle
    });

    function enviarFormularioDetalle() {
        var formData = new FormData($('#formRegistrarDetalle')[0]); // Crear un objeto FormData

        // Agregar un campo adicional
        formData.append('registrarD', '1'); // Usar append para agregar parámetros

        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=carga',
            data: formData,
            processData: false, // Importante: No procesar los datos
            contentType: false, // Importante: No establecer el tipo de contenido
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: response.data.title,
                        text: response.data.message,
                        icon: response.data.icon
                    }).then(() => {
                        $('#modalregistrardetallep').modal('hide'); // Cerrar el modal
                        $('#formRegistrarDetalle')[0].reset(); // Reiniciar el formulario
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', error);
                console.log('Respuesta del servidor:', xhr.responseText); // Muestra la respuesta del servidor
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al registrar más detalles.',
                    icon: 'error'
                });
            }
        });
    }

    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=nombreProducto]', function () {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=carga',
                method: 'POST',
                data: {
                    buscar: query
                },
                dataType: 'json',
                success: function (data) {
                    var listaProductos = $('#lista-productos' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados Solo tome la logica y su funcion de venta para mostrar productos pero al quitar el costo no me muestra los productos
                        $.each(data, function (key, producto) {
                            var marca = producto.marca || 'Sin marca';
                            var presentacion =  producto.presentacion || 'sin presentación';
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="' + producto.producto_nombre + '" ' +
                                'data-tipo="' + producto.excento + '" ' +
                                'data-codigo="' + producto.cod_presentacion + '" ' +
                                'data-marca="' + marca + '" ' + '">' +
                                producto.producto_nombre + ' - ' + marca + ' - ' + presentacion + '</a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                }
            });
        } else {
            $('#lista-productos' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function () {
        var selectedProduct = $(this).data('nombre');
        var codigo = $(this).data('codigo');
        var tipo = $(this).data('tipo');
        var cant = 1;

        var inputId = $(this).closest('.list-group').prev('input').attr('id');
        var index = inputId.replace('nombreProducto', ''); // Extrae el índice del campo
        $('#' + inputId).val(selectedProduct);

        $('#codigoProducto' + index).val(codigo);
        $('#tipoProducto' + index).val(tipo);
        $('#cantidadProducto' + index).val(cant).trigger('change');
        $(this).closest('.list-group').fadeOut();
    });
});

$(document).ready(function () {
    // Manejo del evento blur para los campos de código de producto NOTA: HOY 6/11/2024 YA FUNCIONA EL CODIGO CON LA VERIFICACION DE DETALLE DE PRODUCTO Y SU REGISTRO... DEJO TODO COMO ESTA YA ME DA MIEDO MOVER LAS COSAS JAJSJASJA

    $(document).on('blur', '.producto-item', function (e) {
        var codigo = $(this).data('codigo');
        if (codigo) { // Verifica que el código no esté vacío
            $.post('index.php?pagina=carga', {
                id: codigo,
                verificarDetalle: true // Asegúrate de enviar este parámetro
            }, function (response) {
                if (response.status === 'error') {
                    Swal.fire({
                        title: 'Error',
                        text: 'No tiene detalle.',
                        icon: 'warning'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#cod_presentacion').val(codigo);
                            $('#modalregistrardetallep').modal('show'); // Mostrar el modal
                        }
                    });
                }
            }, 'json');
        }
    });

    //Mostrar detalle
    $('#myModalr').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var nombre = button.data('nombre');
        var cantidad = button.data('cantidad');


        // Modal
        var modal = $(this);
        modal.find('.modal-body #cod').val(nombre);
        modal.find('.modal-body #nombre').val(cantidad);


    });
    // Validaciones JS
    // Validación por cada campo cuando se pierde el foco
    $('#fecha').on('blur', function () {
        var fecha = $(this).val();
        if (fecha.trim() === '') {
            showError('#fecha', 'el campo fecha no puede estar vacío');
        } else {
            hideError('#fecha');
        }
    });

    $('#cantidad').on('blur', function () {
        var cantidad = $(this).val();
        if (cantidad.trim() === '') {
            showError('#cantidad', 'el campo cantidad no puede estar vacío');
        } else if (!/^[0-9\.,]+$/.test(cantidad)) { // Cambié 'Z0-9' a '0-9'
            showError('#cantidad', 'solo se aceptan números');
        } else {
            hideError('#cantidad');
        }
    });

    $('#descripcion').on('blur', function () {
        var descripcion = $(this).val();
        if (descripcion.trim() === '') {
            showError('#descripcion', 'el campo descripcion no puede estar vacío');
        } else if (!/^[a-zA-Z0-9\s\.,]+$/.test(descripcion)) {
            showError('#descripcion', 'solo letras, números y signos');
        } else {
            hideError('#descripcion');
        }
    });

    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message).css({
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
/*

//NO TOCAR
// Agregar más campos
document.getElementById('add-product').addEventListener('click', function () {
    // Clonamos la entrada de producto
    const productEntry = document.querySelector('.product-entry').cloneNode(true);

    // Limpiamos los valores de los campos clonados
    const inputs = productEntry.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.value = '';
    });

    // Agregamos el nuevo conjunto de campos al contenedor
    document.getElementById('product-container').appendChild(productEntry);
});*/



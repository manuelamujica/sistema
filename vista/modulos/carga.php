<!-- EN REVISIOON EN EL CONTROLADOR NO QUIERE REGISTRAR LA CARGA :(  
 COMENTARIO 2/11/2024-->
 <?php require_once 'controlador/carga.php' ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- MODULO TRANSACIONAL DE CARGA DE PRODUCTOS EN AJUSTE DE INVENTARIO  -->
                    <h1>Carga de productos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Carga de productos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Botón para ventana modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarCarga">Registrar Carga</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- MOSTRAR EL REGISTRO DE CARGA DE PRODUCTOS -->
                                <table id="carga" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Detalles</th>

                                            <!--<th>Cantidad total</th>-->
                                            <!-- ##### CUAL DEJO? CANTIDAD CARGADA O CANTIDAD TOTAL(STOCK) ##### -->
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($carga as $dato) {
                                        ?>
                                            <?php if ($dato['status'] != 2): ?>

                                                <td><?php echo $dato['cod_carga'] ?></td>
                                                <td><?php echo $dato['fecha'] ?></td>
                                                <td><?php echo $dato['descripcion'] ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" style="position: center;" data-toggle="modal" data-target="#detallemodal" title="Ver detalle"
                                                        data-codigo="<?php echo $dato["cod_carga"]; ?>">
                                                        <i class="fas fa-plus"></i>
                                                    </button>

                                                </td>

                                                <!--<td><?php //echo $dato['stock'] 
                                                        ?></td>-->
                                                <td>
                                                    <?php if ($dato['status'] == 1): ?>
                                                        <span class="badge bg-success">Cargado</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">No disponible</span>
                                                    <?php endif; ?>
                                                </td>


                            </div>

                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                    </tbody>
                    </table>
                        </div>
                    </div>
                </div>

                <!-- =======================
MODAL REGISTRAR CARGA CON EXITO
============================= -->

                <div class="modal fade" id="modalregistrarCarga" tabindex="-1" aria-labelledby="modalregistrarCargaLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                                <h5 class="modal-title" id="exampleModalLabel">Registrar Carga</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form id="formregistrarCarga" method="post">
                                    <!--   FECHA      -->
                                    <div class="form-group">
                                        <label for="fecha">Fecha</label>
                                        <input type="datetime-local" name="fecha" id="fecha" class="form-control">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                    <!--   DESCRIPCIÓN  -->
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <input type="text" class="form-control" id="descripcion" name="descripcion" maxlength="100">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>

                                    <div class="table-responsive mt-4">
                                        <table id="productosCarga" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--  filas dinámicas  -->
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-secondary" id="add-product">Agregar otro producto</button>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php
        if (isset($registrar)): ?>
            <script>
                Swal.fire({
                    title: '<?php echo $registrar["title"]; ?>',
                    text: '<?php echo $registrar["message"]; ?>',
                    icon: '<?php echo $registrar["icon"]; ?>',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'carga';
                    }
                });
            </script>
        <?php endif; ?>



        <!-- =============================
    MODAL MOSTRAR DETALLES 
================================== -->
        <div class="modal fade" id="detallemodal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Detalle de productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="productos" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Presentacion del Producto</th>
                                                <th>Lote</th>
                                                <th>Fecha de vencimiento</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detalleBody">
                                            <!-- Los detalles se cargarán aquí -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- registrar DETALLEP -->
        <div class="modal fade" id="modalregistrardetallep">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                        <h4 class="modal-title">Añadir detalles</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="post" id="formRegistrarDetalle">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="cod_presentacion">Código del Producto:</label>
                                <input type="text" class="form-control" name="cod_presentacion" id="cod_presentacion" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                            </div>
                            <div class="form-group">
                                <label for="lote">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" required placeholder="Ingrese el lote del producto" maxlength="20">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="registrarD" value="">Guardar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
</div>

<script>
    //Modal detalle
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
                            '<td colspan="6" class="text-center">No hay detalles disponibles para este producto</td>' +
                            '</tr>'
                        );
                    } else {
                        // Recorrer los datos devueltos y llenar la tabla
                        $.each(data, function(index, detalle) {

                            var statusText = detalle.status == '1' //Si el status es 1 mostrar activo sino inactivo
                                ?
                                '<span class="badge badge-success">Activo</span>' :
                                '<span class="badge badge-danger">Inactivo</span>' //NECESITO MEJORAR LA LOGIA Y EL FILTRADO DEL CONSULTAR

                            $('#detalleBody').append(
                                '<tr>' +
                                '<td>' + detalle.cod_det_carga + '</td>' +
                                '<td>' + detalle.nombre + ' en ' + detalle.presentacion + '</td>' +
                                '<td>' + detalle.lote + '</td>' +
                                '<td>' + detalle.fecha_vencimiento + '</td>' +
                                '<td class="stock">' + detalle.stock + '</td>' +
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
</script>

<script>
    $(document).ready(function() {


        var productoIndex = 0; // Contador para las filas de productos

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

        // Función para agregar una nueva fila a la tabla
        $('#add-product').on('click', function() {
            var nuevaFila = crearFila(productoIndex);
            $('#productosCarga tbody').append(nuevaFila);
            productoIndex++;
        });



        // Función para eliminar una fila
        window.eliminarFila = function(index) {
            $('#fila' + index).remove();
        };

        // Manejo del envío del formulario para registrar la carga
        $('#formregistrarCarga').on('submit', function(event) {


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
            $('#productosCarga tbody tr').each(function() {
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
            $.each(productosTabla, function(index, producto) {
                $.ajax({
                    type: 'POST',
                    url: 'index.php?pagina=carga',
                    data: {
                        verificarDetalle: true,
                        id: producto.codigo // Usar el código del producto para verificar
                    },
                    dataType: 'json',
                    success: function(response) {
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
                    error: function(xhr, status, error) {
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
            // Prevenir el envío del formulario por defecto



            // Recoger los datos de la tabla




        });

        // Función para enviar el formulario de carga
        /*function enviarFormularioCarga() {
            var formData = $('#formregistrarCarga').serializeArray();
            formData.push({ name: 'guardar', value: '1' });

            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=carga',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log('pasa por registro de carga');
                        Swal.fire({
                            title: response.data.title,
                            text: response.data.message,
                            icon: response.data.icon,
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'carga'; // Redirigir a la página de carga
                            }
                        });
                    } else {
                        Swal.fire({
                            title: response.data.title,
                            text: response.data.message,
                            icon: response.data.icon
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Ocurrió un error al registrar la carga.');
                }
            });
        }*/

        // Manejo del envío del formulario para registrar detalle de producto
        $('#formRegistrarDetalle').on('submit', function(event) {
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
                success: function(response) {
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
                error: function(xhr, status, error) {
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
        $(document).on('input', '[id^=nombreProducto]', function() {
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
                    success: function(data) {
                        var listaProductos = $('#lista-productos' + index);
                        listaProductos.empty(); // Limpiar resultados anteriores

                        if (data.length > 0) {
                            // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados Solo tome la logica y su funcion de venta para mostrar productos pero al quitar el costo no me muestra los productos
                            $.each(data, function(key, producto) {
                                var costo = parseFloat(producto.costo);
                                var precioVenta = costo + (costo * producto.porcen_venta / 100);
                                listaProductos.append(
                                    '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                    'data-nombre="' + producto.producto_nombre + '" ' +
                                    'data-tipo="' + producto.excento + '" ' +
                                    'data-codigo="' + producto.cod_presentacion + '" ' +
                                    'data-marca="' + producto.marca + '" ' +
                                    'data-precio="' + precioVenta + '">' +
                                    producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion + '</a>'
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
        $(document).on('click', '.producto-item', function() {
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
</script>

<script>
    $(document).ready(function() {
        // Manejo del evento blur para los campos de código de producto NOTA: HOY 6/11/2024 YA FUNCIONA EL CODIGO CON LA VERIFICACION DE DETALLE DE PRODUCTO Y SU REGISTRO... DEJO TODO COMO ESTA YA ME DA MIEDO MOVER LAS COSAS JAJSJASJA

        $(document).on('blur', '.producto-item', function(e) {
            var codigo = $(this).data('codigo');
            if (codigo) { // Verifica que el código no esté vacío
                $.post('index.php?pagina=carga', {
                    id: codigo,
                    verificarDetalle: true // Asegúrate de enviar este parámetro
                }, function(response) {
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
        $('#myModalr').on('show.bs.modal', function(event) {
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
        $('#fecha').on('blur', function() {
            var fecha = $(this).val();
            if (fecha.trim() === '') {
                showError('#fecha', 'el campo fecha no puede estar vacío');
            } else {
                hideError('#fecha');
            }
        });

        $('#cantidad').on('blur', function() {
            var cantidad = $(this).val();
            if (cantidad.trim() === '') {
                showError('#cantidad', 'el campo cantidad no puede estar vacío');
            } else if (!/^[0-9\.,]+$/.test(cantidad)) { // Cambié 'Z0-9' a '0-9'
                showError('#cantidad', 'solo se aceptan números');
            } else {
                hideError('#cantidad');
            }
        });

        $('#descripcion').on('blur', function() {
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
</script>
<!-- FUNCIONA TODO, SOLO QUE AL MOMENTO DE ORGANIZAR EL JS DA UN ERROR DEL CONTROLADOR JEJEJE :(  
 COMENTARIO 10/27/2024-->
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
                                            <th>Producto</th>
                                            <th>Cantidad cargada</th>
                                            <!--<th>Cantidad total</th>-->
                                            <!-- ##### CUAL DEJO? CANTIDAD CARGADA O CANTIDAD TOTAL(STOCK) ##### -->
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($datos as $dato) {
                                        ?>
                                            <?php if ($dato['status'] != 2): ?>

                                                <td><?php echo $dato['cod_carga'] ?></td>
                                                <td><?php echo $dato['fecha'] ?></td>
                                                <td><?php echo $dato['descripcion'] ?></td>
                                                <td><?php echo $dato['nombre']." en ".$dato['presentacion'] ?></td>
                                                <td><?php echo $dato['cantidad'] ?></td>
                                                <!--<td><?php //echo $dato['stock'] ?></td>-->
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

                <div class="modal fade" id="modalregistrarCarga" tabindex="-1" aria-labelledby="modalregistrarCargaLabel" aria-hidden="true">
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
                                        <input type="date" class="form-control" name="fecha" id="fecha">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                    <!--   DESCRIPCIÓN  -->
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <input type="text" class="form-control" id="descripcion" name="descripcion">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>

                                    <!-- Contenedor para productos -->
                                    <div id="product-container">
                                        <div class="product-entry">

                                            <div class="form-group">
                                                <label for="select-producto">Producto:</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="cod_detallep[]" class="cod_detallep form-control mr-2" id="select-producto"> <!--- AGG EL ID -->
                                                        <option selected="selected" value=""></option>
                                                        <?php foreach ($productos as $datos) { ?>
                                                            <option value="<?php echo $datos['cod_presentacion']; ?>" class="product-option" data-nombre="<?php echo $datos['nombre']; ?>"> <?php echo $datos['presentacion']." ".$datos['nombre']." " . $datos['marca'] ?> </option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="invalid-feedback" style="display: none;"></div>
                                                    <button data-toggle="modal" data-target="#modalregistrardetallep" class="btn btn-primary" title="Añadir detalle del producto" data-cod="<?php echo $datos['cod_producto']; ?>" id="regisd">+</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="cantidad">Cantidad:</label>
                                                <input type="text" class="form-control" id="cantidad" name="cantidad[]">
                                                <div class="invalid-feedback" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </div>
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
                        <label for="cod_producto">Código del Producto:</label>
                        <input type="text" class="form-control" name="cod_presentacion" id="cod_presentacion" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="fecha_vencimiento">Fecha de vencimiento</label>
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                    </div>
                    <div class="form-group">
                        <label for="lote">Lote</label>
                        <input type="text" class="form-control" id="lote" name="lote" required placeholder="Ingrese el lote del producto">
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
    $(document).ready(function() {
        // Manejo del envío del formulario para registrar la carga
        $('#formregistrarCarga').on('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario por defecto

            // Obtener todos los productos seleccionados
            var productos = $('select[name="cod_detallep[]"]').map(function() {
                return {
                    id: $(this).val(),
                    nombre: $(this).find('option:selected').data('nombre') // Obtener el nombre del producto
                };
            }).get();

            // Contador para verificar si todos los productos tienen detalles
            var detallesVerificados = 0;
            var totalProductos = productos.length;

            // Verificar cada producto
            $.each(productos, function(index, producto) {
                if (producto.id) {
                    $.ajax({
                        type: 'POST',
                        url: 'index.php?pagina=carga',
                        data: {
                            verificarDetalle: true,
                            id: producto.id
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Verificar el estado de la respuesta
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'success',
                                    text: 'El producto "' + producto.nombre + '" tiene detalles.',
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                                detallesVerificados++;
                            } else {
                                Swal.fire({
                                    title: 'Producto Sin información',
                                    text: 'El producto "' + producto.nombre + '" no tiene información registrada.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Registrar Detalle',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Abrir el modal de detalle de producto
                                        $('#cod_presentacion').val(producto.id); // Llenar el campo del modal
                                        $('#modalregistrardetallep').modal('show'); // Mostrar el modal
                                    }
                                });
                            }


                            // Si se han verificado todos los productos
                            if (detallesVerificados === totalProductos) {
                                enviarFormularioCarga();
                                console.log("Todos los detalles verificados");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en AJAX:', error);
                            Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al verificar los detalles del producto.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                        }
                    });
                } else {
                    // Si no hay producto seleccionado, simplemente incrementamos
                    detallesVerificados++;
                }
            });
        });

        // Función para enviar el formulario de carga
        function enviarFormularioCarga() {
            // Obtener los datos del formulario
            var formData = $('#formregistrarCarga').serializeArray(); // Serializa los datos del formulario
            // Agregar el campo 'guardar' al arreglo de datos
            formData.push({
                name: 'guardar',
                value: '1'
            });
            var queryString = $.param(formData);

            // Realizar la llamada AJAX para registrar la carga
            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=carga',
                data: queryString,
                dataType: 'json',
                success: function(response) {
                    // Manejo de la respuesta del servidor
                    if (response.status === 'success') {
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
                        // Manejar el error
                        Swal.fire({
                            title: response.data.title,
                            text: response.data.message,
                            icon: response.data.icon,
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Ocurrió un error al registrar la carga.');
                }
            });
        }

        // FUNCION PARA REGISTRAR DETALLE DE PRODUCTO
        function enviarFormularioDetalle() {
            // Obtener los datos del formulario
            var formData = $('#formRegistrarDetalle').serializeArray(); // Serializa los datos del formulario
            // Agregar el campo 'guardar' al arreglo de datos
            formData.push({
                name: 'registrarD',
                value: '1'
            });
            var queryString = $.param(formData);

            // Realizar la llamada AJAX para registrar la carga
            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=carga',
                data: queryString,
                dataType: 'json',
                success: function(response) {
                    // Manejo de la respuesta del servidor
                    if (response.status === 'success') {
                        Swal.fire({
                            title: response.data.title,
                            text: response.data.message,
                            icon: response.data.icon,
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modalregistrardetallep').modal('hide');
                                $('#formRegistrarDetalle')[0].reset(); // Reinicia el formulario
                            }
                        });
                    } else {
                        // Manejar el error
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    alert('Ocurrió un error al registrar mas detalles.');
                }
            });
        }


        $('#formRegistrarDetalle').on('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario por defecto
            enviarFormularioDetalle(); // Llamar a la función para registrar el detalle
        });
    });
</script>


<script src="vista/dist/js/modulos-js/carga.js"></script>

<script>
    //VALIDACIONES JS
    $(document).ready(function() {
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
            } else if (!/^[Z0-9\.,]+$/.test(cantidad)) {
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
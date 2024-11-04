<!-- MODULO DE compraA / SOLO VISta   -->
<?php require_once 'controlador/compras.php'; ?>



<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Compras</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Compras</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Formulario y tabla de compras -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Botones  de registar en línea -->
                            <button name="reg" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modalcom">
                                Registrar compra
                            </button>
                        </div>
                        <br>
                        <div class="card-body">
                            <!-- Tabla de compra-->
                            <div class="table-responsive">
                                <table id="compras" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Proveedor</th>
                                            <th>Sub total</th>
                                            <th>Fecha</th>
                                            <th>total</th>
                                            <th>status</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- registro de puro de compra-->
                                        <?php foreach ($compra as $compras) {
                                        ?>
                                            <?php if ($compras['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $compras["cod_compra"] ?></td>
                                                    <td><?php echo $compras['razon_social'] ?></td>
                                                    <td><?php echo $compras['subtotal'] ?></td>
                                                    <td><?php echo $compras["fecha"] ?></td>
                                                    <td><?php echo $compras["total"] ?></td>
                                                    <td>
                                                        <?php if ($compras['compra_status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <!-- anular cambia los estatus de compra-->
                                                        <button name="Eliminar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" data-target="#modaleliminar"
                                                            data-cod="<?php echo $compras['cod_compra']; ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>


            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->



<!-- Registrar modal  compra -->
<div class="modal fade" id="modalcom" tabindex="-1" role="dialog" aria-labelledby="compraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 96%;">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="compraModalLabel">Registrar compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formcompra" method="post">
                    <div class="container-fluid">
                        <!-- Información del Proveedor -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cedula_rif">RIF</label>
                                    <div class="row">
                                        <div class="col-md-11">
                                            <input type="text" class="form-control form-control-sm" id="rif-r" name="rif" placeholder="RIF">
                                            <input type="hidden" id="cod_prov" name="cod_prov">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalProv">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Número de teléfono</label>
                                    <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" placeholder="Número del proveedor" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="razon-social">Razón social</label>
                                    <input type="text" class="form-control form-control-sm" id="razon-social" name="razon_social" placeholder="Razón social" readonly>
                                </div>
                                <div class="form-row align-items-end">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha-hora">Fecha y Hora</label>
                                            <input type="text" class="form-control form-control-sm" id="fecha-hora" name="fecha" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de productos -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Fecha de vencimiento</th>
                                        <th>Lote</th>
                                        <th>Cantidad</th>
                                        <th>Precio de compra</th>
                                        <th>Iva</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="ProductosBody">
                                    <!-- Aquí se agregarán dinámicamente las filas de productos -->
                                </tbody>
                            </table>
                        
                        <!-- Botón para agregar nuevo producto -->
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="agregarFila()">+ Agregar Producto</button>
                        </div>

                        <!-- Resumen de la compra -->
                        <div class="card card-outline card-primary float-right" style="width: 300px;">
                            <div class="card-body">
                                <p>Subtotal: S/ <input type="number" class="form-control" name="subtotal" placeholder="Subtotal" style="width: 120px;" readonly></p>
                                <p>IVA (16%): S/ <input type="number" class="form-control" name="impuesto_total" placeholder="IVA" style="width: 120px;" readonly></p>
                                <p class="text-bold">TOTAL: S/ <span id="total-span" class="text-bold">0.00</span></p>
                                <input type="hidden" id="total-general" name="total_general">
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="formcompra" name="registrar">Guardar</button>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'compras';
            }
        });
    </script>
<?php endif; ?>
<!-- registrar compra-->

<script>
    $('#rif-r').blur(function(e) {
            console.log("Evento blur activado"); // Depuración
            var buscar = $('#rif-r').val();
            // Buscamos la razón social (si busca razon ,pero no telefono)y teléfono del proveedor por cod_prov
            $.post('index.php?pagina=proveedores', {buscar: buscar}, function(response) {
                console.log("Enviando búsqueda con valor:", buscar); // Depuración
                console.log(response);
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
                }
            }, 'json');
        });

        var productoIndex = 1; 

// Función para crear una nueva fila en formato de matriz
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" name="productos[${index}][cod_presentacion]" id="codigoProducto${index}" placeholder="Código del proveedor">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" name="productos[${index}][nombre_producto]" id="nombreProducto${index}" placeholder="Nombre del producto">
                    <div id="lista${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos(${index})">+</button>
                    </div>
                </div>
            </td>
            <td><input type="date" id="fecha-v${index}" class="form-control" name="productos[${index}][fecha_v]" placeholder="Fecha">
            <input type="hidden" id="cod-dp${index}" class="form-control" name="productos[${index}][cod-dp]"></td>
            <td>
                <div class="input-group">
                    <input type="number" id="lotes${index}" class="form-control" name="productos[${index}][lote]" placeholder="Lote">
                    <div id="lista-lotes${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                </div>
            </td>
            <td><input type="number" class="form-control" name="productos[${index}][cantidad]" value="1" min="1" onchange="calcularMontos(${index})"></td>
            <td><input type="number" class="form-control" name="productos[${index}][precio]" placeholder="Precio" onchange="calcularMontos(${index})"></td>
            <td><select class="form-control" id="tipoProducto${index}" name="productos[${index}][iva]" required>
                <option value="1">E</option>
                <option value="2">G</option>
                </select></td>
            <td><input type="number" class="form-control" name="productos[${index}][total]" placeholder="Total" readonly required></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(${index})">&times;</button></td>
        </tr>
    `;
}

// Función para agregar una nueva fila
function agregarFila() {
    var nuevaFila = crearfila(productoIndex);
    $('#ProductosBody').append(nuevaFila);
    productoIndex++; 
}

// Función de inicialización de filas
function inicializarFilas() {
    agregarFila(); // Se añade una fila inicial
}

$(document).ready(function() {
    inicializarFilas(); 
});

function calcularMontos(index) {
    var cantidad = parseFloat($(`[name="productos[${index}][cantidad]"]`).val()) || 0;
    var precio = parseFloat($(`[name="productos[${index}][precio]"]`).val()) || 0;
    var total = cantidad * precio;
    $(`[name="productos[${index}][total]"]`).val(total.toFixed(2));
    actualizarResumen(index); 
}

function actualizarResumen(index) {
    var subtotal = 0;

    // Sumar todos los valores de los inputs "total" de cada producto
    $('#ProductosBody input[name^="productos"][name$="[total]"]').each(function() {
        subtotal += parseFloat($(this).val()) || 0;
    });

    var tipoProducto = $('#tipoProducto' + index).val();
    var iva=0;
    if (tipoProducto === 2) {
            iva = subtotal * 0.16;
        }
    // Calcular el IVA y el total general
    console.log(iva);
    console.log(tipoProducto);
    var totalGeneral = subtotal + iva;

    // Actualizar el DOM con los valores calculados
    $('#subtotal').text(subtotal.toFixed(2));
    $('#iva').text(iva.toFixed(2));
    $('#total-span').text(totalGeneral.toFixed(2));
    $('#total-general').val(totalGeneral.toFixed(2));

    // Actualizar los inputs ocultos correspondientes
    $('input[name="subtotal"]').val(subtotal.toFixed(2));
    $('input[name="impuesto_total"]').val(iva.toFixed(2));
}

$(document).ready(function() {
    // Actualizar el resumen general al cambiar el total de un producto
    $('#ProductosBody').on('input', 'input[name^="productos"][name$="[total]"]', function() {
        actualizarResumen();
    });
});

// Evento para calcular montos al cambiar cantidad, precio o descuento en una fila
$(document).on('change', 'input[name^="productos"][name$="[cantidad]"], input[name^="productos"][name$="[precio]"], input[name^="productos"][name$="[descuento]"]', function() {
    calcularMontos($(this));
});

$(document).ready(function() {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=nombreProducto]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario
        console.log(query);

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=compras',
                method: 'POST',
                data: {buscar: query},
                dataType: 'json',
                success: function(data) {
                    console.log("Enviando búsqueda con valor:", query); // Depuración
                    console.log(data);
                    var listaProductos = $('#lista' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        console.log("entra en el condicional");
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function(key, producto) {
                            console.log(producto);
                            console.log(listaProductos);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="'+producto.producto_nombre+'" ' +
                                'data-tipo="'+producto.excento+'" ' +
                                'data-codigo="'+producto.cod_presentacion+'" ' +
                                'data-marca="'+producto.marca+'">' +
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion+' </a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                }
            });
        } else {
            $('#lista' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(){
        var selectedProduct = $(this).data('nombre'); 
        var codigo = $(this).data('codigo'); 
        var tipo = $(this).data('tipo');
        var cant=1;


        var inputId = $(this).closest('.list-group').prev('input').attr('id');
        var index = inputId.replace('nombreProducto', ''); // Extrae el índice del campo
        $('#' + inputId).val(selectedProduct); 

        $('#codigoProducto' + index).val(codigo); 
        $('#tipoProducto' + index).val(tipo); 
        $(this).closest('.list-group').fadeOut(); 
    });
});


$(document).ready(function() {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=lotes]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('lotes', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario
        var cod_detalle=$('#codigoProducto' + index).val();
        $('#cod-dp' + index).val(''); 
        console.log(query);
        console.log(cod_detalle);
        if (query.length > 2) {
            console.log("es mayor de 2");
            $.ajax({
                url: 'index.php?pagina=compras',
                method: 'POST',
                data: {b_lotes: query, cod: cod_detalle},
                dataType: 'json',
                success: function(data) {
                    console.log("Enviando búsqueda con valor:", query); // Depuración
                    console.log(data);
                    var listaProductos = $('#lista-lotes' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        console.log("entra en el condicional");
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function(key, producto) {
                            console.log(producto);
                            console.log(listaProductos);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="'+producto.lote+'" ' +
                                'data-fecha="'+producto.fecha_vencimiento+'" ' +
                                'data-codigo="'+producto.cod_detallep+'">' +
                                producto.lote + ' </a>'
                            );
                        });
                        listaProductos.fadeIn();
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                }
            });
        } else {
            $('#lista-lotes' + index).fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(){
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

$(document).ready(function() {
        // Obtener la fecha y hora actual
        var now = new Date();
        var fecha = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0');

        // Asignar el valor al input de fecha-hora
        $('#fecha-hora').val(fecha);
    });
</script>
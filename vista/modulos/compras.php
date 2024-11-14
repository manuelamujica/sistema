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
                                <table id="compras" class="table table-bordered table-striped table-hover datatable1" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Proveedor</th>
                                            <th>Sub total</th>
                                            <th>Fecha</th>
                                            <th>total</th>
                                            <th>status</th>
                                            <th>Info</th>
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
                                                            <span class="badge bg-success">Registrada</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Anulada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" style="position: center;" data-toggle="modal" data-target="#detallemodal" title="Ver detalle"
                                                        data-codigo="<?= $compras["cod_compra"];?>"
                                                        data-nombre="<?= $compras['razon_social'] ?>"
                                                        data-fecha="<?= $compras["fecha"] ?>"
                                                        data-total="<?= $compras["total"] ?>">
                                                        <i class="fas fa-plus"></i>
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
                                            <input type="text" class="form-control form-control-sm" id="rif-r" name="rif" placeholder="RIF" required>
                                            <input type="hidden" id="cod_prov" name="cod_prov" required>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectDivisa">Selecciona la divisa:</label>
                                            <select id="selectDivisa" class="form-control form-control-sm">
                                                <?php foreach($opciones as $divisa){ ?>
                                                <option data-cod="<?= $divisa['cod_divisa'] ?>"
                                                data-tasa="<?= $divisa['tasa'] ?>"
                                                data-abreviatura="<?= $divisa['abreviatura'] ?>" <?= $divisa['cod_divisa']==1 ? 'selected' : '' ?>>
                                                <?= $divisa['nombre'].' - '.$divisa['abreviatura'] ?></option>
                                                <?php }?>
                                                <!-- Agrega más divisas si es necesario -->
                                            </select>
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
                                        <th class="col-divisa" style="display: none;">Precio unitario (<span id="labelDivisa"></span>)</th>
                                        <th>Precio unitario (Bs)</th>
                                        <th style="width: 7%;">IVA</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="ProductosBody">
                                    <!-- Aquí se agregarán dinámicamente las filas de productos -->
                                </tbody>
                            </table>
                        
                        <!-- Botón para agregar nuevo producto -->
                        <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Producto</button>

                        
                        <div class="card card-outline card-primary float-right" style="width: 300px;">
                            <div class="card-body">
                                <p>Subtotal: Bs <span id="subtotal" class="text-bold">0.00</span></p>
                                <p>Exento: Bs <span id="exento" class="text-bold">0.00</span></p>
                                <p>Base imponible: Bs <span id="base-imponible" class="text-bold">0.00</span></p>
                                <p>IVA (16%): Bs <span id="iva" class="text-bold">0.00</span></p>
                                <p class="text-bold">TOTAL: Bs <span id="total-span" class="text-bold">0.00</span></p>
                                <input type="hidden" id="total-general" name="total_general">
                                <input type="hidden" id="subt" name="subtotal">
                                <input type="hidden" id="impuesto_total" name="impuesto_total">

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

<div class="modal fade" id="detallemodal" tabindex="-1" aria-labelledby="detalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="detalleLabel">Informacion de la compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nro_venta">Nro de compra</label>
                            <input type="text" class="form-control" id="nro-compra" name="nro_venta" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_cliente">Razon social</label>
                            <input type="text" class="form-control" id="r_social" name="nombre_cliente" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_venta">Fecha de la compra</label>
                            <input type="text" class="form-control" id="fecha_compra" name="fecha_venta" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Fecha de vencimiento</th>
                                    <th>Lote</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario (Bs)</th>
                                    <!--<th style="width: 7%;">IVA</th>-->
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="detalleBody">
                                <!-- Aquí se agregarán dinámicamente las filas de productos -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card card-outline card-primary float-right" style="width: 300px;">
                        <div class="card-body">
                            <p class="text-bold">TOTAL: Bs <span id="total_compra" class="text-bold">0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <input type="text" class="form-control" name="productos[${index}][cod_presentacion]" id="codigoProducto${index}" placeholder="Código de la presentacion" required readonly>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" name="productos[${index}][nombre_producto]" id="nombreProducto${index}" placeholder="Nombre del producto" required>
                    
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos(${index})">+</button>
                    </div>
                </div>
                <div id="lista${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
            </td>
            <td><input type="date" id="fecha-v${index}" class="form-control" name="productos[${index}][fecha_v]" placeholder="Fecha">
            <input type="hidden" id="cod-dp${index}" class="form-control" name="productos[${index}][cod-dp]"></td>
            <td>
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
        var cod=$('#selectDivisa').find('option:selected').data('cod');
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

$(document).ready(function() {
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

$(document).ready(function() {
    // Manejar el cambio de la divisa seleccionada
    $('#selectDivisa').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var tasa = parseFloat(selectedOption.data('tasa'));
        var abreviatura = selectedOption.data('abreviatura');
        var cod=selectedOption.data('cod')||1;
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

    /* $('[id^=precio_divisa]').on('input', function(){
        var inputId = $(this).attr('id');
        var index = inputId.replace('precio_divisa', '');
        var tasa = parseFloat($(this).attr('data-tasa'));
        var precioDivisa = parseFloat($(this).val()) || 0;
        var precioBs = (precioDivisa * tasa).toFixed(2);
        console.log(precioBs);
        $(`[name="productos[${index}][precio]"]`).val(precioBs);
        calcularMontos(index);
    });*/
    
    $(document).on('input', '[id^=precio_divisa]', function() {
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

    /* Calcular el precio en Bs al cambiar el valor en la columna de divisa
    $(document).on('input', '.precio-divisa', function() {
        var tasa = parseFloat($(this).attr('data-tasa'));
        var precioDivisa = parseFloat($(this).val()) || 0;
        var precioBs = (precioDivisa * tasa).toFixed(2);
        $(this).closest('tr').find('[name$="[precio]"]').val(precioBs);
        calcularMontos($(this).closest('tr').index()); // Llamar a la función de cálculo
    });*/
});


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

$(document).ready(function() {
    // Actualizar el resumen general al cambiar el total de un producto
    $('#ProductosBody').on('input', 'input[name^="productos"][name$="[total]"]', function() {
        actualizarResumen();
    });
});

$(document).on('input', 'input[name^="productos"][name$="[cantidad]"], input[name^="productos"][name$="[precio]"]', function() {
    // Obtén el índice del nombre del input, asumiendo que sigue el patrón "productos[index][campo]"
    var name = $(this).attr('name');
    var index = name.match(/\[([0-9]+)\]/)[1]; // Extrae el índice del nombre usando una expresión regular
    calcularMontos(index); // Llama a calcularMontos con el índice correcto
});

/* Evento para calcular montos al cambiar cantidad, precio o descuento en una fila
$(document).on('change', 'input[name^="productos"][name$="[cantidad]"], input[name^="productos"][name$="[precio]"], input[name^="productos"][name$="[descuento]"]', function() {
    calcularMontos($(this));
});*/

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
                                'data-unidad="'+producto.tipo_medida+'" ' +
                                'data-marca="'+producto.marca+'">' +
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.presentacion+' </a>'
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
    $(document).on('click', '.producto-item', function(){
        var selectedProduct = $(this).data('nombre'); 
        var codigo = $(this).data('codigo'); 
        var tipo = $(this).data('tipo');
        var unidad=$(this).data('unidad');
        var cant=1;

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
                        listaProductos.fadeOut();
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

    //Modal detalle
$(document).ready(function() {
    // Evento al abrir el modal
    $('#detallemodal').on('show.bs.modal', function(event) {
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
            success: function(data) {
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
                $.each(data, function(index, detalle) {
                    
                    $('#detalleBody').append(
                        '<tr>' +
                        '<td>' + detalle.cod_detallep + '</td>' +
                        '<td>' + detalle.presentacion + '</td>' +
                        '<td>' + detalle.fecha_vencimiento + '</td>' +
                        '<td>' + detalle.lote + '</td>' +
                        '<td class="stock">' + detalle.cantidad + '</td>' +
                        '<td>' + detalle.monto + '</td>' +

                        '<td>' + (detalle.monto*detalle.cantidad).toFixed(2) + '</td>' +
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
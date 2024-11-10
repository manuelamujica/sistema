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
                                            <!--<th>Accion</th>-->
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
                                                    <!--<td>
                                                        <?php if ($compras['compra_status'] == 1): ?>
                                                            <button name="anular" class="btn btn-danger btn-sm eliminar" title="anular" data-toggle="modal" data-target="#anularcompra"
                                                            data-cod="<?php //echo $compras['cod_compra']; ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-danger btn-sm disabled" title="anular">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                        anular cambia los estatus de compra
                                                        
                                                    </td>-->
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
                                        <th class="col-divisa" style="display: none;">Precio de compra (<span id="labelDivisa"></span>)</th>
                                        <th>Precio de compra-Bs</th>
                                        <th>Iva</th>
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
                                <!--
                                <p>Subtotal: Bs. <input type="number" class="form-control" name="subtotal" placeholder="Subtotal" style="width: 120px;" readonly></p>
                                <p>IVA (16%): Bs. <input type="number" class="form-control" name="impuesto_total" placeholder="IVA" style="width: 120px;" readonly></p>
                                <p class="text-bold">TOTAL: Bs. <span id="total-span" class="text-bold">0.00</span></p>
                                <input type="hidden" id="total-general" name="total_general">
                                    nuevo -->
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

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->
<div class="modal fade" id="anularcompra" tabindex="-1" aria-labelledby="anularcompraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anularcompraLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="anumodal" method="post"> 
                <p>¿Está seguro que desea eliminar la venta nro: <span id="codc"></span>?</p>
                <input type="hidden" id="codcom" name="codcom"> 
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" form="anumodal" class="btn btn-danger" id="confirmDelete" name="anular">Eliminar</button>
        </div>
        </div>
    </div>
</div>
<?php if (isset($eliminar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $eliminar["title"]; ?>',
            text: '<?php echo $eliminar["message"]; ?>',
            icon: '<?php echo $eliminar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'compras';
            }
        });
    </script>
<?php endif; ?>



<script>
    $('#anularcompra').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('cod');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #codc').text(codigo);
    modal.find('.modal-body #codcom').val(codigo);
});



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
                    <input type="text" id="lotes${index}" class="form-control" name="productos[${index}][lote]" placeholder="Lote">
                    <div id="lista-lotes${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                </div>
            </td>
            <td>
                <div class="input-group">
                    <input type="number" class="form-control" name="productos[${index}][cantidad]" value="1" step="0.001" onchange="calcularMontos(${index})" required>
                    <div class="input-group-append">
                        <span id="unidadm${index}" class="input-group-text" value=" "></span>
                    </div>
                </div>
            </td>
            <td class="col-divisa" style="display: none;">
                <input type="number" step="0.001" class="form-control precio-divisa" placeholder="Precio en divisa" onchange="calcularMontos(${index})">
            </td>
            <td><input type="number" class="form-control" step="0.001" name="productos[${index}][precio]" placeholder="Precio" onchange="calcularMontos(${index})"></td>
            <td><select class="form-control" id="tipoProducto${index}" name="productos[${index}][iva]" onchange="calcularMontos(${index})" required>
                <option value="1">E</option>
                <option value="2">G</option>
                </select></td>
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

    // Calcular el precio en Bs al cambiar el valor en la columna de divisa
    $(document).on('input', '.precio-divisa', function() {
        var tasa = parseFloat($(this).attr('data-tasa'));
        var precioDivisa = parseFloat($(this).val()) || 0;
        var precioBs = (precioDivisa * tasa).toFixed(2);
        $(this).closest('tr').find('[name$="[precio]"]').val(precioBs);
        calcularMontos($(this).closest('tr').index()); // Llamar a la función de cálculo
    });
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


    /* Sumar todos los valores de los inputs "total" de cada producto
    $('#ProductosBody input[name^="productos"][name$="[total]"]').each(function() {
        var totalp=parseFloat($(this).val()) || 0;
        subtotal += parseFloat($(this).val()) || 0;

    });
    $('#ProductosBody input[name^="productos"][name$="[iva]"]').each(function() {
        var tipoProducto = $(this).val();
        if(tipoProducto==1){
            exento
        }
        subtotal += parseFloat($(this).val()) || 0;
    });

    var tipoProducto = $('#tipoProducto' + index).val();
    if (tipoProducto == 2) {
            iva = subtotal * 0.16;
        }
    // Calcular el IVA y el total general
    console.log(iva);
    console.log(tipoProducto);
    var totalGeneral = subtotal + iva;*/

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


        var inputId = $(this).closest('.list-group').prev('input').attr('id');
        var index = inputId.replace('nombreProducto', ''); // Extrae el índice del campo
        $('#' + inputId).val(selectedProduct); 

        $('#codigoProducto' + index).val(codigo); 
        $('#tipoProducto' + index).val(tipo);
        $('#unidadm'+index).text(unidad);
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
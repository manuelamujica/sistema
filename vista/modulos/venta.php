<?php require_once 'controlador/venta.php'; ?>
<!-- MÓDULO TRANSACCIONAL, SOLO VISTA-->
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Ventas</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">ventas</li>
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
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ventaModal"> Registrar venta </button>
                            <!-- Botones PDF y Excel -->
                            <div class="card-tools">
                                <div class="float-right">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
            <div class="card-body">
                <!-- Barra de búsqueda -->
                <div class="input-group input-group-sm">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <br>
            <!-- Tabla de productos -->
            <div class="table-responsive">
            <table id="producto" class="table table-bordered table-striped datatable">
                <thead>
                        <tr>
                            <th>Nro. de Venta</th>
                            <th>Cliente</th>
                            <th>Descuento</th>
                            <th>Monto</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr> 
                </thead>
                <tbody>
                <?php foreach ($consulta as $venta) { ?>
                    <tr>
                        <td><?php echo $venta['cod_venta']?></td>
                        <td><?php echo $venta['nombre']." ".$venta['apellido']?></td>
                        <td><?php echo $venta['descuento'] ?></td>
                        <td><?php echo $venta['total'] ?></td>
                        <td>
                            <?php if ($venta['status_venta']==1):?>
                                <span class="badge bg-default">Pendiente</span>
                            <?php elseif ($venta['status_venta']==2):?>
                                <span class="badge bg-warning">Pago parcial</span>
                            <?php elseif ($venta['status_venta']==3):?>
                                <span class="badge bg-success">Completada</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Anulada</span>
                            <?php endif;?>
                        </td>
                        <td>anular..</td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</section>
</div>

<!-- Modal de Venta con búsqueda interactiva -->
<div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="ventaModalLabel">Registrar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ventamodal" method="post">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Información del Cliente y Nro de Venta -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cedula-rif">Cédula/RIF</label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <input type="text" class="form-control form-control-sm" id="cedula-rif" name="cedula-rif" placeholder="Cédula o RIF">
                                        <input type="hidden" id="cod_cliente" name="cod_cliente">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#modalRegistrarClientes">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre-cliente">Numero de telefono</label>
                                <input type="text" class="form-control form-control-sm" id="numero-cliente" name="numero-cliente" placeholder="telefono del cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre-cliente">Nombre del Cliente</label>
                                <input type="text" class="form-control form-control-sm" id="nombre-cliente" name="nombre-cliente" placeholder="Nombre del cliente" readonly>
                            </div>
                            <div class="form-row align-items-end">
                                <div class="col-md-6">
                                    <div class="form-group">

                                    <?php $ultimo=end($consulta); $nueva=$ultimo['cod_venta']+1; ?>
                                        <label for="numero">Nro Venta</label>
                                        <input type="text" class="form-control form-control-sm" id="nro_venta" value="<?=$nueva?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha-hora">Fecha y Hora</label>
                                        <input type="text" class="form-control form-control-sm" id="fecha-hora" name="fecha_hora" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla para agregar productos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="ventaProductosBody">
                                <!-- Se generan dinámicamente filas de productos -->
                            </tbody>
                        </table>
                    
                    <!-- Botón para agregar más filas de productos -->
                    <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Producto</button>
                    <div class="card card-outline card-primary float-right" style="width: 300px;">
                        <div class="card-body">
                            <p>Subtotal: S/ <span id="subtotal" class="text-bold">0.00</span></p>
                            <p>Exento: S/ <span id="exento" class="text-bold">0.00</span></p>
                            <p>Base imponible: S/ <span id="base-imponible" class="text-bold">0.00</span></p>
                            <p>IVA (16%): S/ <span id="iva" class="text-bold">0.00</span></p>
                            <p class="text-bold">TOTAL: S/ <span id="total-span" class="text-bold">0.00</span></p>
                            <input type="hidden" id="total-general" name="total_general">
                        </div>
                    </div>
                    </div>
                </div>
            </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" form="ventamodal" class="btn btn-primary" id="realizarVentaBtn">Realizar Venta</button>
                    <input type="hidden" name="registrarv">
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Modal de Resumen de Venta (este modal se abre desde el modal de "Ver Venta") -->
        <div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                        <h5 class="modal-title" id="pagoLabel">Resumen de Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pagoForm">
                            <div class="form-group">
                                <label for="efectivo">Cliente</label>
                                <input type="text" class="form-control form-control-sm" id="clientenom">
                            </div>
                            <div class="form-group">
                                <label for="tipo-pago">Tipo de Pago</label>
                                <select class="form-control form-control-sm" id="tipo-pago">
                                    <option>Efectivo</option>
                                    <option>Tarjeta</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="efectivo">Efectivo recibido</label>
                                <input type="text" class="form-control form-control-sm" id="efectivo">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="efectivo-exacto">
                                    <label class="form-check-label" for="efectivo-exacto">Efectivo Exacto</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="monto-efectivo">Monto Efectivo</label>
                                <input type="text" class="form-control form-control-sm" id="monto-efectivo" value="S/ 8.73" readonly>
                            </div>
                            <div class="form-group">
                                <label for="vuelto">Vuelto</label>
                                <input type="text" class="form-control form-control-sm" id="vuelto" value="S/ 0.00" readonly>
                            </div>
                        </form>
                        <div class="total-venta">
                            <p>Subtotal: S/ 7.84</p>
                            <p>Exento: S/ 2.25</p>
                            <p>Base imponible: S/ 5.59</p>
                            <p>IVA (16%): S/ 0.89</p>
                            <p><strong>TOTAL: S/ 8.73</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="finalizarPagoBtn">Finalizar Pago</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- =======================
MODAL REGISTRAR CLIENTES 
============================= -->
<script>
    <?php if (isset($_GET['abrirModal']) && $_GET['abrirModal'] == 1): ?>
        $(document).ready(function(){
            $('#ventaModal').modal('show');
        });
    <?php endif; ?>
</script>

<div class="modal fade" id="modalRegistrarClientes" tabindex="-1" aria-labelledby="modalRegistrarClientesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="clientesModalLabel">Registrar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formRegistrarClientes" action="index.php?pagina=clientes" method="post">
                    <div class="form-group">
                        <label for="cedula_rif">Cédula o Rif:</label>
                        <input type="text" class="form-control" name="cedula_rif" id="cedula_rif" placeholder="Ingrese la cédula" required>
                        <input type="hidden" name="vista" value="venta">
                        
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre" required>

                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" name="apellido" placeholder="Ingrese el apellido" required>

                        <label for="telefono">Teléfono:</label>
                        <input type="tel" class="form-control"  name="telefono" placeholder="Ingrese el teléfono">

                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="Ingrese el correo electrónico">

                        <label for="direccion">Direccion:</label>
                        <textarea class="form-control" name="direccion" placeholder="Ingrese la dirección de vivienda"></textarea>
                    </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('abrirModal', 'true');
                window.location='venta';
            }
        });

    </script>
<?php endif; ?>
<script>
    $('#cedula_rif').blur(function (e){
        var buscar=$('#cedula_rif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            if(response != ''){
                alert('El cliente ya se encuentra registrado');
            }
        },'json');
    });
    $(document).ready(function() {
        // Verifica si el valor 'abrirModal' está en localStorage
        if (localStorage.getItem('abrirModal') === 'true') {
            $('#ventaModal').modal('show');
            localStorage.removeItem('abrirModal');
        }
    });
</script>
<!-- =======================
FIN REGISTRAR CLIENTES
============================= -->





<script>
/* =======================
SCRIPT DE VENTA
============================= */


        // Cuando se haga clic en "Realizar Venta" en el modal de Ver Venta
        $('#realizarVentaBtn').click(function() {
            $('#pagoModal').modal('show');

            
            var cliente = $('#cliente').val(); //cargar el nombre del cliente
            $('#cliente-resumen').val(cliente);
        });

        
        $('#finalizarPagoBtn').click(function() {
            
            alert('Pago Finalizado');
            
            $('#resumenVentaModal').modal('hide');
            $('#ventaModal').modal('hide'); 
        });



var productoIndex = 1; 

// Función para agregar una nueva fila a la tabla
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" id="codigoProducto${index}" name="productos[${index}][codigo]" placeholder="Código del producto">
                <input type="hidden" class="form-control" id="tipoProducto${index}">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="nombreProducto${index}" name="productos[${index}][nombre]" placeholder="Nombre del producto">
                    <div id="lista-productos${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                    </div>
                </div>
            </td>
            <td>
                <input type="number" class="form-control" name="productos[${index}][cantidad]" id="cantidadProducto${index}" step="0.001" onchange="calcularTotal(${index})">
            </td>
            <td>
                <input type="text" class="form-control" id="precioProducto${index}" name="productos[${index}][precio]" placeholder="Precio" onchange="calcularTotal(${index})">
            </td>
            <td>
                <input type="text" class="form-control" id="total${index}" name="productos[${index}][total]" placeholder="Total" readonly>
            </td>
        </tr>
    `;
    
}

function agregarFila() {
    var nuevaFila = crearfila(productoIndex);
    $('#ventaProductosBody').append(nuevaFila);
    productoIndex++; 
}

function inicializarFilas() {
    for (let i = 1; i <= 2; i++) {
        agregarFila();
    }
}

$(document).ready(function() {
    inicializarFilas(); 
});


// Calcular el total de cada fila
function calcularTotal(index) {
    var cantidad = parseFloat($(`[name="productos[${index}][cantidad]"]`).val()) || 0;
    var precio = parseFloat($(`[name="productos[${index}][precio]"]`).val()) || 0;
    var total = cantidad * precio;
    $(`[name="productos[${index}][total]"]`).val(total.toFixed(2));

    actualizarResumen(); 
}

/*function mostrarProductos() {
    alert('Mostrar lista de productos');
}*/

// Función para actualizar el resumen de venta
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

    
    $('#subtotal').text(subtotal.toFixed(2));
    $('#exento').text(exento.toFixed(2));
    $('#base-imponible').text(baseImponible.toFixed(2));
    $('#iva').text(iva.toFixed(2));
    
    
    $('#total-span').text(totalGeneral.toFixed(2));
    $('#total-general').val(totalGeneral.toFixed(2));
}


$('#cedula-rif').blur(function (e){
        var buscar=$('#cedula-rif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            var nombre=response['nombre']+" "+response['apellido'];
            var telefono=response['telefono'];
            var apellido=response['apellido'];
            var codigo=response['cod_cliente'];

            var modal = $('#ventaModal');
            modal.find('.modal-body #numero-cliente').val(telefono);
            modal.find('.modal-body #nombre-cliente').val(nombre);
            modal.find('.modal-body #cod_cliente').val(codigo);
        },'json');
    });


    $(document).ready(function() {
    // Evento 'input' en los campos de productos dinámicos
    $(document).on('input', '[id^=nombreProducto]', function() {
        var inputId = $(this).attr('id');
        var index = inputId.replace('nombreProducto', ''); // Obtener el índice del campo
        var query = $(this).val(); // Valor ingresado por el usuario

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=venta',
                method: 'POST',
                data: {buscar: query},
                dataType: 'json', 
                success: function(data) {
                    var listaProductos = $('#lista-productos' + index);
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        // Recorrer los productos recibidos y mostrar nombre + código + precio concatenados
                        $.each(data, function(key, producto) {
                            var costo = parseFloat(producto.costo);
                            var precioVenta = costo + (costo * producto.porcen_venta / 100);
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-nombre="'+producto.nombre+'" ' +
                                'data-tipo="'+producto.excento+'" ' +
                                'data-codigo="'+producto.cod_producto+'" ' +
                                'data-marca="'+producto.marca+'" ' +
                                'data-precio="'+precioVenta+'">' +
                                producto.nombre + ' - ' + producto.marca + ' - ' + precioVenta + '</a>'
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
    $(document).on('click', '.producto-item', function(){
        var selectedProduct = $(this).data('nombre'); 
        var codigo = $(this).data('codigo'); 
        var precio = $(this).data('precio'); 
        var tipo = $(this).data('tipo');
        var cant=1;


        var inputId = $(this).closest('.list-group').prev('input').attr('id');
        var index = inputId.replace('nombreProducto', ''); // Extrae el índice del campo
        $('#' + inputId).val(selectedProduct); 

        // Completar otros campos automáticamente
        $('#codigoProducto' + index).val(codigo); 
        $('#tipoProducto' + index).val(tipo);
        $('#precioProducto' + index).val(precio); 
        $('#cantidadProducto' + index).val(cant).trigger('change');
        $(this).closest('.list-group').fadeOut(); 
    });
});

$(document).ready(function() {
    // Obtener la fecha y hora actual
    var now = new Date();
    var fecha = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0');

// Formatea la hora en el formato HH:MM:SS
var hora=String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    
    // Concatenar fecha y hora
    var fechaHora = fecha + ' ' + hora;
    
    // Asignar el valor al input de fecha-hora
    $('#fecha-hora').val(fechaHora);
});



$(document).ready(function() {
    $('#ventaModal').on('submit', function(event) {
        event.preventDefault(); // Evita que el formulario se envíe de forma estándar y recargue la página
        
        // Serializa los datos del formulario
        var datosVenta = $(this).serialize();

        // Enviar la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=venta', // Cambia esto por la URL de tu controlador
            data: datosVenta,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Cierra el modal de venta
                    $('#ventaModal').modal('hide');
                    
                    // Rellena el modal de pago con los datos de la venta registrada
                    $('#clientenom').val(response.cliente);
                    

                    // Abre el modal de pago
                    $('#pagoModal').modal('show');
                } else {
                    alert('Error al registrar la venta: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Estado:', status);
                console.error('Error:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                alert('Hubo un problema al registrar la venta. Inténtalo de nuevo.');
            }
        });
    });
});

</script>

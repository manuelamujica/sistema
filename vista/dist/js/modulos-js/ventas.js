
// Cuando se haga clic en "Realizar Venta" en el modal de Ver Venta
$('#realizarVentaBtn').click(function() {
    // Mostrar el modal de resumen de venta
    $('#resumenVentaModal').modal('show');

    // Aquí puedes cargar los datos de la venta y otros detalles
    var cliente = $('#cliente').val(); // Ejemplo: cargar el nombre del cliente
    $('#cliente-resumen').val(cliente);
});

// Acción para finalizar el pago
$('#finalizarPagoBtn').click(function() {
    // Procesar la venta (enviar los datos al servidor o similar)
    alert('Pago Finalizado');
    
    // Cerrar ambos modales
    $('#resumenVentaModal').modal('hide'); // Cerrar modal de resumen
    $('#ventaModal').modal('hide'); // Cerrar modal de ver venta
});



var productoIndex = 5; // Controla el número de filas

// Función para agregar una nueva fila a la tabla
function agregarFila() {
var nuevaFila = `
<tr>
    <td>
        <input type="text" class="form-control" id="codigo${productoIndex}" placeholder="Código del producto">
    </td>
    <td>
        <div class="input-group">
            <input type="text" class="form-control" id="producto${productoIndex}" placeholder="Nombre del producto">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
            </div>
        </div>
    </td>
    <td>
        <input type="number" class="form-control" id="cantidad${productoIndex}" value="1" min="1" onchange="calcularTotal(${productoIndex})">
    </td>
    <td>
        <input type="text" class="form-control" id="precio${productoIndex}" placeholder="Precio" readonly>
    </td>
    <td>
        <input type="text" class="form-control" id="total${productoIndex}" placeholder="Total" readonly>
    </td>
</tr>`;

$('#ventaProductosBody').append(nuevaFila); // Agrega la nueva fila al cuerpo de la tabla
productoIndex++; // Incrementa el índice del producto para la próxima fila
}

// Función para calcular el total por fila
function calcularTotal(index) {
var cantidad = parseFloat($('#cantidad' + index).val());
var precio = parseFloat($('#precio' + index).val());
var total = cantidad * precio;

$('#total' + index).val(total.toFixed(2)); // Actualiza el total en la fila
// Actualizar resumen de venta
actualizarResumen();
}

// Función para mostrar el listado de productos (se simula la función)
function mostrarProductos() {
alert('Mostrar lista de productos');
}

// Función para actualizar el resumen de venta
function actualizarResumen() {
var subtotal = 0;
var exento = 0;
var baseImponible = 0;
var iva = 0;

// Recorrer todas las filas para sumar el subtotal, exento y base imponible
for (var i = 1; i < productoIndex; i++) {
var totalProducto = parseFloat($('#total' + i).val()) || 0;
var tipoProducto = 2; // Aquí puedes obtener el tipo del producto desde tu lógica o base de datos (1: exento, 2: con IVA)

subtotal += totalProducto;

if (tipoProducto === 1) {
    exento += totalProducto; // Si el producto está exento de IVA
} else if (tipoProducto === 2) {
    baseImponible += totalProducto; // Si el producto tiene IVA
}
}

// Calcular IVA (16% de la base imponible)
iva = baseImponible * 0.16;

// Calcular total general
var totalGeneral = subtotal + iva;

// Actualizar los campos del resumen de venta
$('#subtotal').text(subtotal.toFixed(2));
$('#exento').text(exento.toFixed(2));
$('#base-imponible').text(baseImponible.toFixed(2));
$('#iva').text(iva.toFixed(2));
$('#total-general').text(totalGeneral.toFixed(2));
}






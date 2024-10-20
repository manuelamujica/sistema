
function nuevaCategoria() {
// Manejar el envío del formulario de nueva categoría
$('#formNuevaCategoria').on('submit', function(e) {
    e.preventDefault();

    // Obtener el valor del input
    var nombreCategoria = $('#n_categoria').val();

    // Enviar la nueva categoría usando AJAX
    $.ajax({
        url: 'categorias',
        type: 'POST',
        data: { nombreCategoria: nombreCategoria },

        success: function(response) {

            // Suponiendo que la respuesta incluye el ID y nombre de la nueva categoría
            var nuevaCategoria = JSON.parse(response);

            // Agregar la nueva categoría al select de categorías
            $('#categoria').append('<option value="' + nuevaCategoria.id + '">' + nuevaCategoria.nombre + '</option>');

            // Seleccionar automáticamente la nueva categoría
            $('#categoria').val(nuevaCategoria.id);

            // Cerrar el modal
            $('#modalAgregarCategoria').modal('hide');

            // Limpiar el formulario
            $('#formNuevaCategoria')[0].reset();
        },
        error: function() {
            alert('Error al agregar la categoría. Inténtalo nuevamente.');
        }
    });
});
}    

function nuevaUnidad() {
    alert('Mostrar modal');
    }


// Función general para calcular el precio de venta
function calcularPrecioVenta(modal) {
    var valorPorcentaje = Number(modal.find('#porcen').val());
    var costo = Number(modal.find('#costo').val());

    if (!isNaN(costo) && !isNaN(valorPorcentaje)) {
        var precioVenta = (valorPorcentaje / 100 + 1) * costo;
        modal.find('#precio').val(precioVenta.toFixed(2)); // Mostrar en el id precio el resultado obtenido con dos decimales
    } else {
        modal.find('#precio').val('0'); // Si es NaN, el precio es 0
    }
}

// Asigna la lógica de cálculo a los inputs de los modales
$(document).on('input', '#costo, #porcen', function() {
    var modal = $(this).closest('.modal'); // Detecta en qué modal estás trabajando (registro o edición)
    calcularPrecioVenta(modal); // Llama la función para calcular el precio
});

//Editar modal
$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');
    var marca = button.data('marca');
    var unidad = button.data('unidad');
    var present = button.data('present');
    var cantpresent = button.data('cantpresent');
    var categoria = button.data('categoria');
    var costo = button.data('costo');
    var iva = button.data('iva');
    var porcen = button.data('porcen');

    // Modal
    var modal = $(this); 
    modal.find('.modal-body #codigo').val(codigo);
    modal.find('.modal-body #name').val(nombre);
    modal.find('.modal-body #marca').val(marca);
    modal.find('.modal-body #unidad1').val(unidad);
    modal.find('.modal-body #presentacion').val(present);
    modal.find('.modal-body #cant_presentacion').val(cantpresent);
    modal.find('.modal-body #categoria1').val(categoria);
    modal.find('.modal-body #costo').val(costo);
    modal.find('.modal-body #iva').val(iva);
    modal.find('.modal-body #porcen').val(porcen);
    
    calcularPrecioVenta(modal); // Llama a la función para calcular el precio de venta cuando se abre el modal de edición
});

//Eliminar
$('#eliminarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');        

    var modal = $(this);
    modal.find('#p_nombre').text(nombre);
    modal.find('.modal-body #p_codigo').val(codigo);
});
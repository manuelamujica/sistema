

$(document).ready(function() {
    $(document).on('input', '#nombre', function() {
        var query = $(this).val(); // Valor ingresado por el usuario

        if (query.length > 2) { // Realiza la búsqueda si hay más de 2 caracteres
            $.ajax({
                url: 'index.php?pagina=productos',
                method: 'POST',
                data: { buscar: query }, // Envía la consulta de búsqueda
                dataType: 'json',
                success: function(data) {
                    var listaProductos = $('#lista-productos'); // Elemento donde mostrar resultados
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        $.each(data, function(key, producto) {
                            // Crea un nuevo elemento de lista para cada producto
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" ' +
                                'data-codigo="'+ producto.codigo +'" '+
                                'data-nombre="'+ producto.producto_nombre +'" ' +
                                'data-marca="'+ producto.marca +'" ' +
                                'data-categoria="'+ producto.cod_categoria +'" ' + // Cambiado a cod_categoria
                                'data-cat-nombre="'+ producto.cat_nombre +'">' + // Agregar cat_nombre para mostrar
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.cat_nombre + '</a>'
                            );
                        });
                        listaProductos.fadeIn(); // Muestra la lista de productos
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                        listaProductos.fadeIn(); // Asegúrate de que la lista se muestre incluso si no hay resultados
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud AJAX: ", textStatus, errorThrown); // Log de error
                }
            });
        } else {
            $('#lista-productos').fadeOut(); // Ocultar la lista si no hay suficientes caracteres
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(event) {
        event.preventDefault(); // Previene la acción por defecto del enlace
        var codigo = $(this).data('codigo'); 
        var selectedProduct = $(this).data('nombre'); 
        var marca = $(this).data('marca');
        var categoriaCod = $(this).data('categoria'); // Obtener el cod_categoria
        var categoriaNombre = $(this).data('cat-nombre'); // Obtener el nombre de la categoría

        console.log("Producto seleccionado: ", codigo, selectedProduct, marca, categoriaCod, categoriaNombre); // Log de producto seleccionado

        // Asigna los valores seleccionados a los inputs
        $('#nombre').val(selectedProduct).prop('readonly', true); // Campo de nombre bloqueado
        $('#marca').val(marca).prop('readonly', true); // Campo de marca bloqueado
        $('#categoria').val(categoriaCod).prop('readonly', true); // Asigna el cod_categoria y bloquea el campo

        $('#lista-productos').fadeOut(); // Oculta la lista después de seleccionar
    });
});


// NUEVA CATEGORIA DESDE PRODUCTO
//(Validar nombre)
    $('#nombrec').blur(function (e){
        var buscar=$('#nombrec').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                alert('La categoria ya se encuentra registrada');
            }
        },'json');
    });

$(document).ready(function() {
    // Verifica si el valor 'categoriaModal' está en localStorage
    if (localStorage.getItem('categoriaModal') === 'true') {
        $('#modalRegistrarProducto').modal('show');
        localStorage.removeItem('categoriaModal');
    }
});

//NUEVA UNIDAD DESDE PRODUCTO
//(Validar nombre)
$('#tipo_medidau').blur(function (e){
    var buscar=$('#tipo_medidau').val();
    $.post('index.php?pagina=unidad', {buscar}, function(response){
        if(response != ''){
            alert('La unidad ya se encuentra registrada');
        }
    },'json');
});

$(document).ready(function() {
    // Verifica si el valor 'unidadModal' está en localStorage
    if (localStorage.getItem('unidadModal') === 'true') {
        $('#modalRegistrarProducto').modal('show');
        localStorage.removeItem('unidadModal');
    }
});


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
    modal.find('.modal-body #cod_presentacion').val(codigo);
    modal.find('.modal-body #name').val(nombre);
    modal.find('.modal-body #marca').val(marca);
    modal.find('.modal-body #unidad1').val(unidad);
    modal.find('.modal-body #presentacion').val(present);
    modal.find('.modal-body #cant_presentacion').val(cantpresent);
    modal.find('.modal-body #categoria1').val(categoria);
    modal.find('.modal-body #costo').val(costo);
    modal.find('.modal-body #iva').val(iva);
    modal.find('.modal-body #porcen').val(porcen);

    modal.find('.modal-body #cod_producto').val(button.data('producto'));

    console.log('Código presentacion:', codigo);
    console.log('Codigo producto:', button.data('producto'));

    calcularPrecioVenta(modal); // Llama a la función para calcular el precio de venta cuando se abre el modal de edición
});

//Eliminar
$('#eliminarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');        

    var modal = $(this);
    modal.find('#p_nombre').text(nombre);
    modal.find('.modal-body #present_codigo').val(codigo);
    modal.find('.modal-body #p_codigo').val(button.data('producto'));

    console.log('Código presentacion:', codigo);
    console.log('Codigo producto:', button.data('producto'));
});



//Si ya existe un producto, tener la opcion de asignarle una presentacion
$(document).ready(function() {
    // Estils de la lista
    $('#lista-productos').css({
        'position': 'absolute', 
        'z-index': '1000',
        'width': '100%',
        'max-height': '200px',
        'overflow-y': 'auto',
        'border': '1px solid #ddd',
        'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.1)'
    });
    
    $(document).on('input', '#nombre', function() {
        var query = $(this).val(); // Valor ingresado por el usuario

        if (query.length > 2) { // Realiza la búsqueda si hay más de 2 caracteres
            $.ajax({
                url: 'index.php?pagina=productos',
                method: 'POST',
                data: { buscar: query },
                dataType: 'json',
                success: function(data) {
                    var listaProductos = $('#lista-productos'); // Elemento donde mostrar resultados
                    listaProductos.empty(); // Limpiar resultados anteriores

                    if (data.length > 0) {
                        $.each(data, function(key, producto) {
                            // Crea un nuevo elemento de lista para cada producto
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" style="color:#333333;"' +
                                'data-codigo="'+ producto.cod_producto +'" '+
                                'data-nombre="'+ producto.producto_nombre +'" ' +
                                'data-marca="'+ producto.marca +'" ' +
                                'data-categoria="'+ producto.cod_categoria +'" ' + 
                                'data-cat-nombre="'+ producto.cat_nombre +'">' + 
                                producto.producto_nombre + ' - ' + producto.marca + ' - ' + producto.cat_nombre + '</a>'
                            );
                        });
                        listaProductos.fadeIn(); // Muestra la lista de productos
                    } else {
                        listaProductos.append('<p class="list-group-item"><b>No se encontraron productos</b></p>');
                    }
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
        var nombre = $(this).data('nombre'); 
        var marca = $(this).data('marca');
        var categoriaCod = $(this).data('categoria'); 

        // Asigna los valores seleccionados a los inputs
        $('#cod_productoR').val(codigo).prop('readonly', true); 
        $('#nombre').val(nombre).prop('readonly', true); 
        $('#marca').val(marca).prop('readonly', true); 
        $('#categoria').val(categoriaCod).prop('readonly', true);
        
        $('#lista-productos').fadeOut(); // Oculta la lista después de seleccionar
    });

    $(document).on('blur', '#nombre', function(event) {
        $('#lista-productos').fadeOut(); // Oculta la lista si pierde el foco
    });

    // Botón "deshacer" para limpiar el formulario
    $('#deshacer').on('click', function() {
        // Restablece los valores y quita la propiedad readonly
        $('#cod_productoR').val('').prop('readonly', false);
        $('#nombre').val('').prop('readonly', false);
        $('#marca').val('').prop('readonly', false);
        $('#categoria').val('').prop('readonly', false);
    });
});

// VALIDAR ENTRADAS
$(document).ready(function() {
    // FUNCIONES
    function showError(selector, message) {
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
            'display': 'block',
            'color': 'red',
        });
    }
    function hideError(selector) {
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display', 'none');
    }
    // FIN FUNCIONES

    // Registrar
    $('#nombre').on('blur', function() {
        var nombre = $(this).val();
        if (nombre.trim() === '') {
            hideError('#nombre');
        } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
            showError('#nombre', 'Solo se permiten letras');
        } else {
            hideError('#nombre');
        }
    });
    $('#marca').on('blur', function() {
        var marca = $(this).val();
        if (marca.trim() === '') {
            hideError('#marca'); 
        } else if (!/^[a-zA-Z0-9\s]+$/.test(marca)) {
            showError('#marca', 'Solo se permiten letras y numeros');
        } else {
            hideError('#marca');
        }
    });
    $('#presentacion').on('blur', function() {
        var presentacion = $(this).val();
        if (presentacion.trim() === '') {
            hideError('#presentacion');
        } else if (!/^[a-zA-Z\s]+$/.test(presentacion)) {
            showError('#presentacion', 'Solo se permiten letras');
        } else {
            hideError('#presentacion');
        }
    });

    //PENDIENTE....NO FUNCIONA
    $('#cant_presentacion').on('input', function() {
        var cant_presentacion = $(this).val();
        if (cant_presentacion.trim() === '') {
            hideError('#cant_presentacion'); 
        } else if (!/^\d+(\.\d{1,2})?$/.test(cant_presentacion)) { // Permite números y un máximo de 2 decimales
            showError('#cant_presentacion', 'Solo se permiten números y un punto decimal opcional.');
        } else {
            hideError('#cant_presentacion'); 
        }
    });

});



// Función general para calcular el precio de venta
function calcularPrecioVenta(modal) {
    var valorPorcentaje = Number(modal.find('#porcen').val());
    var costo = Number(modal.find('#costo').val());
    var iva = Number(modal.find('#iva').val());

    if (!isNaN(costo) && !isNaN(valorPorcentaje)) {
        if(iva == 2){
            var costoiva = costo * 1.16;
            console.log(costoiva);
            var precioVenta = (valorPorcentaje / 100 + 1) * costoiva;
        }else{
            var precioVenta = (valorPorcentaje / 100 + 1) * costo;
        }
        modal.find('#precio').val(precioVenta.toFixed(2)); // Mostrar en el id precio el resultado obtenido con dos decimales
    } else {
        modal.find('#precio').val('0'); // Si es NaN, el precio es 0
    }
}

// Asigna la lógica de cálculo a los inputs de los modales
$(document).on('input', '#costo, #porcen, #iva', function() {
    var modal = $(this).closest('.modal'); // Detecta en qué modal estás trabajando (registro o edición)
    calcularPrecioVenta(modal); // Llama la función para calcular el precio
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


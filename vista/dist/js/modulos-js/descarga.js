//Modal detalle descarga

console.log('Abrio JS');

$(document).ready(function() {
    $('#detallemodal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que abrió el modal
        var codigoDescarga = button.data('codigo'); // Extraer el cod_descarga
        
        console.log(codigoDescarga);

        // Limpiar la tabla de detalles antes de cargar nuevos datos
        $('#detalleBody').empty();

        // Hacer una llamada AJAX para obtener los detalles de la descarga
        $.ajax({
            url: 'index.php?pagina=descarga',
            method: 'POST',
            data: { detalled: codigoDescarga },
            dataType: 'json',
            success: function(data) {
                
                // Verificar si hay datos en la respuesta
                console.log(data);
                
                
                if (data.length === 0) {
                    // Si no hay detalles mostrar un mensaje 
                    $('#detalleBody').append(
                        '<tr>' +
                            '<td colspan="4" class="text-center">No hay detalles disponibles para este producto</td>' +
                        '</tr>'
                    );
                } else {
                // Recorrer los datos devueltos y llenar la tabla
                $.each(data, function(index, detalle) {
                    let lote = detalle.lote || 'No disponible';
                        $('#detalleBody').append(
                            '<tr>' +
                                '<td>' + detalle.cod_det_descarga + '</td>' +
                                '<td>' + detalle.nombre + '</td>' +
                                '<td>' + detalle.presentacion_concat + '</td>' +
                                '<td>' + lote + '</td>' +
                                '<td>' + detalle.cantidad + '</td>' +
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

var productoIndex = 1;
// Función para crear una nueva fila en la tabla
function crearfila(index) {
    return `
        <tr id="fila${index}">
            <td>
                <input type="text" class="form-control" id="cod_detallep${index}" name="productos[${index}][cod_detallep]" placeholder="Código" readonly>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="nombreProducto${index}" name="productos[${index}][nombre]" placeholder="Nombre">
                    <div id="lista-productos${index}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                </div>
            </td>
            <td>
                <input type="text" class="form-control" id="presentacion${index}" name="productos[${index}][presentacion]" placeholder="Presentacion" readonly>
            </td>
            <td>
                <input type="text" class="form-control" id="lote${index}" name="productos[${index}][lote]" placeholder="Lote" readonly>
            </td>
            <td>
                <input type="text" class="form-control" id="stock${index}" name="productos[${index}][stock]" placeholder="Stock" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="productos[${index}][cantidad]" id="cantidad${index}" step="0.001">
            <div class="invalid-feedback" style="display: none;"></div>
            </td>
            <td>
                <button type="button" class="btn-sm btn-danger" onclick="eliminarFila(${index})">&times;</button>
            </td>
        </tr>
    `;
}

// Función para agregar una nueva fila a la tabla
function agregarFila() {
    var nuevaFila = crearfila(productoIndex);
    $('#detdescargabody').append(nuevaFila);
    productoIndex++;
}

// Función para eliminar una fila específica
function eliminarFila(index) {
    var fila = document.getElementById(`fila${index}`);
    if (fila) {
        fila.remove();
    }
}

// Al documentarse, inicializamos la primera fila
$(document).ready(function() {
    agregarFila(); // Inicializa la tabla con una fila

//Buscar para seleccionar detalle de producto
/*
$('#lista-productos').css({
    'position': 'absolute', 
    'z-index': '10000',
    'width': '100%',
    'max-height': '200px',
    'overflow-y': 'auto',
    'border': '1px solid #ddd',
    'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.1)'
    
});
*/
    // Creación de filas y búsqueda
    $(document).on('input', '[id^="nombreProducto"]', function() {
        var query = $(this).val(); 
        var index = $(this).attr('id').replace('nombreProducto', ''); // Obtener el índice de la fila actual
        var listaProductos = $('#lista-productos' + index); // Seleccionar la lista correspondiente

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=descarga',
                method: 'POST',
                data: { buscar: query },
                dataType: 'json',
                success: function(data) {
                    console.log("Datos recibidos:", data);
                    listaProductos.empty(); // Limpiar resultados anteriores
                    listaProductos.show(); // Mostrar la lista de productos

                    if (data.length > 0) {
                        $.each(data, function(key, detproducto) {
                            console.log(detproducto); // Verifica que cada objeto tiene las propiedades esperadas
                            listaProductos.append(
                                '<a href="#" class="list-group-item list-group-item-action producto-item" style="color:#333333; font-weight:normal;"' +
                                'data-codigo="'+ detproducto.cod_detallep +'" '+
                                'data-nombre="'+ detproducto.producto_nombre +'" ' +
                                'data-present="'+ detproducto.presentacion_concat +'" ' +
                                'data-stock="'+ detproducto.stock +'" ' +
                                'data-lote="'+ detproducto.lote +'" >' + 
                                detproducto.producto_nombre + ' - ' + detproducto.producto_marca +' - ' + detproducto.presentacion_concat + ' - ' + detproducto.lote + '</a>'
                            );
                        });
                    } else {
                        listaProductos.append('<p class="list-group-item">No se encontraron productos</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error); // Log del error
                }
            });
        } else {
            listaProductos.fadeOut();
        }
    });

    // Cuando el usuario selecciona un producto
    $(document).on('click', '.producto-item', function(event) {
        event.preventDefault(); // Previene la acción por defecto del enlace
        var codigo = $(this).data('codigo'); 
        var nombre = $(this).data('nombre'); 
        var present = $(this).data('present');
        var lote = $(this).data('lote'); 
        var stock = $(this).data('stock'); 

        //console.log("Producto seleccionado:", codigo, nombre, present, lote, stock); // Log de producto seleccionado

        // Asigna los valores seleccionados a los inputs
        var index = $(this).closest('.input-group').find('input').attr('id').replace('nombreProducto', '');
        $('#cod_detallep' + index).val(codigo); 
        $('#nombreProducto' + index).val(nombre);
        $('#presentacion' + index).val(present);
        $('#lote' + index).val(lote);
        $('#stock' + index).val(stock);
        
        $('#lista-productos' + index).fadeOut(); // Oculta la lista después de seleccionar
    });

    // Oculta la lista si el campo pierde el foco
    $(document).on('blur', '[id^="nombreProducto"]', function(event) {
        var index = $(this).attr('id').replace('nombreProducto', '');
        $('#lista-productos' + index).fadeOut(); // Oculta la lista si pierde el foco
    });


    //VALIDAR ENTRADAS
    //Funciones
    function showError(selector,message){
        $(selector).addClass('is-invalid');
        $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
            'display':'block',
            'color':'red',
        });
    }
    function hideError(selector){
        $(selector).removeClass('is-invalid');
        $(selector).next('.invalid-feedback').css('display','none');
    }
    //Fin Funciones

    // Fecha y hora
    $('#fecha').on('change', function() {
        const seleccionada = new Date($(this).val()); 
        const actual = new Date(); 
        if (seleccionada > actual) {
            showError('#fecha', 'La fecha y hora no pueden ser futuras.');
        } else {
            hideError('#fecha'); 
        }
    }); 

    //Descripcion
    $('#descripcion').on('blur', function() {
        var descripcion = $(this).val();
        if (descripcion.trim() === '') {
            hideError('#descripcion'); 
        } else if (descripcion.length > 100) {
            showError('#descripcion', 'El texto no debe exceder los 100 caracteres');
        } else if (!/^[a-zA-ZÀ-ÿ0-9!.'-,\s]+$/.test(descripcion)) {
            showError('#descripcion', 'Solo se permiten letras, números y caracteres: ! - . \' ,');
        } else {
            hideError('#descripcion');
        }
    });

    //Cantidad y stock
    $(document).on('input', '[id^="cantidad"]', function() {
        const index = $(this).attr('id').replace('cantidad', ''); // Obtiene el índice de la fila actual
        const cantidad = parseFloat($(this).val());
        const stock = parseFloat($('#stock' + index).val());
    
        if (cantidad > stock) {
            showError('#cantidad' + index, 'La cantidad no puede ser mayor al stock disponible.');
        } else {
            hideError('#cantidad' + index);
        }
    });

});




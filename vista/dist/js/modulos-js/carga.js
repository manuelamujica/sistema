


// Buscar carga existente
$('#fecha').blur(function (e) {
    var buscar = $('#fecha').val();
    $.post('index.php?pagina=carga', { buscar }, function (response) {
        console.log(response); // Agrega esto para depurar
        if (response != '') {
            alert('La carga ya se encuentra registrada');
        }
    }, 'json');
});

//DETALLES DISPONIBLES
$('#regisd').click(function (e) {
    var buscar1 = 'true';
    $.post('index.php?pagina=carga', { buscar1 }, function (response) {
        if (response.total > 0) {
            alert('Ya tiene detalles');
        }
    }, 'json');
});

// Inicializar Select2
$(document).ready(function () {
    $(".js-example-tags").select2({
        tags: true,
        placeholder: "Selecciona productos",
        allowClear: true
    });
});


//NO TOCAR
// Agregar más campos
document.getElementById('add-product').addEventListener('click', function () {
    // Clonamos la entrada de producto
    const productEntry = document.querySelector('.product-entry').cloneNode(true);

    // Limpiamos los valores de los campos clonados
    const inputs = productEntry.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.value = '';
    });

    // Agregamos el nuevo conjunto de campos al contenedor
    document.getElementById('product-container').appendChild(productEntry);
});



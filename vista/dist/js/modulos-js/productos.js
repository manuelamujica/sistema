//CALCULO PRECIO DE VENTA

    $('#costo, #porcen').on('input', function() {
        
    // Capturar el valor del porcentaje
        var valorPorcentaje = Number($('#porcen').val());

    // Capturar el valor de costo
        var costo = Number($('#costo').val());

    // Verifica que ambos valores sean válidos (no NaN)
        if (!isNaN(costo) && !isNaN(valorPorcentaje)) {
        // Calcular el valor final
        var precioVenta = (valorPorcentaje / 100 + 1) * costo;
        
        $('#precio').val(precioVenta.toFixed(2)); // Mostrar en el id precio el resultado obtenido con dos decimales
        //$('#precio').prop('readonly',true); // Cambiar la propiedad del input precio venta a solo lectura

    } else{
        $('#precio').val('0'); // Si NAN entonces precio es 0
    }
});


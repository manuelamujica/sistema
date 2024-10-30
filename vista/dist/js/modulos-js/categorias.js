//Validar entradas
    $(document).ready(function(){

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

    // Registrar
    $('#nombre').on('blur', function() {
        var nombre = $(this).val();
        if (nombre.trim() === '') {
            hideError('#nombre'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z\s]+$/.test(nombre)) {
            showError('#nombre', 'Solo letras');
        } else {
            hideError('#nombre');
        }
    });

    // Editar
    $('#name').on('blur', function() {
        var name = $(this).val();
        if (name.trim() === '') {
            hideError('#name'); // Si está vacío, oculta el error
        } else if (!/^[a-zA-Z\s]+$/.test(name)) {
            showError('#name', 'Solo letras');
        } else {
            hideError('#name');
        }
    });

});

    //Validar registrar
    $('#nombre').blur(function (e){
        var buscar=$('#nombre').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La categoria ya se encuentra registrada',
                    confirmButtonText: 'Aceptar'
                });
            }
        },'json');
    });
    //Validar editar
    $('#name').blur(function (e){
        var buscar=$('#name').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La categoria ya se encuentra registrada',
                    confirmButtonText: 'Aceptar'
                });
            }
        },'json');
    });

    //EDITAR
    $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var codigo = button.data('codigo');
            var nombre = button.data('nombre');
            var status = button.data('status');

            // Modal
            var modal = $(this); 
            modal.find('.modal-body #codigo').val(codigo);
            modal.find('.modal-body #name').val(nombre);
            modal.find('.modal-body #status').val(status);
        });

    //ELIMINAR
    $('#eliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nombre = button.data('nombre');
        var codigo = button.data('codigo');
        var status = button.data('status');

        var modal = $(this);
        modal.find('#catnombre').text(nombre);
        modal.find('.modal-body #catcodigo').val(codigo);
        modal.find('.modal-body #statusDelete').val(status);

        console.log(nombre,codigo,status);
    });
/* Clase electiva II let prueba1 = document.getElementById('nombre').value;
console.log(prueba1);

let nombre='Manuela Mujica'; console.log(typeof nombre);

let longitud = 5; 
if (nombre.length >= longitud){
console.log('El nombre: ' +nombre+ ' tiene la longitud requerida')}
else {
    console.log('El nombre:' +nombre+ ' NO tiene la longitud requerida')
        }

function sumar(a,b){
    if(typeof a !== 'number' || typeof b !== 'number'){
        return 'Los argumentos son validos';
    }
    return a + b;
}
console.log(sumar(8,4));
console.log(sumar(2,9));
*/

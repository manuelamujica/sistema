let prueba1 = document.getElementById('nombre').value;
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

    //Validar registrar
    $('#nombre').blur(function (e){
        var buscar=$('#nombre').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                alert('La categoria ya se encuentra registrada');
            }
        },'json');
    });
    //Validar editar
    $('#name').blur(function (e){
        var buscar=$('#name').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                alert('La categoria ya se encuentra registrada');
            }
        },'json');
    });
    //Editar
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
    //Eliminar
    $('#eliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nombre = button.data('nombre');
        var codigo = button.data('codigo');

        var modal = $(this);
        modal.find('#catnombre').text(nombre);
        modal.find('.modal-body #catcodigo').val(codigo);
    });


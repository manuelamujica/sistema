
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
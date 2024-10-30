
//Validar registro
$('#user').blur(function (e){
    var buscar=$('#user').val();
    $.post('index.php?pagina=usuarios', {buscar}, function(response){
        if(response != ''){
            alert('El usuario ya se encuentra registrado');
        }
    },'json');
});


//Validar editar
$('#usuario').blur(function (e){
    var buscar=$('#usuario').val();
    $.post('index.php?pagina=usuarios', {buscar}, function(response){
        if(response != ''){
            alert('El usuario ya se encuentra registrado');
        }
    },'json');
});

//Editar
$('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var codigo = button.data('codigo');
        var nombre = button.data('nombre');
        var user = button.data('user');
        var pass = button.data('password') 
        var rol = button.data('cod'); 
        var status = button.data('status');
        var origin = button.data('user');

        // Modal
        var modal = $(this); 
        modal.find('.modal-body #codigo').val(codigo);
        modal.find('.modal-body #name').val(nombre);
        modal.find('.modal-body #usuario').val(user);
        modal.find('.modal-body #password').val(pass);
        modal.find('.modal-body #roles').val(rol);            
        modal.find('.modal-body #status').val(status);
        modal.find('.modal-body #origin').val(origin); // Campo oculto con el valor original del user
    });

//Eliminar
$('#eliminarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('codigo');
    var nombre = button.data('nombre');
    //var user = button.data('user');
    //var pass = button.data('password');
    //var rol = button.data('cod');        

    var modal = $(this);
    modal.find('#username').text(nombre);
    modal.find('.modal-body #usercode').val(codigo);
});
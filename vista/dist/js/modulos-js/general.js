console.log('Abrio general.js')

$('#registrar').click(function(e){
    var buscar = 'true';
    $.post('index.php?pagina=general', {buscar}, function(response){
    if(response.total > 0){
        alert('Los datos de la empresa ya estan registrados');
    }
},'json');
});

$(document).ready(function() {
    //Editar modal
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var rif = button.data('rif');
        var rs = button.data('rs');
        var direc = button.data('direc');
        var tlf = button.data('tlf');
        var email = button.data('email')
        var des = button.data('des');


        // Modal
        var modal = $(this); 
        modal.find('.modal-body #rifE').val(rif);
        modal.find('.modal-body #rsE').val(rs);
        modal.find('.modal-body #direcE').val(direc);
        modal.find('.modal-body #tlfE').val(tlf);
        modal.find('.modal-body #emailE').val(email);
        modal.find('.modal-body #desE').val(des);
    });
});
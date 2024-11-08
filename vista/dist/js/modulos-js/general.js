$('#registrar').click(function(e){
    var buscar = 'true';
    $.post('index.php?pagina=general', {buscar}, function(response){
    if(response.total > 0){
        alert('Los datos de la empresa ya estan registrados');
    }
},'json');
});
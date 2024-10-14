/*=================
    RANGO DE FECHAS 
    (PLUGINS DATE RANGE PICKER)
    Vinculado en plantilla el JS principal
========================*/
/*if(localStorage.getItem("rango",rango) != null){
    $("#daterange-btn span").html(localStorage.getItem("rango"));
    } else{
        $("#daterange-btn span").html('<i class="fa fa-calendar"></i> Rango de fecha');
    }
    */
//Date range as a button
$('#daterange-btn').daterangepicker(
    {
    ranges   : {
        'Hoy'       : [moment(), moment()],
        'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
        'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
        'Mes pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
    },
    function (start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    //Capturar en una variable con formato de BD
    var fechaInicial = start.format('YYYY-MM-DD');
    var fechaFinal = end.format('YYYY-MM-DD');

    //Capturar el rango
    var rango = $("#daterange-btn span").html();
    
    localStorage.setItem("rango",rango);
    }
);
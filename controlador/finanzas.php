<?php
require_once 'modelo/finanzas.php';
require_once 'modelo/stockmensual.php';
require_once 'modelo/proyecciones.php';
require_once 'modelo/bitacora.php';
require_once 'modelo/analisisrentabilidad.php';
require_once 'modelo/presupuestos.php';

$_GET['ruta'] = 'finanzas';


$objFinanzas = new Finanzas();
$objStock = new StockMensual();
$objProyecciones = new Proyecciones();
$objBitacora = new Bitacora();
$objRentabilidad = new AnalisisRentabilidad();
$objPresupuestos = new Presupuestos();



// AJAX 
if(isset($_POST['accion'])) {
    $respuesta = [];
    
    switch($_POST['accion']) {
        case 'obtener_detalle_producto':
            if(isset($_POST['cod_producto'])) {
                $detalles = $objProyecciones->obtenerProyeccionesProducto($_POST['cod_producto']);
                $respuesta = [
                    'labels' => array_column($detalles, 'mes'),
                    'proyectado' => array_column($detalles, 'valor_proyectado'),
                    'real' => array_column($detalles, 'valor_real'),
                    'precision' => array_column($detalles, 'precision_valor')
                ];
            }
            break;
    }
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit;
}

// 1. Análisis de Cuentas - PENDIENTE


// 2. Rotación de Inventario - PENDIENTE


// 3. Rentabilidad - PENDIENTE


// 4. Presupuestos - PENDIENTE

// 5. Proyecciones - ACTIVO
$periodo_default = 6;
$proyecciones = $objProyecciones->obtenerProyeccionesFuturas($periodo_default);
$historico = $objProyecciones->obtenerHistoricoVentas($periodo_default);
$precision = $objProyecciones->obtenerPrecisionHistorica();
$proyecciones_historicas = $objProyecciones->obtenerProyeccionesHistoricas($periodo_default);


$datos_grafico = $objProyecciones->obtenerDatosGrafico();
$datos_presupuestos = $objPresupuestos->obtenerDatosGraficoPresupuestos();
$categorias_gasto = $objPresupuestos->obtenerCategorias();
$presupuestos = $objPresupuestos->obtenerPresupuestos();


// Registrar en bitácora
$objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Acceso a Finanzas', '', 'Finanzas');


$_GET['ruta'] = 'finanzas';

$datos_iniciales = [
    'proyecciones' => $proyecciones,
    'proyecciones_historicas' => $proyecciones_historicas,
    'historico' => $historico,
    'precision' => $precision,
    'datos_grafico_proyecciones' => $datos_grafico,
    'datos_presupuestos' => $datos_presupuestos,
    'categorias_gasto' => $categorias_gasto,
    'presupuestos' => $presupuestos
];

error_log('datos a frontend: ' . print_r($datos_iniciales, true));

require_once 'vista/plantilla.php';
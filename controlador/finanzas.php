<?php
require_once 'modelo/finanzas.php';
require_once 'modelo/stockmensual.php';
require_once 'modelo/proyecciones.php';
require_once 'modelo/bitacora.php';
require_once 'modelo/analisisrentabilidad.php';
require_once 'modelo/presupuestos.php';
require_once 'controlador/proyecciones.php';

$_GET['ruta'] = 'finanzas';

// Instancias de las clases necesarias
$objFinanzas = new Finanzas();
$objStock = new StockMensual();
$objProyecciones = new Proyecciones();
$objBitacora = new Bitacora();
$objRentabilidad = new AnalisisRentabilidad();
$objPresupuestos = new Presupuestos();

// Obtener datos de cada modelo
/*$cuentas = $objCuentas->getconsultar_cuentas();
$productos = $objProductos->getProductos();
$rentabilidad = $objRentabilidad->getAnalisisRentabilidad();
$stock = $objStock->getStockMensual();
$proyecciones = $objProyecciones->getProyecciones();*/

// ==========================================
// ANÁLISIS DE CUENTAS
// Tablas relacionadas:
// - cuentas_contables
// - asientos_contables
// - detalle_asientos
// - movimientos
// ==========================================
$datos_cuentas = [
    'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    'cuentas' => [
        'Caja Principal' => [
            'ingresos' => [12000, 19000, 15000, 25000, 22000, 30000],
            'egresos' => [10000, 15000, 12000, 20000, 18000, 25000]
        ],
        'Cuenta Banco 1' => [
            'ingresos' => [8000, 12000, 9000, 15000, 13000, 18000],
            'egresos' => [7000, 10000, 8000, 13000, 11000, 16000]
        ],
        'Caja Secundaria' => [
            'ingresos' => [5000, 7000, 6000, 9000, 8000, 11000],
            'egresos' => [4000, 6000, 5000, 8000, 7000, 9000]
        ],
        'Cuenta Banco 2' => [
            'ingresos' => [15000, 22000, 18000, 28000, 25000, 32000],
            'egresos' => [13000, 19000, 16000, 25000, 22000, 29000]
        ]
    ]
];

// ==========================================
// ROTACIÓN DE INVENTARIO
// Tablas relacionadas:
// - detalle_productos
// - stock_mensual
// - productos
// - presentacion_producto
// ==========================================
$datos_inventario = [
    'productos' => [
        "Producto A" => [
            'rotacion' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'stock' => [100, 80, 120, 90, 110, 95],
                'ventas' => [20, 30, 25, 35, 30, 40],
                'resumen' => [
                    'stockInicial' => 100,
                    'stockFinal' => 50,
                    'ventasTotal' => 150,
                    'rotacion' => 2.0,
                    'diasRotacion' => 182.5
                ]
            ]
        ],
        "Producto B" => [
            'rotacion' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'stock' => [200, 180, 160, 140, 120, 100],
                'ventas' => [40, 50, 45, 55, 50, 60],
                'resumen' => [
                    'stockInicial' => 200,
                    'stockFinal' => 100,
                    'ventasTotal' => 300,
                    'rotacion' => 3.0,
                    'diasRotacion' => 121.7
                ]
            ]
        ]
    ]
];

// ==========================================
// ANÁLISIS DE RENTABILIDAD
// Tablas relacionadas:
// - analisis_rentabilidad
// - detalle_productos
// - productos
// - detalle_ventas
// - ventas
// ==========================================
$datos_rentabilidad = [
    'productos' => [
        "Producto A" => [
            'rentabilidad' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'rentabilidad' => [15, 18, 16, 20, 19, 22],
                'roi' => [12, 15, 14, 17, 16, 19],
                'resumen' => [
                    'costoTotal' => 1000.00,
                    'ingresoTotal' => 1500.00,
                    'margenBruto' => 500.00,
                    'rentabilidad' => 33.33,
                    'roi' => 50.00
                ]
            ]
        ],
        "Producto B" => [
            'rentabilidad' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'rentabilidad' => [40, 42, 41, 45, 44, 47],
                'roi' => [35, 38, 37, 40, 39, 42],
                'resumen' => [
                    'costoTotal' => 2000.00,
                    'ingresoTotal' => 3500.00,
                    'margenBruto' => 1500.00,
                    'rentabilidad' => 42.86,
                    'roi' => 75.00
                ]
            ]
        ]
    ]
];

// ==========================================
// PRESUPUESTOS
// Tablas relacionadas:
// - presupuestos
// - categoria_movimiento
// - gasto
// - categoria_gasto
// - tipo_gasto
// - frecuencia_gasto
// ==========================================
$datos_presupuestos = [
    'categorias' => [
        ['id' => 1, 'nombre' => "Gastos Operativos"],
        ['id' => 2, 'nombre' => "Gastos Administrativos"],
        ['id' => 3, 'nombre' => "Gastos de Ventas"],
        ['id' => 4, 'nombre' => "Gastos de Marketing"],
        ['id' => 5, 'nombre' => "Gastos de Personal"]
    ],
    'datos_mensuales' => [
        'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        'por_categoria' => [
            "Gastos Operativos" => [
                'presupuesto' => [5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000],
                'gasto_real' => [4800, 5200, 4900, 5100, 5300, 4950, 5100, 5200, 4800, 5100, 5400, 5200]
            ],
            "Gastos Administrativos" => [
                'presupuesto' => [3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000],
                'gasto_real' => [2800, 3100, 2900, 3200, 2950, 3100, 2900, 3100, 3200, 2900, 3100, 3000]
            ],
            "Gastos de Ventas" => [
                'presupuesto' => [2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000],
                'gasto_real' => [1900, 2100, 1950, 2200, 1900, 2100, 2200, 1900, 2100, 2000, 2100, 2150]
            ],
            "Gastos de Marketing" => [
                'presupuesto' => [1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500],
                'gasto_real' => [1400, 1600, 1450, 1550, 1600, 1400, 1500, 1600, 1400, 1500, 1600, 1500]
            ],
            "Gastos de Personal" => [
                'presupuesto' => [4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000],
                'gasto_real' => [4000, 4000, 4100, 4000, 4200, 4000, 4100, 4000, 4200, 4000, 4100, 4000]
            ]
        ]
    ],
    'resumen_actual' => [
        'presupuesto_total' => 15500,
        'gasto_real_total' => 14950,
        'diferencia' => 550,
        'porcentaje_utilizado' => 96.45
    ]
];

// ==========================================
// PROYECCIONES
// Tablas relacionadas:
// - proyecciones_futuras
// - proyecciones_historicas
// - productos
// - ventas
// - detalle_ventas
// ==========================================
$datos_proyecciones = [
    'historico' => [
        'ventas' => [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            'valores' => [12000, 13500, 14200, 15000, 14800, 15500]
        ],
        'precision' => [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            'proyectado' => [11800, 13200, 14000, 14800, 15200, 15800],
            'real' => [12000, 13500, 14200, 15000, 14800, 15500]
        ]
    ],
    'proyecciones' => [
        'corto_plazo' => [
            'labels' => ['Jul', 'Ago', 'Sep'],
            'valores' => [16500, 17500, 18500]
        ],
        'mediano_plazo' => [
            'labels' => ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'valores' => [16500, 17500, 18500, 19500, 20500, 21500]
        ],
        'largo_plazo' => [
            'labels' => ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            'valores' => [16500, 17500, 18500, 19500, 20500, 21500, 22500, 23500, 24500, 25500, 26500, 27500]
        ]
    ],
    'productos' => [
        "Producto A" => [
            'proyeccion' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'ventas_reales' => [5000, 6000, 5500, 7000, 6500, 8000],
                'proyectado' => [5200, 5800, 6000, 6800, 7000, 7500]
            ],
            'resumen' => [
                'ventasActuales' => 15000.00,
                'proyecciones' => [
                    'tresMeses' => 16500.00,
                    'seisMeses' => 18750.00,
                    'docesMeses' => 22500.00
                ],
                'precision' => [
                    'promedio' => 97.38,
                    'mejor' => 98.10,
                    'peor' => 96.67
                ]
            ]
        ],
        "Producto B" => [
            'proyeccion' => [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'ventas_reales' => [8000, 9000, 8500, 10000, 9500, 11000],
                'proyectado' => [8200, 8800, 9000, 9800, 10000, 10500]
            ],
            'resumen' => [
                'ventasActuales' => 25000.00,
                'proyecciones' => [
                    'tresMeses' => 27500.00,
                    'seisMeses' => 31250.00,
                    'docesMeses' => 37500.00
                ],
                'precision' => [
                    'promedio' => 96.80,
                    'mejor' => 97.50,
                    'peor' => 96.10
                ]
            ]
        ]
    ]
];

// Si es una solicitud AJAX para detalles de producto
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

// Cargar datos para todas las secciones
// 1. Análisis de Cuentas - PENDIENTE
/*$cuentas = $objFinanzas->obtenerResumenFinanciero();
$movimientos = $objFinanzas->obtenerUltimosMovimientos();
$cuentas_activas = $objFinanzas->obtenerCuentasActivas();*/

// 2. Rotación de Inventario - PENDIENTE
/*$rotacion = $objStock->obtenerStockMensual();*/

// 3. Rentabilidad - PENDIENTE
/*$rentabilidad = $objRentabilidad->getAnalisisRentabilidad();*/

// 4. Presupuestos - PENDIENTE
/*$presupuestos = $objPresupuestos->obtenerPresupuestos();
$categorias_presupuesto = $objPresupuestos->obtenerCategorias();*/

// 5. Proyecciones - ACTIVO
$periodo_default = 6;
$proyecciones = $objProyecciones->obtenerProyeccionesFuturas($periodo_default);
$historico = $objProyecciones->obtenerHistoricoVentas($periodo_default);
$precision = $objProyecciones->obtenerPrecisionHistorica();

// Registrar en bitácora
$objBitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Acceso a Finanzas', '', 'Finanzas');

// Establecer la ruta para la vista
$_GET['ruta'] = 'finanzas';

$datos_iniciales = [
    'proyecciones' => $proyecciones_data ?? [],
    'historico' => $historico_data ?? [],
    'precision' => $precision_data ?? []
];

require_once 'plantilla.php';
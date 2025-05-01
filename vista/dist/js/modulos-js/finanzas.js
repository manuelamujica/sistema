// Datos de prueba (simulando respuesta del servidor)
const datosFinanzas = {
    historico: {
        ventas: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            valores: [12000, 13500, 14200, 15000, 14800, 15500]
        },
        precision: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            proyectado: [11800, 13200, 14000, 14800, 15200, 15800],
            real: [12000, 13500, 14200, 15000, 14800, 15500]
        }
    },
    proyecciones: {
        corto_plazo: {
            labels: ['Jul', 'Ago', 'Sep'],
            valores: [16500, 17500, 18500]
        },
        mediano_plazo: {
            labels: ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            valores: [16500, 17500, 18500, 19500, 20500, 21500]
        },
        largo_plazo: {
            labels: ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            valores: [16500, 17500, 18500, 19500, 20500, 21500, 22500, 23500, 24500, 25500, 26500, 27500]
        }
    },
    cuentas: {
        ingresos: [12000, 19000, 15000, 25000, 22000, 30000],
        egresos: [10000, 15000, 12000, 20000, 18000, 25000],
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun']
    },
    presupuestos: {
        categorias: [
            { id: 1, nombre: "Gastos Operativos" },
            { id: 2, nombre: "Gastos Administrativos" },
            { id: 3, nombre: "Gastos de Ventas" },
            { id: 4, nombre: "Gastos de Marketing" },
            { id: 5, nombre: "Gastos de Personal" }
        ],
        datos_mensuales: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            por_categoria: {
                "Gastos Operativos": {
                    presupuesto: [5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000, 5000],
                    gasto_real: [4800, 5200, 4900, 5100, 5300, 4950, 5100, 5200, 4800, 5100, 5400, 5200]
                },
                "Gastos Administrativos": {
                    presupuesto: [3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000, 3000],
                    gasto_real: [2800, 3100, 2900, 3200, 2950, 3100, 2900, 3100, 3200, 2900, 3100, 3000]
                },
                "Gastos de Ventas": {
                    presupuesto: [2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000, 2000],
                    gasto_real: [1900, 2100, 1950, 2200, 1900, 2100, 2200, 1900, 2100, 2000, 2100, 2150]
                },
                "Gastos de Marketing": {
                    presupuesto: [1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500, 1500],
                    gasto_real: [1400, 1600, 1450, 1550, 1600, 1400, 1500, 1600, 1400, 1500, 1600, 1500]
                },
                "Gastos de Personal": {
                    presupuesto: [4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000, 4000],
                    gasto_real: [4000, 4000, 4100, 4000, 4200, 4000, 4100, 4000, 4200, 4000, 4100, 4000]
                }
            }
        },
        resumen_actual: {
            presupuesto_total: 15500,
            gasto_real_total: 14950,
            diferencia: 550,
            porcentaje_utilizado: 96.45
        }
    },
    productos: {
        "Producto A": {
            rotacion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                stock: [100, 80, 120, 90, 110, 95],
                ventas: [20, 30, 25, 35, 30, 40],
                resumen: {
                    stockInicial: 100,
                    stockFinal: 50,
                    ventasTotal: 150,
                    rotacion: 2.0,
                    diasRotacion: 182.5
                }
            },
            proyeccion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                ventas_reales: [5000, 6000, 5500, 7000, 6500, 8000],
                proyectado: [5200, 5800, 6000, 6800, 7000, 7500]
            },
            rentabilidad: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                rentabilidad: [15, 18, 16, 20, 19, 22],
                roi: [12, 15, 14, 17, 16, 19],
                resumen: {
                    costoTotal: 1000.00,
                    ingresoTotal: 1500.00,
                    margenBruto: 500.00,
                    rentabilidad: 33.33,
                    roi: 50.00
                }
            },
            resumen: {
                ventasActuales: 15000.00,
                proyecciones: {
                    tresMeses: 16500.00,
                    seisMeses: 18750.00,
                    docesMeses: 22500.00
                },
                precision: {
                    promedio: 97.38,
                    mejor: 98.10,
                    peor: 96.67
                }
            }
        },
        "Producto B": {
            rotacion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                stock: [200, 180, 160, 140, 120, 100],
                ventas: [40, 50, 45, 55, 50, 60],
                resumen: {
                    stockInicial: 200,
                    stockFinal: 100,
                    ventasTotal: 300,
                    rotacion: 3.0,
                    diasRotacion: 121.7
                }
            },
            proyeccion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                ventas_reales: [8000, 9000, 8500, 10000, 9500, 11000],
                proyectado: [8200, 8800, 9000, 9800, 10000, 10500]
            },
            rentabilidad: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                rentabilidad: [40, 42, 41, 45, 44, 47],
                roi: [35, 38, 37, 40, 39, 42],
                resumen: {
                    costoTotal: 2000.00,
                    ingresoTotal: 3500.00,
                    margenBruto: 1500.00,
                    rentabilidad: 42.86,
                    roi: 75.00
                }
            },
            resumen: {
                ventasActuales: 25000.00,
                proyecciones: {
                    tresMeses: 27500.00,
                    seisMeses: 31250.00,
                    docesMeses: 37500.00
                },
                precision: {
                    promedio: 96.80,
                    mejor: 97.50,
                    peor: 96.10
                }
            }
        },
        "Producto C": {
            rotacion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                stock: [150, 130, 140, 120, 110, 90],
                ventas: [30, 35, 32, 40, 38, 45],
                resumen: {
                    stockInicial: 150,
                    stockFinal: 75,
                    ventasTotal: 225,
                    rotacion: 2.5,
                    diasRotacion: 146.0
                }
            },
            proyeccion: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                ventas_reales: [6000, 6500, 6200, 7000, 6800, 7500],
                proyectado: [6200, 6400, 6600, 6800, 7000, 7200]
            },
            rentabilidad: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                rentabilidad: [30, 32, 31, 35, 34, 37],
                roi: [25, 28, 27, 30, 29, 32],
                resumen: {
                    costoTotal: 1500.00,
                    ingresoTotal: 2250.00,
                    margenBruto: 750.00,
                    rentabilidad: 33.33,
                    roi: 50.00
                }
            },
            resumen: {
                ventasActuales: 10000.00,
                proyecciones: {
                    tresMeses: 10500.00,
                    seisMeses: 11250.00,
                    docesMeses: 12500.00
                },
                precision: {
                    promedio: 97.00,
                    mejor: 97.80,
                    peor: 96.20
                }
            }
        }
    }
};

// Configuración base de gráficos
const configGraficos = {
    type: 'line',
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        animation: {
            duration: 0
        },
        layout: {
            padding: {
                top: 10,
                right: 20,
                bottom: 10,
                left: 20
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    maxTicksLimit: 8
                }
            }
        }
    }
};

// Objeto para almacenar las instancias de los gráficos
const graficos = {};

// Objeto para almacenar las instancias de DataTables
const tablas = {};

// Inicialización de gráficos y componentes
$(document).ready(function() {
    console.log('Document ready - Initializing finanzas module');
    
    // Initialize tabs
    $('#pestañas button[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Ensure first tab is active
    $('#cuentas-tab').tab('show');
    console.log('Tabs initialized');

    // Initialize tables first
    inicializarTablas();
    console.log('Tables initialized');

    // Initialize main charts with data
    const ctxCuentas = document.getElementById('grafico-cuentas');
    if (ctxCuentas) {
        graficos.cuentas = ChartUtils.inicializarGraficoCuentas(ctxCuentas, datosFinanzas.cuentas);
    }

    const ctxPresupuesto = document.getElementById('presupuestoChart');
    if (ctxPresupuesto) {
        const categoriaSeleccionada = $('#categoria-gasto').val() || datosFinanzas.presupuestos.categorias[0].id;
        const nombreCategoria = datosFinanzas.presupuestos.categorias.find(c => c.id === parseInt(categoriaSeleccionada))?.nombre;
        const datosCategoriaActual = datosFinanzas.presupuestos.datos_mensuales.por_categoria[nombreCategoria];
        
        if (datosCategoriaActual) {
            graficos.presupuesto = ChartUtils.inicializarGraficoPresupuesto(
                ctxPresupuesto,
                {
                    labels: datosFinanzas.presupuestos.datos_mensuales.labels,
                    presupuesto: datosCategoriaActual.presupuesto,
                    gasto_real: datosCategoriaActual.gasto_real
                },
                nombreCategoria
            );
        }
    }

    const ctxProyecciones = document.getElementById('proyeccionesChart');
    if (ctxProyecciones) {
        graficos.proyecciones = ChartUtils.inicializarGraficoProyecciones(
            ctxProyecciones,
            datosFinanzas.historico.ventas,
            datosFinanzas.proyecciones.mediano_plazo
        );
    }

    console.log('Main charts initialized');

    // Initialize other components
    inicializarSelectoresMes();
    actualizarTablas();
    console.log('Other components initialized');

    // Initialize presupuestos components
    poblarCategoriasGasto();
    inicializarTablaPresupuestos();
    actualizarTablaPresupuestos();
    console.log('Presupuestos components initialized');
    
    // Initialize modal events
    $('.modal').on('show.bs.modal', function() {
        const modalId = this.id;
        const canvas = this.querySelector('canvas');
        if (canvas && !graficos[modalId]) {
            setTimeout(() => {
                const producto = $(this).data('producto');
                if (producto) {
                    graficos[modalId] = ChartUtils.inicializarGraficoModal(canvas, modalId, datosFinanzas.productos[producto], producto);
                }
            }, 50);
        }
    });
    
    // Initialize event handlers
    inicializarEventosModales();
    inicializarEventosTablas();
    console.log('Event handlers initialized');

    // Handle analysis type change
    $('#ver-historico').on('change', function() {
        console.log('Analysis type changed to:', $(this).val());
        const tipo = $(this).val();
        actualizarTipoAnalisis();
        inicializarEventosTablas();
    });

    // Handle projection period change
    $('#periodo-proyeccion').on('change', function() {
        console.log('Projection period changed to:', $(this).val());
        const tipo = $('#ver-historico').val();
        actualizarGraficoProyecciones(tipo);
    });

    // Handle category change
    $('#categoria-gasto').on('change', function() {
        const ctx = document.getElementById('presupuestoChart');
        if (ctx) {
            const categoriaSeleccionada = $(this).val();
            const nombreCategoria = datosFinanzas.presupuestos.categorias.find(c => c.id === parseInt(categoriaSeleccionada))?.nombre;
            const datosCategoriaActual = datosFinanzas.presupuestos.datos_mensuales.por_categoria[nombreCategoria];
            
            if (datosCategoriaActual) {
                graficos.presupuesto = ChartUtils.destroyChart(graficos.presupuesto);
                graficos.presupuesto = ChartUtils.inicializarGraficoPresupuesto(
                    ctx,
                    {
                        labels: datosFinanzas.presupuestos.datos_mensuales.labels,
                        presupuesto: datosCategoriaActual.presupuesto,
                        gasto_real: datosCategoriaActual.gasto_real
                    },
                    nombreCategoria
                );
            }
        }
        actualizarTablaPresupuestos();
    });

    // Set initial state of period selector based on analysis type
    const initialTipo = $('#ver-historico').val();
    const $periodoSelect = $('#periodo-proyeccion');
    
    if (initialTipo === 'proyecciones') {
        $periodoSelect
            .html(`
                <option value="3">Próximos 3 meses</option>
                <option value="6">Próximos 6 meses</option>
                <option value="12">Próximo año</option>
            `)
            .val('6'); // Default to 6 months
    } else {
        $periodoSelect
            .html(`
                <option value="3">Últimos 3 meses</option>
                <option value="6">Últimos 6 meses</option>
                <option value="12">Último año</option>
            `)
            .val('6')
            .prop('disabled', true);
    }

    console.log('Finanzas module initialization complete');
});

function actualizarGraficoProyecciones(tipo) {
    console.log('Updating projections chart with type:', tipo);
    
    const ctx = document.getElementById('proyeccionesChart');
    if (!ctx) {
        console.error('Canvas not found for proyeccionesChart');
        return;
    }

    graficos.proyecciones = ChartUtils.destroyChart(graficos.proyecciones);

    if (tipo === 'proyecciones') {
        const periodo = $('#periodo-proyeccion').val();
        let proyeccionData;

        switch(periodo) {
            case '3':
                proyeccionData = datosFinanzas.proyecciones.corto_plazo;
                break;
            case '6':
                proyeccionData = datosFinanzas.proyecciones.mediano_plazo;
                break;
            case '12':
                proyeccionData = datosFinanzas.proyecciones.largo_plazo;
                break;
            default:
                proyeccionData = datosFinanzas.proyecciones.mediano_plazo;
        }

        graficos.proyecciones = ChartUtils.inicializarGraficoProyecciones(
            ctx,
            datosFinanzas.historico.ventas,
            proyeccionData
        );

        $('#tabla-proyecciones').show();
        $('#tabla-precision').hide();
    } else {
        const precision = datosFinanzas.historico.precision;
        console.log('Creating precision chart with:', precision);

        graficos.proyecciones = new Chart(ctx, {
            type: 'line',
            data: {
                labels: precision.labels,
                datasets: [
                    ChartUtils.createLineDataset('Proyectado', precision.proyectado, true),
                    ChartUtils.createLineDataset('Real', precision.real)
                ]
            },
            options: ChartUtils.getChartOptions('Precisión Histórica')
        });

        $('#tabla-proyecciones').hide();
        $('#tabla-precision').show();
    }
}

// Funciones para mostrar modales
function mostrarModalRotacion(producto) {
    const datos = datosFinanzas.productos[producto]?.rotacion;
    if (!datos) return;

    const ctx = document.getElementById('grafico-rotacion').getContext('2d');
    crearGrafico(ctx, {
        ...configGraficos,
        data: {
            labels: datos.labels,
            datasets: [
                {
                    label: 'Stock',
                    data: datos.stock,
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                },
                {
                    label: 'Ventas',
                    data: datos.ventas,
                    borderColor: 'rgb(255, 159, 64)',
                    tension: 0.1
                }
            ]
        },
        options: {
            ...configGraficos.options,
            plugins: {
                ...configGraficos.options.plugins,
                title: {
                    display: true,
                    text: `Rotación de ${producto}`
                }
            }
        }
    }, 'rotacion');

    // Agregar resumen
    const resumen = datos.resumen;
    const resumenHtml = `
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-primary">Rotación</h6>
                        <p class="card-text h3 text-primary">${resumen.rotacion.toFixed(1)}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-success">Ventas Totales</h6>
                        <p class="card-text h3 text-success">${resumen.ventasTotal}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-info">Días de Rotación</h6>
                        <p class="card-text h3 text-info">${resumen.diasRotacion.toFixed(1)}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#modal-rotacion .modal-body').append(resumenHtml);

    $('#modal-rotacion').modal('show');

    // Limpiar resumen al cerrar el modal
    $('#modal-rotacion').on('hidden.bs.modal', function () {
        $('#modal-rotacion .modal-body .row').remove();
    });
}

function mostrarModalProyeccion(producto, datos = null) {
    console.log('mostrarModalProyeccion called with:', { producto, datos });
    
    // If datos is not provided, get it from datosFinanzas
    if (!datos) {
        datos = datosFinanzas.productos[producto];
    }
    
    if (!datos) {
        console.error('No se proporcionaron datos para el producto:', producto);
        return;
    }

    const tipoAnalisis = $('#ver-historico').val();
    const historico = datos.proyeccion;
    const resumen = datos.resumen;
    const modalId = '#modal-proyeccion';
    
    // Update modal title
    ChartUtils.modalUtils.updateModalTitle(modalId, `${tipoAnalisis === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica'} - ${producto}`);
    
    // Store product reference on modal
    $(modalId).data('producto', producto);
    
    // Clean up existing chart
    graficos.modalProyeccion = ChartUtils.destroyChart(graficos.modalProyeccion);
    
    // Set canvas container height
    ChartUtils.modalUtils.ensureCanvasHeight(document.querySelector('#grafico-proyeccion'));

    // Setup modal events
    ChartUtils.modalUtils.setupModal(modalId, 
        // onShown handler
        function() {
            const ctx = document.getElementById('grafico-proyeccion');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            console.log('Creating new chart');
            
            if (tipoAnalisis === 'proyecciones') {
                const ultimoValorHistorico = historico.ventas_reales[historico.ventas_reales.length - 1];
                const datosHistoricos = historico.ventas_reales;
                const labelsHistoricos = historico.labels;
                const mesesFuturos = ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                const todosLabels = [...labelsHistoricos, ...mesesFuturos];

                const datosProyeccion = [
                    ...Array(datosHistoricos.length - 1).fill(null),
                    ultimoValorHistorico,
                    resumen.proyecciones.tresMeses,
                    (resumen.proyecciones.tresMeses + resumen.proyecciones.seisMeses) / 2,
                    resumen.proyecciones.seisMeses,
                    (resumen.proyecciones.seisMeses + resumen.proyecciones.docesMeses) / 2,
                    (resumen.proyecciones.seisMeses + resumen.proyecciones.docesMeses) / 2,
                    resumen.proyecciones.docesMeses
                ];

                graficos.modalProyeccion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: todosLabels,
                        datasets: [
                            ChartUtils.createLineDataset(
                                'Ventas Reales',
                                [...datosHistoricos, ...Array(mesesFuturos.length).fill(null)]
                            ),
                            ChartUtils.createLineDataset(
                                'Proyecciones',
                                datosProyeccion,
                                true
                            )
                        ]
                    },
                    options: ChartUtils.getChartOptions(`Proyección de Ventas - ${producto}`)
                });
            } else {
                graficos.modalProyeccion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: historico.labels,
                        datasets: [
                            ChartUtils.createLineDataset(
                                'Ventas Reales',
                                historico.ventas_reales
                            ),
                            ChartUtils.createLineDataset(
                                'Proyectado',
                                historico.proyectado,
                                true
                            )
                        ]
                    },
                    options: ChartUtils.getChartOptions(`Precisión Histórica - ${producto}`)
                });
            }
        },
        // onHidden handler
        function() {
            graficos.modalProyeccion = ChartUtils.destroyChart(graficos.modalProyeccion);
        }
    );

    // Show the modal
    $(modalId).modal('show');

    // Update summary section
    actualizarResumenModal(producto, resumen);
}

function actualizarResumenModal(producto, resumen) {
    $('#resumen-proyeccion').html(`
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-primary">Ventas Actuales</h6>
                        <p class="card-text h3 text-primary">${formatearNumero(resumen.ventasActuales)}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-success">Proyección 3 Meses</h6>
                        <p class="card-text h3 text-success">${formatearNumero(resumen.proyecciones.tresMeses)}</p>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i>
                            ${formatearPorcentaje((resumen.proyecciones.tresMeses / resumen.ventasActuales - 1) * 100)}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-info">Precisión Histórica</h6>
                        <p class="card-text h3 text-info">${resumen.precision.promedio.toFixed(1)}%</p>
                        <small class="text-info">
                            Rango: ${resumen.precision.peor}% - ${resumen.precision.mejor}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3">Proyecciones a Futuro</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th class="text-end">Proyección</th>
                                        <th class="text-end">Crecimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>3 Meses</td>
                                        <td class="text-end">${formatearNumero(resumen.proyecciones.tresMeses)}</td>
                                        <td class="text-end text-success">
                                            <i class="bi bi-arrow-up"></i>
                                            ${formatearPorcentaje((resumen.proyecciones.tresMeses / resumen.ventasActuales - 1) * 100)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6 Meses</td>
                                        <td class="text-end">${formatearNumero(resumen.proyecciones.seisMeses)}</td>
                                        <td class="text-end text-success">
                                            <i class="bi bi-arrow-up"></i>
                                            ${formatearPorcentaje((resumen.proyecciones.seisMeses / resumen.ventasActuales - 1) * 100)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12 Meses</td>
                                        <td class="text-end">${formatearNumero(resumen.proyecciones.docesMeses)}</td>
                                        <td class="text-end text-success">
                                            <i class="bi bi-arrow-up"></i>
                                            ${formatearPorcentaje((resumen.proyecciones.docesMeses / resumen.ventasActuales - 1) * 100)}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);
}

function mostrarModalRentabilidad(producto) {
    const datos = datosFinanzas.productos[producto]?.rentabilidad;
    if (!datos) return;

    const ctx = document.getElementById('grafico-rentabilidad').getContext('2d');
    crearGrafico(ctx, {
        ...configGraficos,
        data: {
            labels: datos.labels,
            datasets: [
                {
                    label: 'Rentabilidad',
                    data: datos.rentabilidad,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'ROI',
                    data: datos.roi,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }
            ]
        },
        options: {
            ...configGraficos.options,
            plugins: {
                ...configGraficos.options.plugins,
                title: {
                    display: true,
                    text: `Rentabilidad de ${producto}`
                }
            }
        }
    }, 'rentabilidad');

    // Actualizar resumen
    const resumen = datos.resumen;
    $('#resumen-rentabilidad').html(`
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-success">Rentabilidad</h6>
                        <p class="card-text h3 text-success">${resumen.rentabilidad}%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-primary">ROI</h6>
                        <p class="card-text h3 text-primary">${resumen.roi}%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-info">Margen Bruto</h6>
                        <p class="card-text h3 text-info">$${formatearNumero(resumen.margenBruto)}</p>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('#modal-rentabilidad').modal('show');
}

function mostrarModalPrecision(producto) {
    const datos = datosFinanzas.productos[producto];
    if (!datos) {
        console.error('No se encontraron datos para el producto:', producto);
        return;
    }

    const modalId = '#modal-precision';
    
    // Update modal title
    ChartUtils.modalUtils.updateModalTitle(modalId, `Precisión Histórica - ${producto}`);

    // Clean up existing chart
    graficos.modalPrecision = ChartUtils.destroyChart(graficos.modalPrecision);

    const historico = datos.proyeccion;
    const precision = datos.resumen.precision;

    // Setup modal events
    ChartUtils.modalUtils.setupModal(modalId, 
        // onShown handler
        function() {
            const ctx = document.getElementById('grafico-precision');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            graficos.modalPrecision = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: historico.labels,
                    datasets: [
                        ChartUtils.createLineDataset(
                            'Proyectado',
                            historico.proyectado,
                            true
                        ),
                        ChartUtils.createLineDataset(
                            'Real',
                            historico.ventas_reales
                        )
                    ]
                },
                options: ChartUtils.getChartOptions(`Precisión Histórica - ${producto}`)
            });
        },
        // onHidden handler
        function() {
            graficos.modalPrecision = ChartUtils.destroyChart(graficos.modalPrecision);
        }
    );

    // Update summary section with monthly comparison
    $('#resumen-precision').html(`
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-primary">Precisión Promedio</h6>
                        <p class="card-text h3 text-primary">${precision.promedio.toFixed(1)}%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-success">Mejor Precisión</h6>
                        <p class="card-text h3 text-success">${precision.mejor.toFixed(1)}%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-info">Peor Precisión</h6>
                        <p class="card-text h3 text-info">${precision.peor.toFixed(1)}%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3">Análisis de Precisión</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th class="text-end">Proyectado</th>
                                        <th class="text-end">Real</th>
                                        <th class="text-end">Diferencia</th>
                                        <th class="text-end">Precisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${historico.labels.map((mes, i) => {
                                        const proyectado = historico.proyectado[i];
                                        const real = historico.ventas_reales[i];
                                        const diff = real - proyectado;
                                        const precisionMes = (100 - (Math.abs(diff) / real * 100)).toFixed(1);
                                        return `
                                            <tr>
                                                <td>${mes}</td>
                                                <td class="text-end">${formatearNumero(proyectado)}</td>
                                                <td class="text-end">${formatearNumero(real)}</td>
                                                <td class="text-end ${diff >= 0 ? 'text-success' : 'text-danger'}">
                                                    ${diff >= 0 ? '+' : ''}${formatearNumero(diff)}
                                                </td>
                                                <td class="text-end">${precisionMes}%</td>
                                            </tr>
                                        `;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);

    // Show the modal
    $(modalId).modal('show');
}

// Función para actualizar datos
function actualizarDatos() {
    const periodo = obtenerPeriodo();
    console.log('Actualizando datos con período:', periodo);
    // Aquí irá la lógica para actualizar los datos según el período seleccionado
    actualizarTablas();
}

// Función para formatear números
function formatearNumero(numero) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'USD'
    }).format(numero);
}

// Función para formatear porcentajes
function formatearPorcentaje(numero) {
    return new Intl.NumberFormat('es-VE', {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numero / 100);
}

// Función para inicializar todas las tablas
function inicializarTablas() {
    // Tabla de rotación de inventario
    tablas.rotacion = $('#tabla-rotacion').DataTable({
        responsive: true,
        autoWidth: false,
        width: '100%',
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: [], // Se llenará dinámicamente
        columns: [
            { data: 'producto' },
            { data: 'stockInicial', className: 'text-end' },
            { data: 'stockFinal', className: 'text-end' },
            { data: 'ventas', className: 'text-end' },
            { data: 'rotacion', className: 'text-end' },
            { data: 'diasRotacion', className: 'text-end' }
        ],
        createdRow: function(row, data) {
            $(row).attr('data-producto', data.producto);
            $(row).addClass('cursor-pointer');
        }
    });

    // Tabla de proyecciones futuras
    tablas.proyecciones = $('#tabla-proyecciones-futuras').DataTable({
        responsive: true,
        autoWidth: false,
        width: '100%',
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: [], // Se llenará dinámicamente
        columns: [
            { data: 'producto' },
            { 
                data: 'ventasActuales',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'proyeccion3m',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'proyeccion6m',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'proyeccion12m',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    return `<i class="bi bi-arrow-${data}-circle-fill text-${data === 'up' ? 'success' : 'danger'}"></i>`;
                }
            }
        ],
        createdRow: function(row, data) {
            $(row).attr('data-producto', data.producto);
            $(row).addClass('cursor-pointer');
        }
    });

    // Tabla de precisión histórica
    tablas.precision = $('#tabla-precision-historica').DataTable({
        responsive: true,
        autoWidth: false,
        width: '100%',
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: [], // Se llenará dinámicamente
        columns: [
            { data: 'producto' },
            { 
                data: 'precisionPromedio',
                className: 'text-end',
                render: function(data) {
                    return data.toFixed(2) + '%';
                }
            },
            { 
                data: 'mejorPrecision',
                className: 'text-end',
                render: function(data) {
                    return data.toFixed(2) + '%';
                }
            },
            { 
                data: 'peorPrecision',
                className: 'text-end',
                render: function(data) {
                    return data.toFixed(2) + '%';
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    return `<i class="bi bi-arrow-${data}-circle-fill text-${data === 'up' ? 'success' : 'danger'}"></i>`;
                }
            }
        ],
        createdRow: function(row, data) {
            $(row).attr('data-producto', data.producto);
            $(row).addClass('cursor-pointer');
        }
    });

    // Tabla de rentabilidad
    tablas.rentabilidad = $('#tabla-rentabilidad').DataTable({
        responsive: true,
        autoWidth: false,
        width: '100%',
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: [], // Se llenará dinámicamente
        columns: [
            { data: 'producto' },
            { 
                data: 'costoTotal',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'ingresoTotal',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'margenBruto',
                className: 'text-end',
                render: function(data) {
                    return formatearNumero(data);
                }
            },
            { 
                data: 'rentabilidad',
                className: 'text-end text-success',
                render: function(data) {
                    return data.toFixed(2) + '%';
                }
            },
            { 
                data: 'roi',
                className: 'text-end text-success',
                render: function(data) {
                    return data.toFixed(2) + '%';
                }
            }
        ],
        createdRow: function(row, data) {
            $(row).attr('data-producto', data.producto);
            $(row).addClass('cursor-pointer');
        }
    });

    // Eventos de click en las filas
    $('#tabla-rotacion tbody').on('click', 'tr', function() {
        const producto = $(this).data('producto');
        mostrarModalRotacion(producto);
    });

    $('#tabla-proyecciones-futuras tbody').on('click', 'tr', function() {
        const producto = $(this).data('producto');
        if ($('#ver-historico').val() === 'proyecciones') {
            mostrarModalProyeccion(producto);
        } else {
            mostrarModalPrecision(producto);
        }
    });

    $('#tabla-precision-historica tbody').on('click', 'tr', function() {
        const producto = $(this).data('producto');
        mostrarModalPrecision(producto);
    });

    $('#tabla-rentabilidad tbody').on('click', 'tr', function() {
        const producto = $(this).data('producto');
        mostrarModalRentabilidad(producto);
    });
}

// Función para actualizar los datos de las tablas
function actualizarTablas() {
    // Actualizar tabla de rotación
    tablas.rotacion.clear().rows.add(Object.keys(datosFinanzas.productos).map(producto => {
        const datos = datosFinanzas.productos[producto].rotacion.resumen;
        return {
            producto: producto,
            stockInicial: datos.stockInicial,
            stockFinal: datos.stockFinal,
            ventas: datos.ventasTotal,
            rotacion: datos.rotacion.toFixed(1),
            diasRotacion: datos.diasRotacion.toFixed(1)
        };
    })).draw();

    // Actualizar tabla de proyecciones
    tablas.proyecciones.clear().rows.add(Object.keys(datosFinanzas.productos).map(producto => {
        const datos = datosFinanzas.productos[producto].resumen;
        return {
            producto: producto,
            ventasActuales: datos.ventasActuales,
            proyeccion3m: datos.proyecciones.tresMeses,
            proyeccion6m: datos.proyecciones.seisMeses,
            proyeccion12m: datos.proyecciones.docesMeses,
            tendencia: 'up' // Esto debería calcularse basado en los datos
        };
    })).draw();

    // Actualizar tabla de precisión
    tablas.precision.clear().rows.add(Object.keys(datosFinanzas.productos).map(producto => {
        const datos = datosFinanzas.productos[producto].resumen.precision;
        return {
            producto: producto,
            precisionPromedio: datos.promedio,
            mejorPrecision: datos.mejor,
            peorPrecision: datos.peor,
            tendencia: 'up' // Esto debería calcularse basado en los datos
        };
    })).draw();

    // Actualizar tabla de rentabilidad
    tablas.rentabilidad.clear().rows.add(Object.keys(datosFinanzas.productos).map(producto => {
        const datos = datosFinanzas.productos[producto].rentabilidad.resumen;
        return {
            producto: producto,
            costoTotal: datos.costoTotal,
            ingresoTotal: datos.ingresoTotal,
            margenBruto: datos.margenBruto,
            rentabilidad: datos.rentabilidad,
            roi: datos.roi
        };
    })).draw();
}

// Función para inicializar los selectores de mes
function inicializarSelectoresMes() {
    // Obtener mes actual
    const fecha = new Date();
    const mesActual = fecha.getMonth() + 1; // getMonth() devuelve 0-11
    const añoActual = fecha.getFullYear();

    // Establecer valores por defecto
    $('#mes-inicio').val(mesActual > 1 ? mesActual - 1 : 12);
    $('#año-inicio').val(mesActual > 1 ? añoActual : añoActual - 1);
    $('#mes-fin').val(mesActual);
    $('#año-fin').val(añoActual);
    
    $('#mes-inventario').val(mesActual);
    $('#año-inventario').val(añoActual);
    
    $('#mes-rentabilidad').val(mesActual);
    $('#año-rentabilidad').val(añoActual);

    // Eventos de cambio
    $('.form-select[id^="mes-"], .form-select[id^="año-"]').on('change', function() {
        const seccion = this.id.split('-')[1]; // inicio, fin, inventario, rentabilidad
        validarPeriodo(seccion);
        actualizarDatos();
    });
}

// Función para validar que el período seleccionado sea válido
function validarPeriodo(seccion) {
    if (seccion === 'inicio' || seccion === 'fin') {
        const mesInicio = parseInt($('#mes-inicio').val());
        const añoInicio = parseInt($('#año-inicio').val());
        const mesFin = parseInt($('#mes-fin').val());
        const añoFin = parseInt($('#año-fin').val());

        // Convertir a fecha para comparar
        const fechaInicio = new Date(añoInicio, mesInicio - 1);
        const fechaFin = new Date(añoFin, mesFin - 1);

        if (fechaFin < fechaInicio) {
            // Si el período no es válido, ajustar fecha fin
            $('#mes-fin').val(mesInicio);
            $('#año-fin').val(añoInicio);
        }
    }
}

// Función para obtener el período seleccionado
function obtenerPeriodo(seccion = '') {
    if (seccion === 'inventario') {
        return {
            mes: parseInt($('#mes-inventario').val()),
            año: parseInt($('#año-inventario').val())
        };
    } else if (seccion === 'rentabilidad') {
        return {
            mes: parseInt($('#mes-rentabilidad').val()),
            año: parseInt($('#año-rentabilidad').val())
        };
    } else {
        return {
            inicio: {
                mes: parseInt($('#mes-inicio').val()),
                año: parseInt($('#año-inicio').val())
            },
            fin: {
                mes: parseInt($('#mes-fin').val()),
                año: parseInt($('#año-fin').val())
            }
        };
    }
}

function actualizarTablaPresupuestos() {
    if (!tablas.presupuestos) return;

    const datos = [];
    Object.entries(datosFinanzas.presupuestos.datos_mensuales.por_categoria).forEach(([categoria, valores]) => {
        const presupuestoMensual = valores.presupuesto[new Date().getMonth()];
        const gastoRealMensual = valores.gasto_real[new Date().getMonth()];
        const diferencia = presupuestoMensual - gastoRealMensual;
        const porcentajeUtilizado = (gastoRealMensual / presupuestoMensual) * 100;

        datos.push({
            categoria: categoria,
            presupuesto: presupuestoMensual,
            gastoReal: gastoRealMensual,
            diferencia: diferencia,
            porcentajeUtilizado: porcentajeUtilizado,
            estado: diferencia >= 0 ? 'success' : 'danger'
        });
    });

    tablas.presupuestos.clear().rows.add(datos).draw();
    actualizarResumenPresupuestos();
}

function actualizarResumenPresupuestos() {
    const resumen = datosFinanzas.presupuestos.resumen_actual;
    
    $('#presupuesto-total').text(formatearNumero(resumen.presupuesto_total));
    $('#gasto-real-total').text(formatearNumero(resumen.gasto_real_total));
    $('#diferencia-total').text(formatearNumero(resumen.diferencia));
}

// Inicializar tabla de presupuestos
function inicializarTablaPresupuestos() {
    tablas.presupuestos = $('#tabla-presupuestos').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        columns: [
            { data: 'categoria' },
            { 
                data: 'presupuesto',
                className: 'text-end',
                render: (data) => formatearNumero(data)
            },
            { 
                data: 'gastoReal',
                className: 'text-end',
                render: (data) => formatearNumero(data)
            },
            { 
                data: 'diferencia',
                className: 'text-end',
                render: (data) => formatearNumero(data)
            },
            { 
                data: 'porcentajeUtilizado',
                className: 'text-end',
                render: (data) => data.toFixed(2) + '%'
            },
            { 
                data: 'estado',
                className: 'text-center',
                render: (data) => `<span class="badge bg-${data}">
                    ${data === 'success' ? 'Dentro' : 'Excedido'}</span>`
            }
        ]
    });
}

function poblarCategoriasGasto() {
    const $select = $('#categoria-gasto');
    $select.empty(); // Limpiar opciones existentes
    
    // Agregar opción por defecto
    $select.append(`<option value="" disabled>Seleccione una categoría</option>`);
    
    // Agregar categorías desde los datos
    datosFinanzas.presupuestos.categorias.forEach(categoria => {
        $select.append(`<option value="${categoria.id}">${categoria.nombre}</option>`);
    });
    
    // Seleccionar la primera categoría por defecto
    $select.val(datosFinanzas.presupuestos.categorias[0].id);
}

function inicializarEventosModales() {
    // Cleanup any existing event listeners
    $('.modal').off('hidden.bs.modal');
    
    // Add global modal cleanup
    $('.modal').on('hidden.bs.modal', function() {
        const modalId = $(this).attr('id');
        if (graficos[modalId]) {
            graficos[modalId].destroy();
            graficos[modalId] = null;
        }
    });

    // Ensure canvas containers have proper height
    $('.modal canvas').each(function() {
        const container = $(this).parent();
        if (container) {
            container.css('height', '400px');
        }
    });
}

function actualizarTipoAnalisis() {
    const tipoAnalisis = $('#ver-historico').val();
    const $periodoSelect = $('#periodo-proyeccion');
    
    // Update UI elements based on analysis type
    if (tipoAnalisis === 'proyecciones') {
        $('.historico-elements').hide();
        $('.proyeccion-elements').show();
        // Enable period selector and set projection text
        $periodoSelect
            .prop('disabled', false)
            .html(`
                <option value="3">Próximos 3 meses</option>
                <option value="6">Próximos 6 meses</option>
                <option value="12">Próximo año</option>
            `)
            .val('6'); // Default to 6 months
    } else {
        $('.historico-elements').show();
        $('.proyeccion-elements').hide();
        // Set period to 6 months, disable, and set historical text
        $periodoSelect
            .html(`
                <option value="3">Últimos 3 meses</option>
                <option value="6">Últimos 6 meses</option>
                <option value="12">Último año</option>
            `)
            .val('6')
            .prop('disabled', true);
    }
    
    // Update main chart
    actualizarGraficoProyecciones(tipoAnalisis);
    
    // Update any open modals
    if (graficos.modalProyeccion) {
        const producto = $('#modal-proyeccion').data('producto');
        if (producto) {
            mostrarModalProyeccion(producto);
        }
    }
}

function inicializarEventosTablas() {
    console.log('Initializing table events');
    
    // Remove any existing click handlers
    $('#tabla-proyecciones-futuras tbody, #tabla-precision-historica tbody').off('click', 'tr');
    
    // Add click handler based on current analysis type
    const tipoAnalisis = $('#ver-historico').val();
    console.log('Current analysis type:', tipoAnalisis);
    
    // Function to handle row clicks
    const handleRowClick = function() {
        console.log('Row clicked');
        const producto = $(this).data('producto');
        console.log('Product:', producto);
        
        if (tipoAnalisis === 'proyecciones') {
            console.log('Opening projections modal');
            mostrarModalProyeccion(producto);
        } else {
            console.log('Opening precision modal');
            mostrarModalPrecision(producto);
        }
    };
    
    // Bind click handler to both tables
    $('#tabla-proyecciones-futuras tbody, #tabla-precision-historica tbody').on('click', 'tr', handleRowClick);
}

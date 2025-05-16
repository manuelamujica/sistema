const graficos = {
    proyecciones: null,
    presupuestos: null
};

const tablas = {
    proyecciones: null,
    precision: null,
    presupuestos: null
};

function formatearMoneda(value) {
    if (typeof value === 'string') {
        value = value.replace('USD', '').trim();
        value = value.replace(/,/g, '');
    }
    
    const valorNumerico = parseFloat(value);
    
    
    if (isNaN(valorNumerico)) {
        console.warn('Valor numérico inválido:', value);
        return 'USD 0.00';
    }
    
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(valorNumerico);
}


function formatearPorcentaje(valor) {
    if (typeof valor !== 'number') {
        valor = parseFloat(valor);
    }
    
    if (isNaN(valor)) {
        return '0.00%';
    }
    
    return new Intl.NumberFormat('es-VE', {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(valor / 100);
}


const configComun = {
    responsive: true,
    autoWidth: false,
    language: {
        url: 'vista/plugins/datatables/Spanish.json'
    }
};


function inicializarTabla(idTabla, config) {

    const $tabla = $(idTabla);
    if (!$.fn.DataTable.isDataTable($tabla)) {
        return $tabla.DataTable(config);
    }
    return $tabla.DataTable();
}

function actualizarDatos() {
    console.log('Actualizando datos con período:', obtenerPeriodo());
    actualizarProyecciones(obtenerPeriodo());
}


function actualizarTipoAnalisis() {
    const tipoAnalisis = $('#ver-historico').val();
    console.log('Analysis type changed to:', tipoAnalisis);
    
    const $periodoSelect = $('#periodo-proyeccion');
    const $tablaProyecciones = $('#tabla-proyecciones');
    const $tablaPrecision = $('#tabla-precision');
    
    if (tipoAnalisis === 'proyecciones') {
        $tablaProyecciones.show();
        $tablaPrecision.hide();
        $periodoSelect.prop('disabled', false);
        $periodoSelect.html(`
            <option value="3">Próximos 3 meses</option>
            <option value="6" selected>Próximos 6 meses</option>
            <option value="12">Próximo año</option>
        `);

        $('#tabla-proyecciones-futuras thead tr').html(`
            <th>Producto</th>
            <th class="text-end">Ventas Actuales</th>
            <th class="text-end">Proyección 3 Meses</th>
            <th class="text-end">Proyección 6 Meses</th>
            <th class="text-end">Proyección 12 Meses</th>
            <th class="text-center">Tendencia</th>
        `);

        $('#tabla-proyecciones .card-body h3').text('Proyecciones Futuras');
    } else {
        $tablaProyecciones.show();
        $tablaPrecision.hide();
        $periodoSelect.prop('disabled', true);
        $periodoSelect.html(`
            <option value="6" selected>Últimos 6 meses</option>
        `);

        $('#tabla-proyecciones-futuras thead tr').html(`
            <th>Producto</th>
            <th class="text-end">Precisión Promedio</th>
            <th class="text-end">Mejor Precisión</th>
            <th class="text-end">Peor Precisión</th>
            <th class="text-center">Detalles</th>
        `);

        $('#tabla-proyecciones .card-body h3').text('Precisión Histórica de Proyecciones');
    }

    if (graficos.proyecciones) {
        UtilidadesGraficos.destruirGrafico(graficos.proyecciones);
    }
    inicializarGraficos();
}

function mostrarModalProyeccion(producto, cod_producto) {
    if (!cod_producto) {
        console.error('No se proporcionó el código del producto');
        return;
    }

    cargarDetalleProducto(cod_producto)
        .done(function(response) {
            const idModal = '#modal-proyeccion';
            const tipoAnalisis = $('#ver-historico').val();

            UtilidadesGraficos.utilidadesModal.actualizarTituloModal(idModal,
                `${tipoAnalisis === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica'} - ${producto}`
            );
    

            $(idModal).data('producto', producto);
            $(idModal).data('cod-producto', cod_producto);
    

            if (graficos.modalProyeccion) {
                UtilidadesGraficos.destruirGrafico(graficos.modalProyeccion);
            }
            

            const ctx = document.getElementById('grafico-proyeccion');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            graficos.modalProyeccion = UtilidadesGraficos.inicializarGraficoModal(ctx, 'modal-proyeccion', response, producto);

            $(idModal).modal('show');
        })
        .fail(function(xhr, status, error) {
            console.error('Error al obtener detalles del producto:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al cargar los detalles del producto'
            });
        });
}


function inicializarGraficos() {

    const ctxProyecciones = document.getElementById('proyeccionesChart');
    if (ctxProyecciones && window.datosFinanzas.datos_grafico_proyecciones) {
        if (graficos.proyecciones) {
            UtilidadesGraficos.destruirGrafico(graficos.proyecciones);
        }
        
        const tipoAnalisis = $('#ver-historico').val();
        const periodoSeleccionado = parseInt($('#periodo-proyeccion').val()) || 6;
        

        const historico = window.datosFinanzas.datos_grafico_proyecciones.historico;
        const proyecciones = window.datosFinanzas.datos_grafico_proyecciones.proyecciones;

        if (tipoAnalisis === 'precision') {

            graficos.proyecciones = UtilidadesGraficos.inicializarGraficoProyecciones(
                ctxProyecciones,
                historico,
                { labels: [], valores: [] }
            );
        } else {

            const proyeccionesFiltradas = {
                labels: proyecciones.labels.slice(0, periodoSeleccionado),
                valores: proyecciones.valores.slice(0, periodoSeleccionado)
            };
            
            graficos.proyecciones = UtilidadesGraficos.inicializarGraficoProyecciones(
                ctxProyecciones,
                historico,
                proyeccionesFiltradas
            );
        }
    }

    const ctxPresupuestos = document.getElementById('grafico-presupuestos');
    console.log('Canvas Presupuestos:', ctxPresupuestos);
    console.log('Datos Presupuestos:', window.datosFinanzas.datos_presupuestos);

    if (ctxPresupuestos && window.datosFinanzas.datos_presupuestos) {
        if (graficos.presupuestos) {
            console.log('Destruyendo gráfico de presupuestos existente');
            UtilidadesGraficos.destruirGrafico(graficos.presupuestos);
        }

        const datos = {
            labels: window.datosFinanzas.datos_presupuestos.labels || [],
            presupuesto: window.datosFinanzas.datos_presupuestos.presupuesto || [],
            gasto_real: window.datosFinanzas.datos_presupuestos.gasto_real || []
        };

        console.log('Datos formateados para gráfico de presupuestos:', datos);

        graficos.presupuestos = UtilidadesGraficos.inicializarGraficoPresupuesto(
            ctxPresupuestos,
            datos,
            'Presupuesto General'
        );
    } else {
        console.warn('No se pudo inicializar el gráfico de presupuestos:', {
            canvasExiste: !!ctxPresupuestos,
            datosExisten: !!window.datosFinanzas.datos_presupuestos
        });
    }
}

function actualizarGraficoPresupuestos(datos) {
    const ctx = document.getElementById('grafico-presupuestos');
    if (!ctx) return;

    if (graficos.presupuestos) {
        UtilidadesGraficos.destruirGrafico(graficos.presupuestos);
    }

    graficos.presupuestos = UtilidadesGraficos.inicializarGraficoPresupuesto(
        ctx,
        datos,
        'Presupuesto General'
    );
}

const TableUtils = {
    createMoneyColumn: function(field, className = 'text-end') {
        return {
            data: field,
            className: className,
            defaultContent: '',
            render: function(data, type) {
                if (type === 'display') {
                    return formatearMoneda(data || 0);
                }
                return data || 0;
            }
        };
    },

    createPercentageColumn: function(field, className = 'text-end') {
        return {
            data: field,
            className: className,
            defaultContent: '',
            render: function(data, type) {
                if (type === 'display') {
                    return formatearPorcentaje(data || 0);
                }
                return data || 0;
            }
        };
    },

    createTextColumn: function(field, className = '') {
        return {
            data: field,
            className: className,
            defaultContent: '',
            render: function(data, type) {
                if (type === 'display') {
                    return data || '';
                }
                return data || '';
            }
        };
    },

    createActionButtonColumn: function(field, icon, buttonClass, buttonText = '') {
        return {
            data: field,
            className: 'text-center',
            defaultContent: '',
            render: function(data, type) {
                if (type === 'display' && data) {
                    return `<button class="btn btn-sm ${buttonClass}" data-id="${data}">
                        <i class="fas ${icon}"></i> ${buttonText}
                    </button>`;
                }
                return data || '';
            }
        };
    },

    updateTable: function(table, data, tableName = 'tabla') {
        if (!table) return;
        
        try {
            console.group(`Actualización ${tableName}`);
            console.log('Actualizando con:', data);
            table.clear();
            table.rows.add(data).draw();
            console.log(`${tableName} actualizada con éxito`);
            console.groupEnd();
        } catch (error) {
            console.error(`Error al actualizar ${tableName}:`, error);
            console.groupEnd();
        }
    },

    initializeTable: function(selector, config, data, tableName = 'tabla') {
        try {
            console.group(`Inicialización ${tableName}`);
            console.log('Datos a cargar:', data);

            const table = inicializarTabla(selector, {
                ...configComun,
                data: data,
                ...config
            });

            console.log(`${tableName} inicializada correctamente`);
            console.groupEnd();
            return table;
        } catch (error) {
            console.error(`Error al inicializar ${tableName}:`, error);
            console.groupEnd();
            return null;
        }
    }
};

function validarDatosPresupuestos(datos) {
    console.group('Validación de Datos Presupuestos');
    
    if (!Array.isArray(datos)) {
        console.error('Los datos de presupuestos no son un array:', datos);
        console.groupEnd();
        return false;
    }

    if (datos.length === 0) {
        console.warn('Array de presupuestos está vacío');
        console.groupEnd();
        return true;
    }

    const camposRequeridos = [
        'cod_cat_gasto',
        'categoria',
        'presupuesto',
        'gasto_real',
        'diferencia',
        'porcentaje_utilizado',
        'estado'
    ];
    
    const primerRegistro = datos[0];
    console.log('Estructura del primer registro:', primerRegistro);
    
    const camposFaltantes = camposRequeridos.filter(campo => !(campo in primerRegistro));
    if (camposFaltantes.length > 0) {
        console.error('Campos faltantes en los datos:', camposFaltantes);
        console.groupEnd();
        return false;
    }

    console.log('Validación exitosa');
    console.groupEnd();
    return true;
}

function inicializarTablas() {
    console.group('Inicialización de Tablas');
    
    if (!window.datosFinanzas?.presupuestos) {
        console.warn('No hay datos de presupuestos disponibles');
        window.datosFinanzas = window.datosFinanzas || {};
        window.datosFinanzas.presupuestos = [];
    }

    if (!validarDatosPresupuestos(window.datosFinanzas.presupuestos)) {
        console.error('Error en la estructura de datos de presupuestos');
        return;
    }

    tablas.presupuestos = TableUtils.initializeTable('#tabla-presupuestos', {
        data: window.datosFinanzas.presupuestos,
        columns: [
            TableUtils.createTextColumn('categoria', ''),
            TableUtils.createMoneyColumn('presupuesto', 'text-end'),
            TableUtils.createMoneyColumn('gasto_real', 'text-end'),
            {
                ...TableUtils.createMoneyColumn('diferencia', 'text-end'),
                render: function(data, type) {
                    if (type === 'display') {
                        const clase = parseFloat(data || 0) >= 0 ? 'text-success' : 'text-danger';
                        return `<span class="${clase}">${formatearMoneda(data || 0)}</span>`;
                    }
                    return data || 0;
                }
            },
            TableUtils.createPercentageColumn('porcentaje_utilizado', 'text-end'),
            {
                ...TableUtils.createTextColumn('estado', 'text-center'),
                defaultContent: 'success',
                render: function(data, type) {
                    if (type === 'display') {
                        const estado = data || 'success';
                        return `<span class="badge bg-${estado}">${estado === 'success' ? 'Dentro del Presupuesto' : 'Excedido'}</span>`;
                    }
                    return data || 'success';
                }
            },
            TableUtils.createActionButtonColumn('cod_cat_gasto', 'fa-chart-line', 'btn-info ver-detalle-presupuesto')
        ]
    }, [], 'tabla presupuestos');


    tablas.proyecciones = TableUtils.initializeTable('#tabla-proyecciones-futuras', {
        columns: [
            TableUtils.createTextColumn('producto'),
            { 
                data: function(row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        row.ventas_actuales : 
                        row.precision_promedio;
                },
                className: 'text-end',
                render: function(data, type, row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        formatearMoneda(data) :
                        formatearPorcentaje(data);
                }
            },
            { 
                data: function(row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        row.proyeccion_3m : 
                        row.mejor_precision;
                },
                className: 'text-end',
                render: function(data, type, row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        formatearMoneda(data || 0) :
                        formatearPorcentaje(data);
                }
            },
            { 
                data: function(row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        row.proyeccion_6m : 
                        row.peor_precision;
                },
                className: 'text-end',
                render: function(data, type, row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 
                        formatearMoneda(data || 0) :
                        formatearPorcentaje(data);
                }
            },
            { 
                data: function(row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    if (tipoAnalisis === 'proyecciones') {
                        return row.proyeccion_12m;
                    }
                    return row.cod_producto;
                },
                className: function(data, type, row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    return tipoAnalisis === 'proyecciones' ? 'text-end' : 'text-center';
                },
                render: function(data, type, row) {
                    const tipoAnalisis = $('#ver-historico').val();
                    if (tipoAnalisis === 'proyecciones') {
                        return formatearMoneda(data || 0);
                    }
                    return '<button class="btn btn-sm btn-info ver-precision" data-cod-producto="' + data + '">' +
                           '<i class="fas fa-chart-line"></i></button>';
                }
            },
            { 
                data: function(row) {
                    return $('#ver-historico').val() === 'proyecciones' ? row.tendencia : null;
                },
                className: 'text-center',
                render: function(data, type, row) {
                    if (type === 'display' && data) {
                        return '<i class="fas fa-arrow-' + (data === 'up' ? 'up' : 'down') + 
                               ' text-' + (data === 'up' ? 'success' : 'danger') + '"></i>';
                    }
                    return '';
                },
                visible: function() {
                    return $('#ver-historico').val() === 'proyecciones';
                }
            }
        ]
    }, [], 'tabla proyecciones');


    tablas.precision = TableUtils.initializeTable('#tabla-precision-historica', {
        columns: [
            TableUtils.createTextColumn('producto'),
            TableUtils.createMoneyColumn('valor_proyectado'),
            TableUtils.createMoneyColumn('valor_real'),
            {
                ...TableUtils.createPercentageColumn('precision_valor'),
                render: function(data, type) {
                    if (type === 'display') {
                        return data ? formatearPorcentaje(data) : 'N/A';
                    }
                    return data;
                }
            },
            TableUtils.createActionButtonColumn('cod_producto', 'fa-percentage', 'btn-info ver-precision')
        ]
    }, [], 'tabla precision');

    console.groupEnd();
}

function actualizarTablas() {
    if (!window.datosFinanzas) {
        console.warn('No hay datos disponibles para las tablas');
        return;
    }


    if (tablas.presupuestos && window.datosFinanzas.presupuestos) {
        try {
            console.log('Actualizando tabla presupuestos con:', window.datosFinanzas.presupuestos);
            tablas.presupuestos.clear();
            tablas.presupuestos.rows.add(window.datosFinanzas.presupuestos).draw();
            console.log('Tabla de presupuestos actualizada con éxito');
        } catch (error) {
            console.error('Error al actualizar tabla de presupuestos:', error);
        }
    }

    const tipoAnalisis = $('#ver-historico').val();
    

    if (tablas.proyecciones) {
        const datos = tipoAnalisis === 'proyecciones' ? 
            window.datosFinanzas.proyecciones : 
            window.datosFinanzas.proyecciones_historicas;
            
        if (datos) {
            tablas.proyecciones.clear();
            tablas.proyecciones.rows.add(datos).draw();
        }
    }


    if (window.datosFinanzas.precision && tablas.precision) {
        tablas.precision.clear();
        tablas.precision.rows.add(window.datosFinanzas.precision).draw();
    }
}

function obtenerPeriodo() {
    return $('#periodo-proyeccion').val() || '6';
}

function mostrarModalPrecision(producto) {
    const datos = window.datosFinanzas.productos[producto];
    if (!datos) {
        console.error('No se encontraron datos para el producto:', producto);
        return;
    }

    const modalId = '#modal-precision';
    
    ChartUtils.modalUtils.updateModalTitle(modalId, `Precisión Histórica - ${producto}`);

    graficos.modalPrecision = ChartUtils.destroyChart(graficos.modalPrecision);

    const historico = datos.proyeccion;
    const precision = datos.resumen.precision;

    ChartUtils.modalUtils.setupModal(modalId, 
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

        function() {
            graficos.modalPrecision = ChartUtils.destroyChart(graficos.modalPrecision);
        }
    );

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
                                                <td class="text-end">${formatearMoneda(proyectado)}</td>
                                                <td class="text-end">${formatearMoneda(real)}</td>
                                                <td class="text-end ${diff >= 0 ? 'text-success' : 'text-danger'}">
                                                    ${diff >= 0 ? '+' : ''}${formatearMoneda(diff)}
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
    $(modalId).modal('show');
}

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'USD'
    }).format(numero);
}

const EventUtils = {
    initializeTabEvents: function() {
        $('#pestañas button[data-toggle="tab"]').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#pestañas button[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            const targetTab = $(e.target).attr('data-target');
            if (targetTab === '#presupuestos' && graficos.presupuestos) {
                graficos.presupuestos.resize();
            } else if (targetTab === '#proyecciones' && graficos.proyecciones) {
                graficos.proyecciones.resize();
            }
        });
    },

    initializeModalEvents: function() {
        $('.modal').off('hidden.bs.modal');
        
        $('.modal').on('hidden.bs.modal', function() {
            const idModal = $(this).attr('id');
            if (graficos[idModal]) {
                UtilidadesGraficos.destruirGrafico(graficos[idModal]);
                graficos[idModal] = null;
            }
        });

        $('.modal canvas').each(function() {
            const contenedor = $(this).parent();
            if (contenedor) {
                contenedor.css('height', '400px');
            }
        });
    },

    initializeTableEvents: function() {
        $('#tabla-proyecciones-futuras tbody, #tabla-precision-historica tbody').on('click', 'tr', function() {
            const tabla = $(this).closest('table').attr('id');
            const data = tabla === 'tabla-proyecciones-futuras' ? 
                tablas.proyecciones.row(this).data() : 
                tablas.precision.row(this).data();
                
            if (data) {
                const tipoAnalisis = $('#ver-historico').val();
                if (tipoAnalisis === 'proyecciones') {
                    mostrarModalProyeccion(data.producto, data.cod_producto);
                } else {
                    mostrarModalPrecision(data.producto, data.cod_producto);
                }
            }
        });
    },

    initializeSelectEvents: function() {

        $('#ver-historico').on('change', function() {
            actualizarTipoAnalisis();
            actualizarTablaProyecciones();
        });


        $('#periodo-proyeccion').on('change', function() {
            const tipoAnalisis = $('#ver-historico').val();
            if (tipoAnalisis === 'proyecciones') {
                if (graficos.proyecciones) {
                    UtilidadesGraficos.destruirGrafico(graficos.proyecciones);
                }
                inicializarGraficos();
                actualizarTablaProyecciones();
            }
        });

        $('.form-select[id^="mes-"], .form-select[id^="año-"]').on('change', function() {
            const seccion = this.id.split('-')[1];
            validarPeriodo(seccion);
            actualizarDatos();
        });
    }
};

const DateUtils = {
    initializeMonthSelectors: function() {
        const fecha = new Date();
        const mesActual = fecha.getMonth() + 1;
        const añoActual = fecha.getFullYear();

        $('#mes-inicio').val(mesActual > 1 ? mesActual - 1 : 12);
        $('#año-inicio').val(mesActual > 1 ? añoActual : añoActual - 1);
        $('#mes-fin').val(mesActual);
        $('#año-fin').val(añoActual);
        
        $('#mes-inventario').val(mesActual);
        $('#año-inventario').val(añoActual);
        
        $('#mes-rentabilidad').val(mesActual);
        $('#año-rentabilidad').val(añoActual);
    },

    validatePeriod: function(seccion) {
        if (seccion === 'inicio' || seccion === 'fin') {
            const mesInicio = parseInt($('#mes-inicio').val());
            const añoInicio = parseInt($('#año-inicio').val());
            const mesFin = parseInt($('#mes-fin').val());
            const añoFin = parseInt($('#año-fin').val());
            
            const fechaInicio = new Date(añoInicio, mesInicio - 1);
            const fechaFin = new Date(añoFin, mesFin - 1);

            if (fechaFin < fechaInicio) {
                $('#mes-fin').val(mesInicio);
                $('#año-fin').val(añoInicio);
            }
        }
    }
};

function actualizarProyecciones(periodo) {
    if (!window.datosFinanzas?.datos_grafico_proyecciones) {
        console.warn('No hay datos de proyecciones disponibles');
        return;
    }
    

    const historico = window.datosFinanzas.datos_grafico_proyecciones.historico;
    const proyecciones = window.datosFinanzas.datos_grafico_proyecciones.proyecciones;

    const proyeccionesFiltradas = {
        labels: proyecciones.labels.slice(0, periodo),
        valores: proyecciones.valores.slice(0, periodo)
    };
    

    actualizarGraficos(historico, proyeccionesFiltradas);
    actualizarTablas();
}


function inicializarFormularioPresupuesto() {

    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    $('#mes-presupuesto').val(primerDiaMes.toISOString().split('T')[0]);
    
    $('#form-registro-presupuesto').on('submit', function(e) {
        e.preventDefault();
        

        const formData = {
            cod_cat_gasto: $('#categoria-presupuesto').val(),
            mes: $('#mes-presupuesto').val(),
            monto: parseFloat($('#monto-presupuesto').val()),
            descripcion: $('#descripcion-presupuesto').val()
        };
        

        if (!formData.cod_cat_gasto || !formData.mes || !formData.monto) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor complete todos los campos requeridos'
            });
            return;
        }


        if (isNaN(formData.monto) || formData.monto <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El monto debe ser un número positivo'
            });
            return;
        }
        

        $.ajax({
            url: 'controlador/finanzas.php',
            method: 'POST',
            data: {
                accion: 'registrar_presupuesto',
                ...formData
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Presupuesto registrado correctamente'
                    });
                    $('#modal-registro-presupuesto').modal('hide');
                    actualizarTablaPresupuestos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al registrar el presupuesto'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de comunicación con el servidor'
                });
            }
        });
    });
}

function inicializarSelectoresCategorias() {
    if (!window.datosFinanzas?.categorias_gasto) {
        console.warn('No hay categorías de gasto disponibles');
        return;
    }

    const categorias = window.datosFinanzas.categorias_gasto;
    const $selectCategoria = $('#categoria-presupuesto');
    const $selectCategoriaFiltro = $('#categoria-gasto');


    $selectCategoria.find('option:not([value=""])').remove();
    $selectCategoriaFiltro.find('option:not([value=""])').remove();


    categorias.forEach(categoria => {

        const option = new Option(categoria.nombre, categoria.cod_cat_gasto);
        $selectCategoria.append(option);
        $selectCategoriaFiltro.append(option.cloneNode(true));
    });
}

function actualizarTablaPresupuestos() {
    if (!window.datosFinanzas?.presupuestos) {
        console.warn('No hay datos de presupuestos disponibles');
        return;
    }

    if (!validarDatosPresupuestos(window.datosFinanzas.presupuestos)) {
        console.error('Error en la estructura de datos de presupuestos');
        return;
    }

    TableUtils.updateTable(tablas.presupuestos, window.datosFinanzas.presupuestos, 'tabla presupuestos');
}

function actualizarTablaProyecciones() {
    const tipoAnalisis = $('#ver-historico').val();
    
    if (tablas.proyecciones) {
        const datos = tipoAnalisis === 'proyecciones' ? 
            window.datosFinanzas.proyecciones : 
            window.datosFinanzas.proyecciones_historicas;
            
        if (datos) {
            TableUtils.updateTable(tablas.proyecciones, datos, 'tabla proyecciones');
        }
    }

    if (window.datosFinanzas.precision && tablas.precision) {
        TableUtils.updateTable(tablas.precision, window.datosFinanzas.precision, 'tabla precision');
    }
}

$(document).ready(function() {
    console.debug('incializando finanzas');
    

    actualizarTipoAnalisis();
    

    EventUtils.initializeTabEvents();
    EventUtils.initializeModalEvents();
    EventUtils.initializeTableEvents();
    EventUtils.initializeSelectEvents();
    
    DateUtils.initializeMonthSelectors();
    inicializarSelectoresCategorias();

    if (window.datosFinanzas) {
        inicializarTablas();
        inicializarGraficos();
        actualizarTablaPresupuestos();
        actualizarTablaProyecciones();
    } else {
        console.warn('No se encontraron datos iniciales');
    }

    $('#cuentas-tab').tab('show');
});

window.tablas = tablas;

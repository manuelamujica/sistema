const graficos = {};

const tablas = {
    proyecciones: null,
    precision: null
};

let datosFinanzas = {};

function formatearMoneda(value) {
    if (typeof value === 'string') {
        value = value.replace('USD', '').trim();
        value = value.replace(/,/g, '');
    }
    
    const valorNumerico = parseFloat(value);
    
    console.log('formatear valor:', value, 'Tipo:', typeof value, 'Parseado:', valorNumerico);
    
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

// Función para formatear porcentaje
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

// Configuración común para las tablas
const configComun = {
    responsive: true,
    autoWidth: false,
    language: {
        url: 'vista/plugins/datatables/Spanish.json'
    }
};

// Función para inicializar una tabla si no está ya inicializada
function inicializarTabla(idTabla, config) {
    //inicializa si no está ya inicializada
    const $tabla = $(idTabla);
    if (!$.fn.DataTable.isDataTable($tabla)) {
        return $tabla.DataTable(config);
    }
    return $tabla.DataTable();
}

// Funciones para cargar datos del servidor
function cargarProyecciones(periodo) {
    console.log('Solicitando proyecciones para período:', periodo);
    return $.ajax({
        url: 'controlador/proyecciones.php',
        method: 'POST',
        data: {
            accion: 'obtener_proyecciones',
            periodo: periodo
        },
        dataType: 'json'
    }).done(function(response) {
        console.log('Proyecciones recibidas:', response);
        datosFinanzas.proyecciones = response;
        actualizarTablas();
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar proyecciones:', error);
        console.error('Status:', status);
        console.error('Response:', xhr.responseText);
    });
}

function cargarPrecision() {
    console.log('Solicitando datos de precisión');
    return $.ajax({
        url: 'controlador/proyecciones.php',
        method: 'POST',
        data: {
            accion: 'obtener_precision'
        },
        dataType: 'json'
    }).done(function(response) {
        console.log('Precisión recibida:', response);
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar precisión:', error);
        console.error('Status:', status);
        console.error('Response:', xhr.responseText);
    });
}

function cargarDetalleProducto(cod_producto) {
    console.log('Solicitando detalles para producto:', cod_producto);
    return $.ajax({
        url: 'controlador/proyecciones.php',
        method: 'POST',
        data: {
            accion: 'obtener_detalle_producto',
            cod_producto: cod_producto
        },
        dataType: 'json'
    }).done(function(response) {
        console.log('Detalles de producto recibidos:', response);
    }).fail(function(xhr, status, error) {
        console.error('Error al cargar detalles del producto:', error);
        console.error('Status:', status);
        console.error('Response:', xhr.responseText);
    });
}

// Función para actualizar datos
function actualizarDatos() {
    console.log('Actualizando datos con período:', obtenerPeriodo());
    cargarProyecciones(obtenerPeriodo());
}

// Función para actualizar el tipo de análisis
function actualizarTipoAnalisis() {
    const tipoAnalisis = $('#ver-historico').val();
    console.log('Analysis type changed to:', tipoAnalisis);
    
    if (tipoAnalisis === 'proyecciones') {
        $('#tabla-proyecciones').show();
        $('#tabla-precision').hide();
    } else {
        $('#tabla-proyecciones').hide();
        $('#tabla-precision').show();
    }
}

// Función para mostrar modal de proyección
function mostrarModalProyeccion(producto, cod_producto) {
    if (!cod_producto) {
        console.error('No se proporcionó el código del producto');
        return;
    }

    cargarDetalleProducto(cod_producto)
        .done(function(response) {
            const idModal = '#modal-proyeccion';
            const tipoAnalisis = $('#ver-historico').val();
    
            // Update modal title
            UtilidadesGraficos.utilidadesModal.actualizarTituloModal(idModal,
                `${tipoAnalisis === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica'} - ${producto}`
            );
    
            // Store product reference on modal
            $(idModal).data('producto', producto);
            $(idModal).data('cod-producto', cod_producto);
    
            // Clean up existing chart
            if (graficos.modalProyeccion) {
                UtilidadesGraficos.destruirGrafico(graficos.modalProyeccion);
            }
            
            // Create new chart with server data
            const ctx = document.getElementById('grafico-proyeccion');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            graficos.modalProyeccion = UtilidadesGraficos.inicializarGraficoModal(ctx, 'modal-proyeccion', response, producto);

            // Show the modal
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

// Función para inicializar los gráficos
function inicializarGraficos() {
    // Initialize main projections chart
    const ctxProyecciones = document.getElementById('proyeccionesChart');
    if (ctxProyecciones && datosFinanzas.historico) {
        if (graficos.proyecciones) {
            UtilidadesGraficos.destruirGrafico(graficos.proyecciones);
        }
        
        graficos.proyecciones = UtilidadesGraficos.inicializarGraficoProyecciones(
            ctxProyecciones,
            datosFinanzas.historico,
            datosFinanzas.proyecciones
        );
    }
}


function inicializarTablas() {
    if (window.datosFinanzas?.proyecciones) {
        console.debug('Inicializando tablas con datos:', {
            count: window.datosFinanzas.proyecciones.length,
            structure: Object.keys(window.datosFinanzas.proyecciones[0] || {})
        });
    }

    //tabla de proyecciones
    tablas.proyecciones = inicializarTabla('#tabla-proyecciones-futuras', {
        ...configComun,
        columns: [
            { data: 'producto' },
            { 
                data: 'ventas_actuales',
                className: 'text-end',
                render: function(data) {
                    return formatearMoneda(data);
                }
            },
            { 
                data: 'proyeccion_3m',
                className: 'text-end',
                render: function(data) {
                    return formatearMoneda(data);
                }
            },
            { 
                data: 'proyeccion_6m',
                className: 'text-end',
                render: function(data) {
                    return formatearMoneda(data);
                }
            },
            { 
                data: 'proyeccion_12m',
                className: 'text-end',
                render: function(data) {
                    return formatearMoneda(data);
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    return '<i class="bi bi-arrow-' + (data === 'up' ? 'up' : 'down') + '-circle-fill text-' + (data === 'up' ? 'success' : 'danger') + '"></i>';
                }
            }
        ]
    });

    //tabla de precision
    tablas.precision = inicializarTabla('#tabla-precision-historica', {
        ...configComun,
        columns: [
            { data: 'producto' },
            { 
                data: 'precision_promedio',
                className: 'text-end',
                render: function(data) {
                    return formatearPorcentaje(data);
                }
            },
            { 
                data: 'mejor_precision',
                className: 'text-end',
                render: function(data) {
                    return formatearPorcentaje(data);
                }
            },
            { 
                data: 'peor_precision',
                className: 'text-end',
                render: function(data) {
                    return formatearPorcentaje(data);
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    return '<i class="bi bi-arrow-' + (data === 'up' ? 'up' : 'down') + '-circle-fill text-' + (data === 'up' ? 'success' : 'danger') + '"></i>';
                }
            }
        ]
    });

    actualizarTablas();
}

function actualizarTablas() {
    if (!window.datosFinanzas) return;

    // Actualizar tabla de proyecciones
    if (window.datosFinanzas.proyecciones && tablas.proyecciones) {
        tablas.proyecciones.clear().rows.add(window.datosFinanzas.proyecciones).draw();
    }

    // Actualizar tabla de precisión
    if (window.datosFinanzas.precision && tablas.precision) {
        tablas.precision.clear().rows.add(window.datosFinanzas.precision).draw();
    }
}

function inicializarSelectoresMes() {
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

    $('.form-select[id^="mes-"], .form-select[id^="año-"]').on('change', function() {
        const seccion = this.id.split('-')[1];
        validarPeriodo(seccion);
        actualizarDatos();
    });
}

function validarPeriodo(seccion) {
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

function obtenerPeriodo() {
    return $('#periodo-proyeccion').val() || '6';
}

function mostrarModalPrecision(producto) {
    const datos = datosFinanzas.productos[producto];
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
        // al ocultar el modal
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

function inicializarEventosModales() {
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
}

function inicializarEventosTablas() {
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
}

$(document).ready(function() {
    console.debug('Initializing finanzas module');
    
    $('#pestañas button[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('#cuentas-tab').tab('show');
    
    if (window.datosFinanzas) {
        inicializarTablas();
        inicializarGraficos();
    }
    
    inicializarSelectoresMes();
    inicializarEventosModales();
    inicializarEventosTablas();

    $('#ver-historico').on('change', function() {
        actualizarTipoAnalisis();
    });
});

window.tablas = tablas;

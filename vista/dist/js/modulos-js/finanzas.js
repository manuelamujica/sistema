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

// Objeto para almacenar los datos
let datosFinanzas = {};

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
            const modalId = '#modal-proyeccion';
            const tipoAnalisis = $('#ver-historico').val();
            
            // Update modal title
            $('#modal-proyeccion-label').text(
                `${tipoAnalisis === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica'} - ${producto}`
            );
            
            // Store product reference on modal
            $(modalId).data('producto', producto);
            $(modalId).data('cod-producto', cod_producto);
            
            // Clean up existing chart
            if (graficos.modalProyeccion) {
                graficos.modalProyeccion.destroy();
            }
            
            // Create new chart with server data
            const ctx = document.getElementById('grafico-proyeccion');
            if (!ctx) {
                console.error('No se encontró el elemento canvas');
                return;
            }

            graficos.modalProyeccion = new Chart(ctx, {
                ...configGraficos,
                data: {
                    labels: response.labels,
                    datasets: [
                        {
                            label: 'Proyectado',
                            data: response.proyectado,
                            borderColor: 'rgb(153, 102, 255)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderDash: [5, 5],
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Real',
                            data: response.real,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                }
            });

            // Show the modal
            $(modalId).modal('show');
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
            graficos.proyecciones.destroy();
        }
        
        graficos.proyecciones = new Chart(ctxProyecciones, {
            type: 'line',
            data: {
                labels: datosFinanzas.historico.labels,
                datasets: [{
                    label: 'Ventas Históricas',
                    data: datosFinanzas.historico.valores,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: configGraficos.options
        });
    }
}

// Función para inicializar todas las tablas
function inicializarTablas() {
    console.log('Initializing tables with data:', window.datosFinanzas);

    // Destroy existing tables if they exist
    Object.values(tablas).forEach(tabla => {
        if (tabla && tabla.destroy) {
            tabla.destroy();
        }
    });

    // Initialize projections table
    if ($.fn.DataTable.isDataTable('#tabla-proyecciones-futuras')) {
        $('#tabla-proyecciones-futuras').DataTable().destroy();
    }
    
    tablas.proyecciones = $('#tabla-proyecciones-futuras').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: window.datosFinanzas?.proyecciones || [],
        columns: [
            { data: 'producto' },
            { 
                data: 'ventasActuales',
                className: 'text-end',
                render: function(data) {
                    return data ? formatearNumero(data) : '$0.00';
                }
            },
            { 
                data: 'proyeccion3m',
                className: 'text-end',
                render: function(data) {
                    return data ? formatearNumero(data) : '$0.00';
                }
            },
            { 
                data: 'proyeccion6m',
                className: 'text-end',
                render: function(data) {
                    return data ? formatearNumero(data) : '$0.00';
                }
            },
            { 
                data: 'proyeccion12m',
                className: 'text-end',
                render: function(data) {
                    return data ? formatearNumero(data) : '$0.00';
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    const direction = data || 'down';
                    return `<i class="bi bi-arrow-${direction}-circle-fill text-${direction === 'up' ? 'success' : 'danger'}"></i>`;
                }
            }
        ]
    });

    // Initialize precision table
    if ($.fn.DataTable.isDataTable('#tabla-precision-historica')) {
        $('#tabla-precision-historica').DataTable().destroy();
    }
    
    tablas.precision = $('#tabla-precision-historica').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            url: 'vista/plugins/datatables/Spanish.json'
        },
        data: window.datosFinanzas?.precision || [],
        columns: [
            { data: 'producto' },
            { 
                data: 'precisionPromedio',
                className: 'text-end',
                render: function(data) {
                    return data ? data.toFixed(2) + '%' : '0.00%';
                }
            },
            { 
                data: 'mejorPrecision',
                className: 'text-end',
                render: function(data) {
                    return data ? data.toFixed(2) + '%' : '0.00%';
                }
            },
            { 
                data: 'peorPrecision',
                className: 'text-end',
                render: function(data) {
                    return data ? data.toFixed(2) + '%' : '0.00%';
                }
            },
            { 
                data: 'tendencia',
                className: 'text-center',
                render: function(data) {
                    const direction = data || 'down';
                    return `<i class="bi bi-arrow-${direction}-circle-fill text-${direction === 'up' ? 'success' : 'danger'}"></i>`;
                }
            }
        ]
    });
}

// Función para actualizar los datos de las tablas
function actualizarTablas() {
    if (!datosFinanzas) return;

    // Actualizar tabla de rotación
    if (datosFinanzas.inventario && datosFinanzas.inventario.productos) {
        tablas.rotacion.clear().rows.add(datosFinanzas.inventario.productos).draw();
    }

    // Actualizar tabla de proyecciones
    if (datosFinanzas.proyecciones && datosFinanzas.proyecciones.productos) {
        tablas.proyecciones.clear().rows.add(datosFinanzas.proyecciones.productos).draw();
    }

    // Actualizar tabla de rentabilidad
    if (datosFinanzas.rentabilidad && datosFinanzas.rentabilidad.productos) {
        tablas.rentabilidad.clear().rows.add(datosFinanzas.rentabilidad.productos).draw();
    }
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

function inicializarEventosTablas() {
    // Handle clicks on projection/precision tables
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

// Initialize everything when document is ready
$(document).ready(function() {
    console.log('Document ready - Initializing finanzas module');
    
    // Initialize tabs
    $('#pestañas button[data-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Ensure first tab is active
    $('#cuentas-tab').tab('show');
    
    // Initialize tables and graphs
    if (window.datosFinanzas) {
        inicializarTablas();
        inicializarGraficos();
    } else {
        console.error('No initial data available');
    }
    
    // Initialize other components
    inicializarSelectoresMes();
    inicializarEventosModales();
    inicializarEventosTablas();

    // Handle analysis type change
    $('#ver-historico').on('change', function() {
        actualizarTipoAnalisis();
    });

    console.log('Finanzas module initialization complete');
});

// Create a namespace for our chart utilities
window.ChartUtils = {
    // Chart configurations
    chartConfigs: {
        common: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y: {
                    beginAtZero: false,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        },
        lineChart: {
            type: 'line',
            options: {
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += formatearNumero(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        }
    },

    // Dataset styles
    datasetStyles: {
        realData: {
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        },
        projectionData: {
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderDash: [5, 5],
            tension: 0.4,
            fill: true
        }
    },

    // Chart management utilities
    destroyChart: function(chart) {
        if (chart) {
            chart.destroy();
            return null;
        }
        return null;
    },
    
    createLineDataset: function(label, data, isProjection = false) {
        return {
            label,
            data,
            ...(isProjection ? this.datasetStyles.projectionData : this.datasetStyles.realData)
        };
    },
    
    getChartOptions: function(title, additionalOptions = {}) {
        return {
            ...this.chartConfigs.common,
            ...this.chartConfigs.lineChart.options,
            plugins: {
                ...this.chartConfigs.lineChart.options.plugins,
                title: {
                    display: true,
                    text: title
                }
            },
            ...additionalOptions
        };
    },

    // Modal management utilities
    modalUtils: {
        setupModal: function(modalId, onShown = null, onHidden = null) {
            const $modal = $(modalId);
            
            // Remove existing event listeners
            $modal.off('shown.bs.modal hidden.bs.modal');
            
            // Add new event listeners
            if (onShown) {
                $modal.on('shown.bs.modal', onShown);
            }
            
            if (onHidden) {
                $modal.on('hidden.bs.modal', onHidden);
            }
            
            return $modal;
        },
        
        updateModalTitle: function(modalId, title) {
            $(`${modalId}-label`).text(title);
        },
        
        ensureCanvasHeight: function(canvas) {
            if (canvas && canvas.parentElement) {
                canvas.parentElement.style.height = '400px';
            }
        }
    },

    // Chart initialization functions
    inicializarGraficoCuentas: function(ctx, datos) {
        return new Chart(ctx, {
            type: 'line',
            ...this.chartConfigs.common,
            data: {
                labels: datos.labels,
                datasets: [
                    this.createLineDataset('Ingresos', datos.ingresos),
                    this.createLineDataset('Egresos', datos.egresos)
                ]
            },
            options: this.getChartOptions('Análisis de Cuentas')
        });
    },

    inicializarGraficoPresupuesto: function(ctx, datos, categoria) {
        return new Chart(ctx, {
            type: 'line',
            ...this.chartConfigs.common,
            data: {
                labels: datos.labels,
                datasets: [
                    this.createLineDataset('Presupuesto', datos.presupuesto),
                    this.createLineDataset('Gasto Real', datos.gasto_real)
                ]
            },
            options: this.getChartOptions(`Presupuesto vs Gasto Real - ${categoria}`)
        });
    },

    inicializarGraficoProyecciones: function(ctx, historico, proyeccion) {
        const data = {
            labels: [...historico.labels, ...proyeccion.labels],
            datasets: [
                this.createLineDataset(
                    'Ventas Reales',
                    [...historico.valores, ...Array(proyeccion.valores.length).fill(null)]
                ),
                this.createLineDataset(
                    'Proyecciones',
                    [...Array(historico.valores.length - 1).fill(null), 
                     historico.valores[historico.valores.length - 1], 
                     ...proyeccion.valores],
                    true
                )
            ]
        };

        return new Chart(ctx, {
            type: 'line',
            data: data,
            options: this.getChartOptions('Proyecciones Futuras')
        });
    },

    inicializarGraficoModal: function(ctx, modalId, datos, producto) {
        let chartData;
        
        switch (modalId) {
            case 'modal-rotacion':
                chartData = {
                    labels: datos.rotacion.labels,
                    datasets: [
                        this.createLineDataset('Stock', datos.rotacion.stock),
                        this.createLineDataset('Ventas', datos.rotacion.ventas)
                    ]
                };
                break;
            case 'modal-proyeccion':
                const tipoAnalisis = $('#ver-historico').val();
                if (tipoAnalisis === 'proyecciones') {
                    const historico = datos.proyeccion;
                    const ultimoValorHistorico = historico.ventas_reales[historico.ventas_reales.length - 1];
                    const mesesFuturos = ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    
                    chartData = {
                        labels: [...historico.labels, ...mesesFuturos],
                        datasets: [
                            this.createLineDataset(
                                'Ventas Reales',
                                [...historico.ventas_reales, ...Array(mesesFuturos.length).fill(null)]
                            ),
                            this.createLineDataset(
                                'Proyecciones',
                                [
                                    ...Array(historico.ventas_reales.length - 1).fill(null),
                                    ultimoValorHistorico,
                                    datos.resumen.proyecciones.tresMeses,
                                    (datos.resumen.proyecciones.tresMeses + datos.resumen.proyecciones.seisMeses) / 2,
                                    datos.resumen.proyecciones.seisMeses,
                                    (datos.resumen.proyecciones.seisMeses + datos.resumen.proyecciones.docesMeses) / 2,
                                    (datos.resumen.proyecciones.seisMeses + datos.resumen.proyecciones.docesMeses) / 2,
                                    datos.resumen.proyecciones.docesMeses
                                ],
                                true
                            )
                        ]
                    };
                } else {
                    chartData = {
                        labels: datos.proyeccion.labels,
                        datasets: [
                            this.createLineDataset('Ventas Reales', datos.proyeccion.ventas_reales),
                            this.createLineDataset('Proyectado', datos.proyeccion.proyectado, true)
                        ]
                    };
                }
                break;
            case 'modal-rentabilidad':
                chartData = {
                    labels: datos.rentabilidad.labels,
                    datasets: [
                        this.createLineDataset('Rentabilidad', datos.rentabilidad.rentabilidad),
                        this.createLineDataset('ROI', datos.rentabilidad.roi)
                    ]
                };
                break;
        }

        return new Chart(ctx, {
            type: 'line',
            ...this.chartConfigs.common,
            data: chartData,
            options: this.getChartOptions(
                `${modalId === 'modal-proyeccion' ? 
                    ($('#ver-historico').val() === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica') : 
                    modalId.replace('modal-', '').charAt(0).toUpperCase() + modalId.slice(7)} - ${producto}`
            )
        });
    }
};

// Helper function for number formatting
function formatearNumero(numero) {
    return new Intl.NumberFormat('es-VE', {
        style: 'currency',
        currency: 'USD'
    }).format(numero);
} 
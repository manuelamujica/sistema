window.UtilidadesGraficos = {
    configuraciones: {
        comun: {
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
        graficoLinea: {
            type: 'line',
            options: {
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(contexto) {
                                let etiqueta = contexto.dataset.label || '';
                                if (etiqueta) {
                                    etiqueta += ': ';
                                }
                                if (contexto.parsed.y !== null) {
                                    etiqueta += formatearNumero(contexto.parsed.y);
                                }
                                return etiqueta;
                            }
                        }
                    }
                }
            }
        }
    },
    estilosDataset: {
        datosReales: {
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        },
        datosProyectados: {
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderDash: [5, 5],
            tension: 0.4,
            fill: true
        }
    },
    destruirGrafico: function(grafico) {
        if (grafico) {
            grafico.destroy();
            return null;
        }
        return null;
    },

    crearDatasetLinea: function(etiqueta, datos, esProyeccion = false) {
        return {
            label: etiqueta,
            data: datos,
            ...(esProyeccion ? this.estilosDataset.datosProyectados : this.estilosDataset.datosReales)
        };
    },
    
    obtenerOpciones: function(titulo, opcionesAdicionales = {}) {
        return {
            ...this.configuraciones.comun,
            ...this.configuraciones.graficoLinea.options,
            plugins: {
                ...this.configuraciones.graficoLinea.options.plugins,
                title: {
                    display: true,
                    text: titulo
                }
            },
            ...opcionesAdicionales
        };
    },

    utilidadesModal: {
        configurarModal: function(idModal, alMostrar = null, alOcultar = null) {
            const $modal = $(idModal);
            $modal.off('shown.bs.modal hidden.bs.modal');
            if (alMostrar) {
                $modal.on('shown.bs.modal', alMostrar);
            }
            
            if (alOcultar) {
                $modal.on('hidden.bs.modal', alOcultar);
            }
            
            return $modal;
        },
        
        actualizarTituloModal: function(idModal, titulo) {
            $(`${idModal}-label`).text(titulo);
        },
        
        ajustarAltoCanvas: function(canvas) {
            if (canvas && canvas.parentElement) {
                canvas.parentElement.style.height = '400px';
            }
        }
    },

    inicializarGraficoCuentas: function(ctx, datos) {
        return new Chart(ctx, {
            type: 'line',
            ...this.configuraciones.comun,
            data: {
                labels: datos.labels,
                datasets: [
                    this.crearDatasetLinea('Ingresos', datos.ingresos),
                    this.crearDatasetLinea('Egresos', datos.egresos)
                ]
            },
            options: this.obtenerOpciones('Análisis de Cuentas')
        });
    },

    inicializarGraficoPresupuesto: function(ctx, datos, categoria) {
        return new Chart(ctx, {
            type: 'line',
            ...this.configuraciones.comun,
            data: {
                labels: datos.labels,
                datasets: [
                    this.crearDatasetLinea('Presupuesto', datos.presupuesto),
                    this.crearDatasetLinea('Gasto Real', datos.gasto_real)
                ]
            },
            options: this.obtenerOpciones(`Presupuesto vs Gasto Real - ${categoria}`)
        });
    },

    inicializarGraficoProyecciones: function(ctx, historico, proyeccion) {
        console.group('Inicializando Gráfico Proyecciones');
        console.log('Datos históricos recibidos:', historico);
        console.log('Datos proyecciones recibidos:', proyeccion);
        
        const tipoAnalisis = $('#ver-historico').val();
        let datos;
        
        if (tipoAnalisis === 'precision' && window.datosFinanzas?.datos_grafico_proyecciones?.historicas) {
            const historicas = window.datosFinanzas.datos_grafico_proyecciones.historicas;
            datos = {
                labels: historicas.labels,
                datasets: [
                    this.crearDatasetLinea(
                        'Ventas Reales',
                        historicas.reales
                    ),
                    this.crearDatasetLinea(
                        'Proyecciones Históricas',
                        historicas.valores,
                        true
                    )
                ]
            };
        } else {
            datos = {
                labels: [...historico.labels, ...proyeccion.labels],
                datasets: [
                    this.crearDatasetLinea(
                        'Ventas Reales',
                        [...historico.valores, ...Array(proyeccion.valores.length).fill(null)]
                    ),
                    ...(proyeccion.valores.length > 0 ? [
                        this.crearDatasetLinea(
                            'Proyecciones',
                            [...Array(historico.valores.length - 1).fill(null), 
                             historico.valores[historico.valores.length - 1], 
                             ...proyeccion.valores],
                            true
                        )
                    ] : [])
                ]
            };
        }

        console.log('Datos finales para el gráfico:', datos);
        console.groupEnd();

        return new Chart(ctx, {
            type: 'line',
            data: datos,
            options: this.obtenerOpciones('Análisis de Ventas', {
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Ventas (USD)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Período'
                        }
                    }
                }
            })
        });
    },

    inicializarGraficoModal: function(ctx, idModal, datos, producto) {
        let datosGrafico;
        
        switch (idModal) {
            case 'modal-rotacion':
                datosGrafico = {
                    labels: datos.rotacion.labels,
                    datasets: [
                        this.crearDatasetLinea('Stock', datos.rotacion.stock),
                        this.crearDatasetLinea('Ventas', datos.rotacion.ventas)
                    ]
                };
                break;
            case 'modal-proyeccion':
                const tipoAnalisis = $('#ver-historico').val();
                if (tipoAnalisis === 'proyecciones') {
                    const historico = datos.proyeccion;
                    const ultimoValorHistorico = historico.ventas_reales[historico.ventas_reales.length - 1];
                    const mesesFuturos = ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                    
                    datosGrafico = {
                        labels: [...historico.labels, ...mesesFuturos],
                        datasets: [
                            this.crearDatasetLinea(
                                'Ventas Reales',
                                [...historico.ventas_reales, ...Array(mesesFuturos.length).fill(null)]
                            ),
                            this.crearDatasetLinea(
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
                    datosGrafico = {
                        labels: datos.proyeccion.labels,
                        datasets: [
                            this.crearDatasetLinea('Ventas Reales', datos.proyeccion.ventas_reales),
                            this.crearDatasetLinea('Proyectado', datos.proyeccion.proyectado, true)
                        ]
                    };
                }
                break;
            case 'modal-rentabilidad':
                datosGrafico = {
                    labels: datos.rentabilidad.labels,
                    datasets: [
                        this.crearDatasetLinea('Rentabilidad', datos.rentabilidad.rentabilidad),
                        this.crearDatasetLinea('ROI', datos.rentabilidad.roi)
                    ]
                };
                break;
        }

        return new Chart(ctx, {
            type: 'line',
            ...this.configuraciones.comun,
            data: datosGrafico,
            options: this.obtenerOpciones(
                `${idModal === 'modal-proyeccion' ? 
                    ($('#ver-historico').val() === 'proyecciones' ? 'Proyección de Ventas' : 'Precisión Histórica') : 
                    idModal.replace('modal-', '').charAt(0).toUpperCase() + idModal.slice(7)} - ${producto}`
            )
        });
    }
};
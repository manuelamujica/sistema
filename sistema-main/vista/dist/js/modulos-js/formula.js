//MODULO DE AJUSTES DE NOMINA

$(document).ready(function () {

    let formulaIndex = 0;


    function crearFila(index) {
        return `
        <tr id="fila${index}">
            <td>
                <div class="input-group">
                    <input type="text" class="form-control variable-input" id="variable${index}_1" name="variable[${index}][variable1]" placeholder="Escriba una variable">
                    <input type="hidden" id="cod_var${index}_1" name="variable[${index}][cod_var1]"> <!-- Campo oculto para el código -->
                    <div id="listaVariables${index}_1" class="list-group" style="position: absolute; z-index: 1000;"></div>
                </div>
            </td>
            <td>
                <select class="form-control operador-select" id="operador${index}" name="formula[${index}][operador]" required>
                    <option value="">Cargando operadores...</option>
                </select>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control variable-input" id="variable${index}_2" name="variable[${index}][variable2]" placeholder="Escriba una variable">
                    <input type="hidden" id="cod_var${index}_2" name="variable[${index}][cod_var2]"> <!-- Campo oculto para el código -->
                    <div id="listaVariables${index}_2" class="list-group" style="position: absolute; z-index: 1000;"></div>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-success btn-sm" onclick="agregarColumna(${index})">+</button>
                
            </td>
        </tr>
        `;

        cargarOperadores(`operador${index}`);
    }
    window.agregarColumna = function (index) {
        const fila = document.getElementById(`fila${index}`);
        if (fila) {
            const nuevoIndice = fila.querySelectorAll('.variable-input').length + 1; 
            const nuevaColumna = `
                <td>
                    <select class="form-control operador-select" id="operador${index}_${nuevoIndice}" name="formula[${index}][operador${nuevoIndice}]" required>
                        <option value="">Seleccione un operador</option>
                    </select>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarColumna(this)">&times;</button>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control variable-input" id="variable${index}_${nuevoIndice}" name="variable[${index}][variable${nuevoIndice}]" placeholder="Escriba una variable">
                        <input type="hidden" id="cod_var${index}_${nuevoIndice}" name="variable[${index}][cod_var${nuevoIndice}]"> <!-- Campo oculto para el código -->
                        <div id="listaVariables${index}_${nuevoIndice}" class="list-group" style="position: absolute; z-index: 1000;"></div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarColumna(this)">&times;</button>
                </td>
            `;
            const botonesCelda = fila.querySelector('td:last-child'); 
            fila.insertAdjacentHTML('beforeend', nuevaColumna);
            fila.appendChild(botonesCelda); 

            cargarOperadores(`operador${index}_${nuevoIndice}`);
        }
    };


    $('#modalregistrarFormula').on('show.bs.modal', function () {

        $('#formulas tbody').empty();

        productoIndex = 0;


        var nuevaFila = crearFila(productoIndex);
        $('#formulas tbody').append(nuevaFila);
    });

    $(document).on('input', '[id^=variable]', function () {
        const inputId = $(this).attr('id'); 
        const index = inputId.split('_')[0].replace('variable', ''); 
        const subIndex = inputId.split('_')[1]; 
        const query = $(this).val(); 

        if (query.length > 2) {
            $.ajax({
                url: 'index.php?pagina=nomina',
                method: 'POST',
                data: {
                    buscar: query 
                },
                dataType: 'json',
                success: function (data) {
                    const listaVariables = $(`#listaVariables${index}_${subIndex}`);
                    listaVariables.empty(); 

                    if (data.length > 0) {

                        $.each(data, function (key, variable) {
                            listaVariables.append(
                                `<a href="#" class="list-group-item list-group-item-action variable-item" 
                                data-nombre="${variable.nombre_var}" 
                                data-id="${variable.cod_var}">
                                ${variable.nombre_var}
                            </a>`
                            );
                        });
                        listaVariables.fadeIn();
                    } else {
                        listaVariables.append('<p class="list-group-item">No se encontraron variables</p>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        } else {
            $(`#listaVariables${index}_${subIndex}`).fadeOut(); 
        }
    });



    $(document).on('click', '.variable-item', function (e) {
        e.preventDefault();
        const selectedVar = $(this).data('nombre');
        const codigo = $(this).data('id');

        const listaId = $(this).closest('.list-group').attr('id'); 
        const index = listaId.split('_')[0].replace('listaVariables', '');
        const subIndex = listaId.split('_')[1]; 

        $(`#variable${index}_${subIndex}`).val(selectedVar);
        $(`#cod_var${index}_${subIndex}`).val(codigo); 

 
        $(this).closest('.list-group').fadeOut();
    });


    $('#formRegistrarFormula').on('submit', function (event) {
        event.preventDefault(); 

        const formulaNombre = $('#formula').val().trim(); 
        if (!formulaNombre) {
            Swal.fire('Error', 'El nombre de la fórmula no puede estar vacío.', 'error');
            return;
        }

        let expresion = '';
        let detalles = [];


        $('#formulas tbody tr').each(function () {
            const variables = [];
            const operadores = [];

 
            $(this).find('input.variable-input').each(function () {
                const variable = $(this).val();
                const cod_var = $(this).siblings('input[type="hidden"]').val();
                if (variable && cod_var) {
                    variables.push({ variable, cod_var });
                }
            });


            $(this).find('select.operador-select').each(function () {
                const operadorNombre = $(this).find('option:selected').text();
                const cod_operador = $(this).val();
                if (operadorNombre && cod_operador) {
                    operadores.push({ operadorNombre, cod_operador });
                }
            });


            if (variables.length > 0) {
                expresion += variables[0].variable; 

                for (let i = 0; i < operadores.length; i++) {
                    const operador = operadores[i]?.operadorNombre || ''; 
                    const variableActual = variables[i]?.variable || ''; 
                    const variableSiguiente = variables[i + 1]?.variable || ''; 


                    if (variables[i] && variables[i + 1]) {

                        expresion += ` ${operador} ${variableSiguiente}`;


                        detalles.push({
                            cod_var: variables[i].cod_var,
                            operador: operadores[i]?.cod_operador || '', 
                            cod_var2: variables[i + 1]?.cod_var || ''
                        });
                    }
                }
            }

            if (detalles.length === 0) {
                Swal.fire('Error', 'Debe agregar al menos una fórmula.', 'error');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=nomina',
                data: {
                    registrarFormula: true,
                    formulaNombre,
                    expresion,
                    detalles: JSON.stringify(detalles) 
                },
                dataType: 'json',
                success: function (response) {
                    console.log(expresion);
                    console.log(response);
                    if (response.status === 'success') {
                        Swal.fire('Éxito', 'Fórmula registrada correctamente.', 'success');
                        $('#modalregistrarFormula').modal('hide'); 
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    console.error('Estado:', status);
                    console.error('Respuesta del servidor:', xhr.responseText);
                    Swal.fire('Error', 'Ocurrió un error al registrar la fórmula.', 'error');
                }
            });
        });
    });

    function cargarOperadores() {
        $.ajax({
            type: 'POST',
            url: 'index.php?pagina=nomina', 
            data: { obtenerOperadores: true }, 
            dataType: 'json',
            success: function (operadores) {
 
                $('.operador-select').each(function () {
                    const select = $(this);
                    select.empty(); 
                    select.append('<option value="">Seleccione un operador</option>'); 

                  
                    operadores.forEach(operador => {
                        select.append(`<option value="${operador.cod_operador}">${operador.operador}</option>`);
                    });
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar operadores:', error);
            }
        });
    }

    $(document).ready(function () {
       
        cargarOperadores();


        $('#modalregistrarFormula').on('show.bs.modal', function () {
            cargarOperadores();
        });
    });

    $(document).ready(function () {
   
        $('#formregistrarVariable').on('submit', function (event) {
            event.preventDefault(); 

     
            const variable = $('#variable').val().trim();

        
            if (variable === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'El nombre de la variable no puede estar vacío.',
                    icon: 'error'
                });
                return;
            }
            console.log("pasamos por aqui");
            console.log(variable);
       
            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=nomina',
                data: { registrarVariable: true, variable: variable }, 
                dataType: 'json',
                success: function (response) {

                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Éxito',
                            text: 'Variable registrada correctamente.',
                            icon: 'success'
                        }).then(() => {
                           
                            $('#modalregistrarVariable').modal('hide');
                        
                            $('#formregistrarVariable')[0].reset();
                     
                            actualizarListaVariables();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al registrar la variable.',
                        icon: 'error'
                    });
                }
            });
        });

        function actualizarListaVariables() {
            $.ajax({
                type: 'POST',
                url: 'index.php?pagina=nomina',
                data: { obtenerVariables: true },
                dataType: 'json',
                success: function (variables) {

                    $('.variable-input').each(function () {
                        const listaId = $(this).attr('id').replace('variable', 'listaVariables');
                        const lista = $(`#${listaId}`);
                        lista.empty(); 

                        variables.forEach(variable => {
                            lista.append(
                                `<a href="#" class="list-group-item list-group-item-action variable-item" 
                                data-nombre="${variable.nombre}" 
                                data-id="${variable.id}">
                                ${variable.nombre}
                            </a>`
                            );
                        });
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error al actualizar la lista de variables:', error);
                }
            });
        }
    });

    $('#add-product').on('click', function () {
        productoIndex++;
        var nuevaFila = crearFila(productoIndex);
        $('#productosCarga tbody').append(nuevaFila);
    });

    window.eliminarColumna = function (boton) {

        const celda = boton.closest('td'); 
        if (celda) {
            celda.remove();
        }
    };



    // VALIDAR FORMULAS
    $('#formula').blur(function (e) {
        let expresion = ''; 
        const detalles = [];

        $('#formulas tbody tr').each(function () {
            const variables = [];
            const operadores = [];


            $(this).find('input.variable-input').each(function () {
                const variable = $(this).val();
                const cod_var = $(this).siblings('input[type="hidden"]').val();
                if (variable && cod_var) {
                    variables.push({ variable, cod_var });
                }
            });

     
            $(this).find('select.operador-select').each(function () {
                const operadorNombre = $(this).find('option:selected').text(); 
                const cod_operador = $(this).val(); // 
                if (operadorNombre && cod_operador) {
                    operadores.push({ operadorNombre, cod_operador });
                }
            });

            if (variables.length > 0) {
                expresion += variables[0].variable; 

                for (let i = 0; i < operadores.length; i++) {
                    const operador = operadores[i]?.operadorNombre || ''; 
                    const variableSiguiente = variables[i + 1]?.variable || '';

                    if (variables[i] && variables[i + 1]) {
                        expresion += ` ${operador} ${variableSiguiente}`;
                    }
                }
            }
        });


        if (expresion.trim().length > 0) {
            $.post('index.php?pagina=nomina', { buscarF: expresion }, function (response) {
                if (response.length > 0) { 
                    Swal.fire({
                        title: 'Error',
                        text: 'Esta fórmula ya se encuentra registrada.',
                        icon: 'warning'
                    });
                }
            }, 'json').fail(function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            });
        }
    });

    $('#variable').blur(function (e) {
        var buscarV = $('#variable').val();
        $.post('index.php?pagina=nomina', { buscarV }, function (response) {
            if (response != '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Esta variable ya se encuentra registrada.',
                    icon: 'warning'
                });
            }
        }, 'json');
    });
});

$('#unidad').DataTable({
    responsive: true,

    columnDefs: [
        { responsivePriority: 1, targets: 0 }, 
        { responsivePriority: 2, targets: -1 } 
    ],
    language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        },
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron registros"
    }

});


$('#unidad2').DataTable({
    responsive: true,

    columnDefs: [
        { responsivePriority: 1, targets: 0 }, 
        { responsivePriority: 2, targets: -1 } 
    ],
    language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        },
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron registros"
    }

});

//EDITAR FORMULA

$(document).ready(function () {

    $('.editarf').click(function () {
        
        
         var codf = $(this).data('codf');
         var nombref = $(this).data('nombref');
         var expresionf = $(this).data('expresionf');
         var expresionfo = $(this).data('expresionf');
         var statusf = $(this).data('statusf');
 
        
 
     
         $('#codf').val(codf);
         $('#cod_ocultof').val(codf);
         $('#nombref').val(nombref);
         $('#nombref_origin').val(nombref);
         $('#statusf').val(statusf);
         $('#expresion').val(expresionf); 


    });
    });

    //ELIMINAR
$('#modaleliminarf').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codigo = button.data('codf');
    var formula = button.data('nombref');

    var modal = $(this);
    modal.find('.modal-body #cod_eliminarf').val(codigo);
    modal.find('.modal-body #eliminarf').text(formula);

    
});

//VARIABLES
//EDITAR VARIABLES

$(document).ready(function () {

    $('.editarv').click(function () {
        
         // Obtener los datos del botón
         var codv = $(this).data('codv');
         var nombrev = $(this).data('nombrev');
         var statusv = $(this).data('statusv');
 
        

         $('#codv').val(codv);
         $('#cod_ocultov').val(codv);
         $('#nombrev').val(nombrev);
         $('#nombre_originv').val(nombrev);
         $('#statusv').val(statusv);

    });
    });

    //ELIMINAR
$('#modaleliminarv').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var codv = button.data('codv');
    var nombrev = button.data('nombrev');

    var modal = $(this);
    modal.find('.modal-body #cod_eliminarv').val(codv);
    modal.find('.modal-body #eliminarv').text(nombrev);

    
});


$('#modalregistrarFormula').on('shown.bs.modal', function () {
    $(this).trigger('focus'); 
});




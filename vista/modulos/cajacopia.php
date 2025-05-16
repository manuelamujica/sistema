<?php require_once 'controlador/cajacopia.php' ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-9">
                <h1>Caja</h1>
                <p>En esta sección se puede gestionar las cajas.</p>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Botón para ventana modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarUnidad">Registrar Caja</button>
                        </div>
                        <div class="card-body">
                            <!-- MOSTRAR EL REGISTRO DE CAJA-->
                            <div class="table-responsive">
                                <table id="unidad" class="table table-bordered table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Saldo</th>
                                            <th>Divisa</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($datos as $dato) {
                                            if ($dato['status'] != 2) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $dato['cod_caja'] ?></td>
                                                    <td><?php echo $dato['nombre'] ?></td>
                                                    <td><?php echo $dato['saldo'] ?></td>
                                                    <td><?php echo $dato['divisa'] ?></td>

                                                    <td>
                                                        <?php if ($dato['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                    <button name="ajustar" class="btn btn-secondary btn-sm movimientos" title="Ver Movimientos" data-toggle="modal" data-target="#modalmovimientos"> <i class="fas fa-eye"></i> </button>
                                                    <button name="ajustar" class="btn btn-secondary btn-sm movimientos" title="Ver detalles" data-toggle="modal" data-target="#modalcontrol"><i class="fas fa-list"></i></button>

                                                    <button name="ajustar" class="btn btn-primary btn-sm editar" title="Editar" data-toggle="modal" data-target="#modalmodificarcaja"
                                                            data-cod="<?php echo $dato['cod_caja']; ?>"
                                                            data-nombre="<?php echo $dato['nombre']; ?>"
                                                            data-saldo="<?php echo $dato['saldo']; ?>"
                                                            data-divisa="<?php echo $dato['cod_divisa']; ?>"
                                                            data-status="<?php echo $dato['status']; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>                                                     
                                                        <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#modaleliminar"
                                                        data-cod="<?php echo $dato['cod_caja']; ?>"
                                                        data-nombre="<?php echo $dato['nombre']; ?>">   
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                    </tbody>
                            </div>
                            </table>
                        </div>
                    </div>

<!-- =======================
MODAL REGISTRAR CAJA
============================= -->

                    <div class="modal fade" id="modalregistrarUnidad" tabindex="-1" aria-labelledby="modalregistrarUnidadLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                                    <h5 class="modal-title" id="exampleModalLabel">Registrar Caja</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formregistrarUnidad" method="post">
                                        <!--   TIPO DE CAJA     -->
                                        <div class="form-group">
                                            <label for="nombre">Nombre de la Caja</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el nombre de la caja, por ejemplo: Caja Chica">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" name="nombre" placeholder="Nombre de la caja" id="nombre" maxlength="20">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="saldo">Saldo inicial en caja</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el saldo inicial de la caja, por ejemplo: 1000">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" name="saldo" placeholder="Saldo de la caja" id="saldo" maxlength="20">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <!-- Cambiar el input de divisa por un select -->
                                    <div class="form-group">
                                        <label for="divisa">Divisa</label>
                                        <select class="form-control" name="divisa" id="divisa" required>
                                            <?php foreach($divisas as $div): ?>
                                                <option value="<?php echo $div['cod_divisa']; ?>"><?php echo $div['nombre']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                                                    </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($registrar)): ?>
                        <script>
                            Swal.fire({
                                title: '<?php echo $registrar["title"]; ?>',
                                text: '<?php echo $registrar["message"]; ?>',
                                icon: '<?php echo $registrar["icon"]; ?>',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'cajacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <!-- MODAL EDITAR -->
   <!-- MODAL EDITAR MEJORADO -->
<div class="modal fade" id="modalmodificarcaja">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:rgb(0, 102, 219); color: #ffffff;">
                <h4 class="modal-title">Editar Caja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form" method="post" id="form-editar-caja">
                <div class="modal-body">
                    <!-- Código -->
                    <div class="form-group">
                        <label for="cod_caja">Código</label>
                        <input type="text" class="form-control" name="cod_caja" id="cod_caja" readonly>
                    </div>

                    <!-- Nombre de la Caja -->
                    <div class="form-group">
                        <label for="nombre1">Nombre de la Caja</label>
                        <input type="text" class="form-control" name="nombre1" id="nombre1" required>
                        <div class="invalid-feedback">Por favor ingrese el nombre de la caja</div>
                    </div>

                    <!-- Saldo -->
                    <div class="form-group">
                        <label for="saldo1">Saldo inicial en caja</label>
                        <input type="number" step="0.01" class="form-control" name="saldo1" id="saldo1" required>
                        <div class="invalid-feedback">Por favor ingrese un saldo válido</div>
                    </div>

                    <!-- Divisa -->
                    <div class="form-group">
                        <label for="divisa1">Divisa</label>
                        <select class="form-control" name="divisa1" id="divisa1" required>
                            <?php foreach($divisas as $div): ?>
                                <option value="<?php echo $div['cod_divisa']; ?>">
                                    <?php echo htmlspecialchars($div['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una divisa</div>
                    </div>

                    <!-- Estatus -->
                    <div class="form-group">
                        <label for="status">Estatus</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="editar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
  <?php
                    if (isset($editar)): ?>
                        <script>
                            Swal.fire({
                                title: '<?php echo $editar["title"]; ?>',
                                text: '<?php echo $editar["message"]; ?>',
                                icon: '<?php echo $editar["icon"]; ?>',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'cajacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>
                     
                    <!-- MODAL CONTROL-->
<div class="modal fade" id="modalcontrol">
    <div class="modal-dialog modal-lg"> <!-- Añadido modal-lg para hacerlo más ancho -->
        <div class="modal-content">
            <div class="modal-header" style="background:rgb(57, 128, 236); color: #ffffff;">
                <h4 class="modal-title">Control de Caja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="post" id="form-editar-unidad">
                <div class="modal-body">
                    <input type="hidden" name="cod_caja_oculto" id="cod_caja_oculto" value="<?php echo $dato['cod_caja'] ?>">
                    
                    <div class="row"> <!-- Agregado row para organizar en columnas -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cod_caja">Código</label>
                                <input type="text" class="form-control" name="cod_caja" id="cod_caja" value="<?php echo $dato['cod_caja'] ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="divisa">Divisa</label>
                                <div class="form-control" style="background-color: #e9ecef; padding: 6px 12px; border-radius: 4px; min-height: 38px;">
                                    <?php 
                                    $selectedDivisa = isset($_POST['divisa']) ? $_POST['divisa'] : $divisas[0]['cod_divisa'];
                                    foreach($divisas as $div) {
                                        if($div['cod_divisa'] == $selectedDivisa) {
                                            echo htmlspecialchars($div['nombre']);
                                            break;
                                        }
                                    }
                                    ?>
                                </div>
                                <input type="hidden" name="divisa" value="<?php echo $selectedDivisa; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Caja</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $dato['nombre'] ?>" maxlength="20" readonly> <!-- Añadido readonly -->
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="saldo">Saldo inicial en caja</label>
                                <input type="text" class="form-control" name="saldo" id="saldo" value="<?php echo $dato['saldo'] ?>" maxlength="20" readonly> <!-- Añadido readonly -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_apertura">Fecha Apertura</label>
                                <input type="datetime-local" class="form-control" name="fecha_apertura" id="fecha_apertura" value="<?php echo date('Y-m-d\TH:i', strtotime($dato['fecha_apertura'] ?? 'now')); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_cierre">Fecha Cierre</label>
                                <input type="datetime-local" class="form-control" name="fecha_cierre" id="fecha_cierre" value="<?php echo isset($dato['fecha_cierre']) ? date('Y-m-d\TH:i', strtotime($dato['fecha_cierre'])) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Estatus</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" <?php echo ($dato['status'] ?? 1) == 1 ? 'selected' : ''; ?>>Abierta</option>
                                    <option value="0" <?php echo ($dato['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Cerrada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="invalid-feedback" style="display: none;"></div>
                </div>
                
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="control">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
                    <?php
                    if (isset($control)): ?>
                        <script>
                            Swal.fire({
                                title: '<?php echo $control["title"]; ?>',
                                text: '<?php echo $control["message"]; ?>',
                                icon: '<?php echo $control["icon"]; ?>',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'cajacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>
                  
                    <!-- MODAL Movimientos-->
<div class="modal fade" id="modalmovimientos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:rgb(57, 128, 236); color: #ffffff;">
                <h4 class="modal-title">Historial de Movimientos - Caja <?php echo htmlspecialchars($dato['nombre']); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                
            </div>
            <div class="modal-body">
                <!-- Información básica de la caja -->
                <div class="row mb-4">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Código</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php echo htmlspecialchars($dato['cod_caja']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Divisa</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php 
                                foreach($divisas as $div) {
                                    if($div['cod_divisa'] == $selectedDivisa) {
                                        echo htmlspecialchars($div['nombre']);
                                        break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Saldo actual</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php echo htmlspecialchars($dato['saldo']); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas de apertura y cierre -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Apertura</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php echo date('d/m/Y H:i', strtotime($dato['fecha_apertura'] ?? 'now')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Cierre</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php echo isset($dato['fecha_cierre']) ? date('d/m/Y H:i', strtotime($dato['fecha_cierre'])) : 'Caja aún abierta'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de movimientos -->
                <div class="row">
                    <div class="col-12">
                        <h5>Detalle de Movimientos</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Monto</th>
                                        <th>Responsable</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php 
                                    
                                    /*
                                    foreach($movimientos as $mov) {
                                        echo '<tr>';
                                        echo '<td>'.date('d/m/Y H:i', strtotime($mov['fecha'])).'</td>';
                                        echo '<td>'.($mov['tipo'] == 'entrada' ? '<span class="badge badge-success">Entrada</span>' : '<span class="badge badge-danger">Salida</span>').'</td>';
                                        echo '<td>'.htmlspecialchars($mov['motivo']).'</td>';
                                        echo '<td>'.htmlspecialchars($mov['monto']).'</td>';
                                        echo '<td>'.htmlspecialchars($mov['responsable']).'</td>';
                                        echo '</tr>';
                                    }
                                    */
                                    ?>
                                    <!-- Ejemplo de fila -->
                                    <tr>
                                        <td>15/04/2023 10:30</td>
                                        <td><span class="badge badge-success">Entrada</span></td>
                                        <td>Venta #12345</td>
                                        <td>150.00</td>
                                        <td>Juan Pérez</td>
                                    </tr>
                                    <tr>
                                        <td>15/04/2023 12:45</td>
                                        <td><span class="badge badge-danger">Salida</span></td>
                                        <td>Compra de materiales</td>
                                        <td>75.50</td>
                                        <td>María Gómez</td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success text-white" onclick="exportarMovimientosPDF()">
                        Exportar PDF de movimientos
                    </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
                    <?php
                    if (isset($control)): ?>
                        <script>
                            Swal.fire({
                                title: '<?php echo $control["title"]; ?>',
                                text: '<?php echo $control["message"]; ?>',
                                icon: '<?php echo $control["icon"]; ?>',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'cajacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>







                </div>
            </div>
        </div>
    </section>
</div>

<!-- Confirmar Eliminar Modal -->
<div class="modal fade" id="modaleliminar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Confirmar Eliminar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <p>¿Estás seguro de eliminar la caja: <b><span id=nombreD></span>?</p></b>
                    <input type="hidden" name="eliminar" id="cod_eliminar" >
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($eliminar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $eliminar["title"]; ?>',
            text: '<?php echo $eliminar["message"]; ?>',
            icon: '<?php echo $eliminar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'cajacopia';
            }
        });
    </script>
<?php endif; ?>


<script src="vista/dist/js/modulos-js/cajacopia.js"></script>

</section>
</div>
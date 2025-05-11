<?php require_once 'controlador/cuentabancariacopia.php' ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-9">
                <h1>Cuenta Bancaria</h1>
                <p>En esta sección se puede gestionar las cuentas bancarias.</p>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarCuenta">Registrar Cuenta Bancaria</button>
                        </div>
                        <div class="card-body">
                            <!-- MOSTRAR EL REGISTRO DE UNIDADES DE MEDIDA -->
                            <div class="table-responsive">
                                <table id="unidad" class="table table-bordered table-striped datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Banco</th>
                                            <th>Tipo de cuenta</th>
                                            <th>Numero de cuenta</th>
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
                                                    <td><?php echo $dato['cod_cuenta_bancaria'] ?></td>
                                                    <td><?php echo $dato['nombre_banco'] ?></td>
                                                    <td><?php echo $dato['tipo_cuenta'] ?></td>
                                                    <td><?php echo $dato['numero_cuenta'] ?></td>
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
                                                    <button class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#modalmodificarcuenta"
                                                    data-cod="<?php echo $dato['cod_cuenta_bancaria']; ?>"
                                                    data-numero="<?php echo $dato['numero_cuenta']; ?>"
                                                    data-saldo="<?php echo $dato['saldo']; ?>"
                                                    data-divisa="<?php echo $dato['cod_divisa']; ?>"
                                                    data-status="<?php echo $dato['status']; ?>"
                                                    data-banco="<?php echo $dato['cod_banco']; ?>"
                                                    data-tipocuenta="<?php echo $dato['cod_tipo_cuenta']; ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                    
                                                        </button>
                                                        <button name="confirmar" class="btn btn-danger btn-sm eliminar" title="Eliminar" data-toggle="modal" id="modificar" data-target="#modaleliminar"
                                                        data-cod="<?php echo $dato['cod_cuenta_bancaria']; ?>"
                                                        data-numero="<?php echo $dato['numero_cuenta']; ?>">  
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
MODAL REGISTRAR CUENTA BANCARIA
============================= -->

                    <div class="modal fade" id="modalregistrarCuenta" tabindex="-1" aria-labelledby="modalregistrarCuentaLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background:rgb(35, 102, 245) ;color: #ffffff; ">
                                    <h5 class="modal-title" id="exampleModalLabel">Registrar Cuenta Bancaria</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formregistrarCuenta" method="post">
                                        <!--   Banco     -->
                                        <div class="form-group">
                                        <label for="banco">Banco</label>
                                        <select class="form-control" name="banco" id="banco" required>
                                            <?php foreach($banco as $ban): ?>
                                                <option value="<?php echo $ban['cod_banco']; ?>"><?php echo $ban['nombre_banco']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                      
                                    <div class="form-group">
                                        <label for="tipo_cuenta">Tipo de cuenta</label>
                                        <select class="form-control" name="tipo_cuenta" id="tipo_cuenta" required>
                                            <?php foreach($tipo as $tip): ?>
                                                <option value="<?php echo $tip['cod_tipo_cuenta']; ?>"><?php echo $tip['nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <script>
                                                    $(function () {
                                                        $('[data-toggle="tooltip"]').tooltip();
                                                    });
                                                </script>
                                        </select>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>

                                        <div class="form-group">
                                            <label for="numerocuenta">Numero de cuenta</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el número de cuenta">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" name="numerocuenta" placeholder="Ingrese el numero de cuenta" id="numerocuenta" maxlength="20">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                        <label for="divisa">Divisa</label>
                                        <select class="form-control" name="divisa" id="divisa" required>
                                            <?php foreach($divisas as $div): ?>
                                                <option value="<?php echo $div['cod_divisa']; ?>"><?php echo $div['nombre']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                    <div class="form-group">
                                            <label for="saldo">Saldo Inicial en cuenta</label>
                                            <!-- TOOLTIPS-->
                                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingrese el saldo inicial en cuenta">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                            <script>
                                                $(function () {
                                                    $('[data-toggle="tooltip"]').tooltip();
                                                });
                                            </script>
                                            <input type="text" class="form-control" name="saldo" placeholder="Ingrese el saldo inicial en cuenta" id="saldo" maxlength="10">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>

                                </div>

                                
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar"  onclick="return validacion();">Guardar</button>
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
                                    window.location = 'cuentabancariacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <!-- MODAL EDITAR -->
                    <div class="modal fade" id="modalmodificarcuenta">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background:rgb(27, 77, 242); color: #ffffff;">
                                    <h4 class="modal-title">Editar Cuenta Bancaria</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form role="form" method="post" id="form-editar-cuenta">

                                    <!--   CODIGO DE LA UNIDAD    -->

                                    <div class="modal-body">
                                        <input type="hidden" name="cod_cuenta_bancaria" id="cod_cuenta_bancaria_oculto" value="">
                                        <div class="form-group">
                                            <label for="cod_unidad">Código</label>
                                            <input type="text" class="form-control" name="cod_cuenta_bancaria1" id="cod_cuenta_bancaria1" value="" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="numero_cuenta">Número de cuenta</label>
                                            <input type="text" class="form-control" name="numero_cuenta1" id="numero_cuenta1" value="" maxlength="20">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                            <input type="hidden" id="origin" class="form-control" name="origin" maxlength="10">
                                        </div>
                                        <div class="form-group">
                                            <label for="numero_cuenta">Saldo</label>
                                            <input type="text" class="form-control" name="saldo1" id="saldo1" value="" maxlength="20">
                                            <div class="invalid-feedback" style="display: none;"></div>
                                            <input type="hidden" id="origin" class="form-control" name="origin" maxlength="10">
                                        </div>
                                        <div class="form-group">
                                        <label for="banco">Banco</label>
                                        <select class="form-control" name="banco1" id="banco1" required>
                                            <?php foreach($banco as $ban): ?>
                                                <option value="<?php echo $ban['cod_banco']; ?>"><?php echo $ban['nombre_banco']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                    <div class="form-group">
                                    <label for="tipo">Tipo de cuenta</label>
                                    <select class="form-control" name="tipodecuenta1" id="tipodecuenta1" required>
                                        <?php foreach($tipo as $tip): ?>
                                            <option value="<?php echo $tip['cod_tipo_cuenta']; ?>"
                                                <?php echo ($tip['cod_tipo_cuenta'] == $dato['cod_tipo_cuenta']) ? 'selected' : ''; ?>>
                                                <?php echo $tip['nombre'];  ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                    <div class="invalid-feedback" style="display: none;"></div>
                                </div>
                                        <div class="form-group">
                                        <label for="divisa">Divisa</label>
                                        <select class="form-control" name="divisa1" id="divisa1" required>
                                            <?php foreach($divisas as $div): ?>
                                                <option value="<?php echo $div['cod_divisa']; ?>"><?php echo $div['nombre']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                        <div class="form-group">
                                            <label for="status">Estatus</label>
                                            <select name="status" id="status">
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" name="editar">Editar</button>
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
                                    window.location = 'cuentabancariacopia';
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- MODAL MOVIMIENTOS-->
<!-------------------------------------------------------------->

<div class="modal fade" id="modalmovimientos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:rgb(57, 128, 236); color: #ffffff;">
                <h4 class="modal-title">Historial de Movimientos - Cuenta Bancaria <?php echo htmlspecialchars($dato['numero_cuenta']); ?></h4>
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
                                <?php echo htmlspecialchars($dato['cod_cuenta_bancaria']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Divisa</label>
                            <div class="form-control" style="background-color: #f8f9fa; padding: 6px 12px; border-radius: 4px;">
                                <?php 
                                foreach($divisas as $div) {
                                    if($div['cod_divisa'] == $dato['cod_divisa']) {
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
                    <p>¿Estás seguro de eliminar la cuenta bancaria: <b><span id=numero_cuentaD></span>?</p></b>
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
                window.location = 'cuentabancariacopia';
            }
        });
    </script>
<?php endif; ?>


<script src="vista/dist/js/modulos-js/cuentabancariacopia.js"></script>

</section>
</div>
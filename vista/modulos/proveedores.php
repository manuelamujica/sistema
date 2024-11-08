<?php require_once 'controlador/proveedores.php'; ?>


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Proveedores</h1>
                </div>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalProv">Registrar proveedor</button>

                        </div>
                        <div class="card-body">

                            <!-- Tabla de proveedores -->
                            <div class="table-responsive">
                                <table id="proveedores" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Rif</th>
                                            <th>Razon social</th>
                                            <th>Correo electronico</th>
                                            <th>Dirección</th>
                                            <th>Telefonos</th>
                                            <th>Status</th>

                                            <th>Representante</th>

                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registro as $datos) { ?>
                                            <?php if ($datos['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $datos["cod_prov"] ?></td>
                                                    <td><?php echo $datos["rif"] ?></td>
                                                    <td><?php echo $datos["razon_social"] ?></td>
                                                    <td><?php echo $datos["email"] ?></td>
                                                    <td><?php echo $datos["direccion"] ?></td>



                                                    <!-- regidtro de telefono del proveeed --------------
                                                    ---------------------------------------------->
                                                    <td>
                                                        <button name="telef" class="btn btn-outline-primary telef" title="telef" data-toggle="modal" data-target="#myModalt"
                                                            data-cod1="<?php echo $datos['cod_prov']; ?>" data-rif="<?php echo $datos['rif']; ?>">
                                                            <i class="fa fa-phone"></i>
                                                            <?php echo $datos["telefonos"]; ?>
                                                        </button>


                                                    </td>

                                                    <td>
                                                        <?php if ($datos['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>


                                                    <!-- --------------------------------------------
                                                    regidtro de solo el nombre del representante proveeedor
                                                --------------------------------------------------------->
                                                    <td>
                                                        <button name="mas" class="btn btn-sm btn-primary mas" title="Registrar" data-toggle="modal" data-target="#myModal"
                                                            data-cod1="<?php echo $datos['cod_prov']; ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </button>



                                                        <!------------------------------------------
                                                        mostrar representante del proveeedo
                                                        ---------------------------------------------->
                                                        <button name="mostrar" class="btn btn-outline-info btn-sm  mostrar" title="consultar" data-toggle="modal" data-target="#myModalr"
                                                            data-cod="<?php echo $datos["cod_representante"]; ?>"

                                                            data-cedula="<?php echo $datos['cedula']; ?>"
                                                            data-nombre="<?php echo $datos['nombre']; ?>"
                                                            data-apellido="<?php echo $datos['apellido']; ?>"
                                                            data-teler="<?php echo $datos['rep_tel']; ?>"
                                                            data-status1="<?php echo $datos['statusr']; ?>">

                                                            <i class="fa fa-user"></i>
                                                            <?php echo $datos['nombre']; ?>
                                                        </button>
                                                    </td>


                                                    <!------------------------------------------
                                                         editar proveedoores
                                                         ----------------------------------------------->
                                                    <td>

                                                        <button name="editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#modalProvedit" title="editar"
                                                            data-cod="<?php echo $datos["cod_prov"]; ?>"
                                                            data-rif="<?php echo $datos['rif']; ?>"
                                                            data-razon="<?php echo $datos["razon_social"]; ?>"
                                                            data-email="<?php echo $datos['email']; ?>"
                                                            data-dire="<?php echo $datos['direccion']; ?>"
                                                            data-status="<?php echo $datos['status']; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>

                                                        <!------------------------------------------
                                                         eliminar proveedoores
                                                         ----------------------------------------------->
                                                        <button name="eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" id="eliminar" data-target="#Modale" title="eliminar"
                                                            data-codigo="<?php echo $datos["cod_prov"]; ?>"
                                                            data-rif="<?php echo $datos["rif"]; ?>">

                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>


                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!--------------------------------------- 
REGISTRO DEL PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="modalProv" tabindex="-1" role="dialog" aria-labelledby="provModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModalLabel">Registrar proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedores" method="post">
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">Rif</label>
                                        <input type="text" class="form-control" id="rif" name="rif" placeholder="Ingrese el Rif" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon_social">Razon Social</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingrese Razon Social" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electronico:</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese  Correo Electronico" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Direccion:</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder=" Ingrese Direccion" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!--------------------------------------- 
FIN REGISTRO DEL PROVEEDOR
 
--------------------------------------------->



<!--------------------------------------- 
REGISTRO DEL  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Registrar representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cod_prov" id="cod_oculto" readonly>
                <form action="index.php?pagina=representantes" method="post">
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod1" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ingrese Cédula" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese Nombre" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese Apellido" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese Teléfono" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="ok">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>


<!--------------------------------------- 
FIN REGISTRO DEL  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->

<!--------------------------------------- 
MOSTRAR MODAL DEL  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="myModalr" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Datos Del representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cod_prov" id="cod_oculto" readonly>
                <form action="index.php?pagina=representantes" method="post">
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" value="<?php echo $datos['cedula'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="rep_tel" name="telefono" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Estatus</label>
                                    <select name="status" id="statusr" class="form-control" readonly>
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <td>
                                <button type="button" name="modificar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#modalProveditr" title="editar"
                                    data-codigo="<?php echo $datos['cod_representante']; ?>"
                                    data-cedula="<?php echo $datos['cedula']; ?>"
                                    data-nombre="<?php echo $datos['nombre']; ?>"
                                    data-apellido="<?php echo $datos['apellido']; ?>"
                                    data-teler="<?php echo $datos["rep_tel"]; ?>"
                                    data-status1="<?php echo $datos['statusr']; ?>">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" name="eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#Modalel" title="eliminar"
                                    data-cod="<?php echo $datos['cod_representante']; ?>"
                                    data-nombre="<?php echo $datos['nombre']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>


                            </td>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------- 
FIN MOSTRAR MODAL DEL  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->


<!--------------------------------------- 
EDITAR REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="modalProveditr" tabindex="-1" role="dialog" aria-labelledby="provModaledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModaleditr">Editar representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?pagina=representantes" id="formrepresentantesedit" method="post">
                    <input type="hidden" name="cod_representante" id="cod_oculto" readonly>


                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="codigo" id="codigo" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula3" name="cedula" required>
                                        <input type="hidden" class="form-control" id="origin" name="origin" value="<?php echo $datos['cedula']; ?>">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre3" name="nombre" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido3" name="apellido" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="reptel" name="reptel" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Estatus</label>
                                        <select name="status" id="status1" class="form-control" required>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" id="editButton" disabled name="editarr">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>


<!--------------------------------------- 
FIN EDITAR REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->

<!--------------------------------------- 
ELIMINAR  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="Modalel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?pagina=representantes" id="formrepresentreselim" method="post">
                    <p>¿Desea eliminar al representante <span id="repreNombre"></span>?</p>
                    <input type="hidden" id="reprCodigo" name="reprCodigo">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" name="eliminar" id="confirmDeletettt" class="btn btn-danger" value="eliminar">Sí</button>
                </form> <!-- Cerrando el form aquí -->
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!--------------------------------------- 
 FIN ELIMINAR  REPRESENTANTE DEL PROVEEDOR
 
--------------------------------------------->
<!-- TODO LO DE REPRESENTANTE-->


<!--------------------------------------- 
REGISTRO DEL TELEFONO PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="myModalt" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Registrar teléfono</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTelefono" action="index.php?pagina=tproveedores" method="post">
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod1">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod1" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">RIF:</label>
                                        <input type="text" class="form-control" id="rif" name="rif" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono2">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono2" name="telefono" placeholder="Ingrese Teléfono" required>
                                        <div class="invalid-feedback" style="color: red; "></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="okk">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!--------------------------------------- 
FIN REGISTRO DEL TELEFONO ROVEEDOR
 
--------------------------------------------->

<!--------------------------------------- 
EDITAR  PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="modalProvedit" tabindex="-1" role="dialog" aria-labelledby="provModaledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModaledit">Editar proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedoresedit" method="post">
                    <input type="hidden" name="cod_prov" id="cod_oculto" value="<?php echo $datos['cod_prov']; ?>" readonly>


                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">RIF</label>
                                        <input type="text" class="form-control" id="rif1" name="rif" required>
                                        <input type="hidden" class="form-control" id="origin" name="origin" value="<?php echo $datos['rif']; ?>">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon">Razón Social</label>
                                        <input type="text" class="form-control" id="razon1" name="razon_social" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="email1" name="email" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dire">Dirección:</label>
                                        <input type="text" class="form-control" id="dire1" name="direccion">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Estatus</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                             
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" id="editButtonn" disabled name="editar">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>

<!--------------------------------------- 
EDITAR  PROVEEDOR
 
--------------------------------------------->




<!--------------------------------------- 
ELIMINAR PROVEEDOR
 
--------------------------------------------->
<div class="modal fade" id="Modale" tabindex="-1" role="dialog" aria-labelledby="exampaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampaleModalLabel">Confirmar Eliminar </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedoreselim" method="post">
                    <p>¿Desea eliminar a el proveedor ?</p>
                    <input type="hidden" id="provCodigo" name="provCodigo">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger" id="confirmDelete" name="eliminar">Sí</button>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>

<!--------------------------------------- 
ELIMINAR PROVEEDOR
 
--------------------------------------------->






<!-- EDITAR proveedorre-->

<script>
    $('#modalProvedit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var cod = button.data('cod');
        var rif = button.data('rif');
        var razon = button.data('razon');
        var email = button.data('email');
        var dire = button.data('dire');
        var status = button.data('status');
        var telefono = button.data('telefono');
        var origin = button.data('rif');
        var cod_tlf = button.data('cod_tlf');

        // Modal
        var modal = $(this);
        modal.find('.modal-body #cod').val(cod);
        modal.find('.modal-body #rif1').val(rif);
        modal.find('.modal-body #razon1').val(razon);
        modal.find('.modal-body #email1').val(email);
        modal.find('.modal-body #dire1').val(dire);
        modal.find('.modal-body #status').val(status);
        modal.find('.modal-body #telefono3').val(telefono);
        modal.find('.modal-body #origin').val(origin);
        modal.find('.modal-body #cod_tlf').val(cod_tlf);
    });
</script>
<!-- Modal de extrae  representante -->

<script>
    $(document).ready(function() {
        $('#myModalr').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var cod = button.data('cod');
            var nombre = button.data('nombre');
            var cedula = button.data('cedula');
            var apellido = button.data('apellido');
            var telefono = button.data('teler');
            var status1 = button.data('status1');


            // Modal
            var modal = $(this);
            modal.find('.modal-body #cod').val(cod);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #cedula').val(cedula);
            modal.find('.modal-body #apellido').val(apellido);
            modal.find('.modal-body #rep_tel').val(telefono);
            modal.find('.modal-body #statusr').val(status1);

            $('#modalProveditr').on('show.bs.modal', function(event) {

                var modal = $(this);
                modal.find('.modal-body #codigo').val(cod);
                modal.find('.modal-body #nombre3').val(nombre);
                modal.find('.modal-body #cedula3').val(cedula);
                modal.find('.modal-body #apellido3').val(apellido);
                modal.find('.modal-body #reptel').val(telefono);
                modal.find('.modal-body #status1').val(status1);
                modal.find('.modal-body #origin').val(origin);
                modal.find('.modal-body #cod_oculto').val(cod);

            });

            $('#Modalel').on('show.bs.modal', function(event) {

                var modal = $(this);
                modal.find('#repreNombre').text(nombre);
                modal.find('#reprCodigo').val(cod);

            });



            var codigo = button.data('codigo');
            var nombre = button.data('nombre');
            var cedula = button.data('cedula');
            var apellido = button.data('apellido');
            var tele = button.data('telefono');
            var status1 = button.data('status1');
            var origin = button.data('cedula');
            // Modal
        });

    })
</script>



<!-- Modal de editar esta junto com mostrar representante -->



<script>
    //validar tu  refistro de proveedores 
    $(document).ready(function() {

        $('#rif').on('blur', function() {
            var rif = $(this).val();
            if (rif.trim() === '') {
                showError('#rif', 'El campo RIF no puede estar vacío');
            } else if (!/^[a-zA-Z0-9\s\-\.\/]{6,12}$/.test(rif)) {
                showError('#rif', 'El RIF debe tener entre 6 y 12 caracteres, incluyendo letras, números .');
            } else {
                hideError('#rif');
            }
        });

        $('#razon_social').on('blur', function() {
            var razon_social = $(this).val();
            if (razon_social.trim() === '') {
                showError('#razon_social', 'El campo razón social no puede estar vacío');
            } else if (!/^[a-zA-Z0-9\s\-\.\/]{6,30}$/.test(razon_social)) {
                showError('#razon_social', 'La razón social debe tener entre 6 y 30 caracteres, incluyendo letras.');
            } else {
                hideError('#razon_social');
            }
        });

        $('#direccion').on('blur', function() {
            var direccion = $(this).val();
            if (direccion.trim() === '') {
                showError('#direccion', 'El campo dirección no puede estar vacío');
            } else if (direccion.length < 6 || direccion.length > 30) {
                showError('#direccion', 'La dirección debe tener entre 6 y 30 caracteres.');
            } else {
                hideError('#direccion');
            }
        });

        $('#email').on('blur', function() {
            var email = $(this).val();
            if (email.trim() === '') {
                showError('#email', 'El campo email no puede estar vacío');
            } else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email) || email.length < 10 || email.length > 40) {
                showError('#email', 'El email debe ser válido y tener entre 10 y 40 caracteres.');
            } else {
                hideError('#email');
            }
        });

        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });



    // refistro de reprsentante  -->

    $(document).ready(function() {
        // Validación de Cédula
        $('#cedula').on('blur', function() {
            var cedula = $(this).val();
            // Permite de 5 a 12 caracteres (números y caracteres especiales permitidos)
            if (!/^[\d\s().\-\/]{5,12}$/.test(cedula)) {
                showError('#cedula', 'La cédula debe tener solo números entre 5 y 12 caracteres');
            } else {
                hideError('#cedula');
            }
        });

        // Validación de Nombre
        $('#nombre').on('blur', function() {
            var nombre = $(this).val();
            // Longitud mínima de 4 y máxima de 20 (solo letras y espacios)
            if (!/^[a-zA-Z\s]{4,20}$/.test(nombre)) {
                showError('#nombre', 'El nombre debe tener solo letras y entre 4 y 20 caracteres');
            } else {
                hideError('#nombre');
            }
        });

        // Validación de Apellido
        $('#apellido').on('blur', function() {
            var apellido = $(this).val();
            // Longitud mínima de 4 y máxima de 20 (solo letras y espacios)
            if (!/^[a-zA-Z\s]{4,20}$/.test(apellido)) {
                showError('#apellido', 'El apellido debe tener solo letras y entre 4 y 20 caracteres');
            } else {
                hideError('#apellido');
            }
        });

        // Validación de Teléfono
        $('#telefono').on('change', function() {
            var telefono = $(this).val();
            // Permite entre 6 y 12 caracteres (números y caracteres especiales permitidos)
            if (!/^[\d\s().\-\/]{6,12}$/.test(telefono)) {
                showError('#telefono', 'El teléfono debe tener solo números entre 6 y 12 caracteres');
            } else {
                hideError('#telefono');
            }
        });

        // Función para mostrar el error
        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toLowerCase()).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        // Función para ocultar el error
        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });



    // validar  refistro de reprsentante  -->

    // validar  telefono de proveedor   -->

    $(document).ready(function() {
        $('#telefono2').on('blur', function() {
            var telefono = $('#telefono2').val().trim();
            // Limpiar el mensaje de error
            $('.invalid-feedback').text('').hide();
            $('#telefono2').removeClass('is-invalid');

            // Validar que el teléfono no esté vacío
            if (telefono === '') {
                $('#telefono2').addClass('is-invalid');
                $('.invalid-feedback').text('El teléfono es requerido').show();
            } else if (!/^\d+$/.test(telefono)) {
                $('#telefono2').addClass('is-invalid');
                $('.invalid-feedback').text('El teléfono solo puede contener números.').show();
            } else if (telefono.length < 10 || telefono.length > 15) {
                $('#telefono2').addClass('is-invalid');
                $('.invalid-feedback').text('El teléfono debe tener entre 10 y 15 caracteres').show();
            } else {
                $('#telefono2').removeClass('is-invalid');
                $('.invalid-feedback').hide();
            }
        });
    });



    //validar  telefono de proveedor   -->


    //validar  editar proveedor   -->

    $(document).ready(function() {
        // Función para validar los campos
        function validarField(campo, regex, mensaje, minLength, maxLength) {
            var valor = campo.val().trim();
            if (valor === '') {
                showError(campo, 'Este campo no puede estar vacío');
            } else if (valor.length < minLength || valor.length > maxLength) {
                showError(campo, mensaje);
            } else if (!regex.test(valor)) {
                showError(campo, mensaje);
            } else {
                hideError(campo);
            }
            toggleEditButton();
        }

        // Función para mostrar el error
        function showError(selector, mensaje) {
            selector.addClass('is-invalid');
            selector.next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + mensaje).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'transparent'
            });
        }


        function hideError(selector) {
            selector.removeClass('is-invalid');
            selector.next('.invalid-feedback').css('display', 'none');
        }

        // Función para habilitar o deshabilitar el botón de editar
        function toggleEditButton() {
            var isRIFValid = $('#rif1').val().length > 0 && $('#rif1').val().length <= 15;
            var isRazonValid = $('#razon1').val().length > 0 && $('#razon1').val().length <= 30;
            var isEmailValid = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test($('#email1').val());
        

            // Habilitar el botón solo si todas las validaciones son verdaderas
            if (isRIFValid && isRazonValid && isEmailValid) {
                $('#editButtonn').prop('disabled', false);
            } else {
                $('#editButtonn').prop('disabled', true);
            }
        }

        $('#rif1').on('blur', function() {
            validarField($(this), /^[a-zA-Z0-9]+$/, 'El RIF debe contener entre 1 y 15 caracteres (letras y números)', 1, 15);
        });

        $('#razon1').on('blur', function() {
            validarField($(this), /^[a-zA-Z0-9\s\.,]+$/, 'La razón social debe contener entre 1 y 30 caracteres (letras, números y signos permitidos)', 1, 30);
        });

        $('#email1').on('blur', function() {
            validarField($(this), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, 'El campo email debe ser un email válido', 1, 100);
        });

      

        $('#dire1').on('blur', function() {
            validarField($(this), /^[a-zA-Z0-9\s\.,]*$/, 'La dirección solo debe contener letras, números y signos permitidos', 0, 100);
        });
    });
</script>
<!-- validar  editar proveedor   -->







<script src="vista/dist/js/modulos-js/proveedores.js"> </script>
<script src="vista/dist/js/modulos-js/representantes.js"> </script>

<script>
    $(document).ready(function() {
        // Función para validar Cédula representante
        function validateCedula() {
            var cedula = $('#cedula3').val();
            if (!cedula || !/^[\d\s()+-]*$/.test(cedula) || cedula.length < 5 || cedula.length > 12) {
                showError('#cedula3', 'Debe contener entre 5 y 12 caracteres, solo números y signos permitidos');
                return false;
            } else {
                hideError('#cedula3');
                return true;
            }
        }

        // Función para validar Nombre
        function validateNombre() {
            var nombre = $('#nombre3').val();
            if (!nombre || !/^[a-zA-Z\s]+$/.test(nombre) || nombre.length < 4 || nombre.length > 20) {
                showError('#nombre3', 'Debe contener entre 4 y 20 letras y no estar vacío');
                return false;
            } else {
                hideError('#nombre3');
                return true;
            }
        }

        // Función para validar Apellido
        function validateApellido() {
            var apellido = $('#apellido3').val();
            if (!apellido || !/^[a-zA-Z\s]+$/.test(apellido) || apellido.length < 4 || apellido.length > 20) {
                showError('#apellido3', 'Debe contener entre 4 y 20 letras y no estar vacío');
                return false;
            } else {
                hideError('#apellido3');
                return true;
            }
        }

        // Función para validar Teléfono
        function validateTelefono() {
            var telefono = $('#reptel').val();
            if (!telefono || !/^[\d\s()+-]*$/.test(telefono) || telefono.length < 6 || telefono.length > 12) {
                showError('#reptel', 'Debe contener entre 6 y 12 caracteres, solo números y signos permitidos');
                return false;
            } else {
                hideError('#reptel');
                return true;
            }
        }

        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toLowerCase()).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }

        // Función para habilitar o deshabilitar el botón de editar
        function toggleEditButton() {
            var isCedulaValid = validateCedula();
            var isNombreValid = validateNombre();
            var isApellidoValid = validateApellido();
            var isTelefonoValid = validateTelefono();

            // Habilitar el botón solo si todas las validaciones son verdaderas
            $('#editButton').prop('disabled', !(isCedulaValid && isNombreValid && isApellidoValid && isTelefonoValid));
        }

        // Asignar eventos de blur para validar al salir del campo
        $('#cedula3').on('blur', toggleEditButton);
        $('#nombre3').on('blur', toggleEditButton);
        $('#apellido3').on('blur', toggleEditButton);
        $('#reptel').on('blur', toggleEditButton);
    });
</script>
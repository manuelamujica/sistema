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
                                                    <td><?php echo !empty($datos["email"]) ? $datos["email"] : 'No disponible'; ?></td>
                                                    <td><?php echo !empty($datos["direccion"]) ? $datos["direccion"] : 'No disponible'; ?></td>
                                                    <!-- REGISTRO TELEFONO PROVEEDOR -->
                                                    <td>
                                                        <button name="telef" class="btn btn-outline-primary telef" title="telef" data-toggle="modal" data-target="#myModalt"
                                                            data-cod1="<?php echo $datos['cod_prov']; ?>"
                                                            data-rif="<?php echo $datos['rif']; ?>">
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
                                                    <!-- BOTON REGISTRAR REPRESENTANTE -->
                                                    <td>
                                                        <button name="mas" class="btn btn-sm btn-primary mas" title="Registrar" data-toggle="modal" data-target="#registrarRepresentante"
                                                            data-codigoproveedor="<?php echo $datos['cod_prov']; ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    <!-- BOTON REGISTRAR REPRESENTANTE -->
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
                                                <!-- BOTON EDITAR PROVEEDOR -->
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
                                                    <!-- BOTON ELIMINAR PROVEEDORES -->
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
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese  Correo Electronico">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Direccion:</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder=" Ingrese Direccion">
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
REGISTRO DEL REPRESENTANTE DEL PROVEEDOR
--------------------------------------------->

<div class="modal fade" id="registrarRepresentante" tabindex="-1" role="dialog" aria-labelledby="registrarRepresentanteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="registrarRepresentanteLabel">Registrar representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?pagina=representantes" method="post">
                    <input type="hidden" name="cod_provREPRE" id="cod_provREPRE">
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
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese Apellido" >
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese Teléfono" >
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="ok">Guardar</button>
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


<!-------------------------------------------
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
                                        <input type="text" class="form-control" id="apellido3" name="apellido" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="reptel" name="reptel" >
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Estatus</label>
                                        <select name="status" id="status1" class="form-control" >
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
ELIMINAR  REPRESENTANTE DEL PROVEEDOR acomodar 
--------------------------------------------->
<div class="modal fade" id="Modalel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?pagina=representantes" id="formrepresentreselim" method="post">
                    <p>¿Desea eliminar al representante <b><span id="repreNombre"></span>?</b></p>
                    <input type="hidden" id="reprCodigo" name="reprCodigo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" name="eliminar" id="confirmDeletettt" class="btn btn-danger" value="eliminar">Sí</button>
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
                                        <input type="email" class="form-control" id="email1" name="email" >
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
                                        <label for="status">Status</label>
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
ELIMINAR PROVEEDOR acomodar 
--------------------------------------------->
<div class="modal fade" id="Modale" tabindex="-1" role="dialog" aria-labelledby="exampaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampaleModalLabel">Confirmar Eliminar </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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

<script src="vista/dist/js/modulos-js/proveedores.js"> </script>

<?php require_once 'controlador/general.php' ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Ajustar información de la empresa</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Información</li>
                </ol>
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
            <button class="btn btn-primary" data-toggle="modal" id="registrar" data-target="#modalregistrarempresa">Registrar Información</button>
            </div>
            <div class="card-body">
                    <?php foreach($datos as $dato): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo $dato['nombre']; ?></h5>
                            </div>
                            <div class="card-body">
                                <p><b>Rif: </b> <?php echo $dato['rif']; ?></p>
                                <p><b>Razón Social: </b><?php echo $dato['nombre']; ?></p>
                                <p><b>Dirección: </b><?php echo $dato['direccion']; ?></p>
                                <p><b>Teléfono: </b><?php echo $dato['telefono']; ?></p>
                                <p><b>Email: </b><?php echo $dato['email']; ?></p>
                                <p><b>Descripción: </b><?php echo $dato['descripcion']; ?></p>
                                <p><b>Logo: </b><img src="<?php echo $dato['logo']; ?>" alt="quesera don pedro"></p>
                            </div>
                            <div class="card-footer">
                            <button name="ajustar" class="btn btn-primary btn-sm editar" data-toggle="modal" id="modificar" data-target="#modalmodificarempresa" value="<?php echo $dato['rif']; ?>">
                                        <i class="fas fa-pencil-alt" title="Editar"></i>
                                    </button>

                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
<!-- =======================
MODAL REGISTRAR INFO GENERAL 
============================= -->

        <div class="modal fade" id="modalregistrarempresa" tabindex="-1" aria-labelledby="modalregistrarempresaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar informacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="formGeneral" method="post" enctype="multipart/form-data">
                            <!--   RIF DE LA empresa     -->
                            <div class="form-group">
                                <label for="rif">Rif de la empresa</label>
                                <input type="text" class="form-control" name="rif" required>
                            </div>
                            <!--   NOMBRE DE LA empresa     -->
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <!--   DIRECCION     -->
                            <div class="form-group">
                                <label for="direccion">Direccion</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                            <!--   TELEFONO     -->
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="tel" class="form-control" name="telefono">
                            </div>
                            <!--   EMAIL     -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <!--   DESCRIPCION    -->
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control" name="descripcion" required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Ingrese el logo</label>
                                <input type="file" class="form-control" name="logo" id="logo" required>
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
    </section>
</div>

<!--      MODAL DE MODIFICACIÓN             --->
<div class="modal fade" id="modalmodificarempresa" tabindex="-1" aria-labelledby="modalmodificarempresaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar informacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!--    QUE PROBLEMAS TIENEN CONMIGO!!!! VARIABLE INDEFINIDA!!!!!!!!!!!!!!!!         -->
                        <form id="Generaleditar" method="post">
                            <input type="hidden" name="rif" value="<?php echo $dato['rif'] ?>">
                            <!--   RIF DE LA empresa     -->
                            <div class="form-group">
                                <label for="rif">Rif de la empresa</label>
                                <input type="text" class="form-control" name="rif" value="<?php echo $dato['rif'] ?>" readonly>
                            </div>
                            <!--   NOMBRE DE LA empresa     -->
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo $dato['nombre'] ?>">
                            </div>
                            <!--   DIRECCION     -->
                            <div class="form-group">
                                <label for="direccion">Direccion</label>
                                <input type="text" class="form-control" name="direccion" value="<?php echo $dato['direccion'] ?>">
                            </div>
                            <!--   TELEFONO     -->
                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="tel" class="form-control" name="telefono" value="<?php echo $dato['telefono'] ?>">
                            </div>
                            <!--   EMAIL     -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $dato['email'] ?>">
                            </div>
                            <!--   DESCRIPCION    -->
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control" name="descripcion" value="<?php echo $dato['descripcion'] ?>">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="editar">Editar</button>
                            <div class="modal-footer justify-content-between">-->
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="editar" value="<?php echo $dato['rif']; ?>">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!--    FIN DEL MODAL DE MODIFICAR         -->

<!--     VALIDACIÓN        -->
<script src="vista/dist/js/modulos-js/general.js"></script>
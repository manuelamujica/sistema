<?php require_once 'controlador/unidad.php' ?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Unidades de medida</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Unidades de medida</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarUnidad">Registrar Unidad de medida</button>
            </div>
            <div class="card-body">
                <!-- MOSTRAR EL REGISTRO DE UNIDADES DE MEDIDA -->
            <div class="table-responsive">
            <table id="unidad" class="table table-bordered table-striped">
                <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo de medida</th>
                            <th>Presentación</th>
                            <th>Cantidad de la presentación</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($datos as $dato){
                    ?>
                    <tr>
                        <td><?php echo $dato['cod_unidad']?></td>
                        <td><?php echo $dato['tipo_medida']?></td>
                        <td><?php echo $dato['presentacion']?></td>
                        <td><?php echo $dato['cantidad_presentacion']?></td>
                        <td>
                            <?php if ($dato['status']==1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST">
                                <button name="ajustar" class="btn btn-primary btn-sm editar" title="Editar" value="<?php echo $dato['cod_unidad']?>"><i class="fas fa-pencil-alt"></i></button>
                                <button name="eliminar" class="btn btn-danger btn-sm eliminar" title="Eliminar" value="<?php echo $dato['cod_unidad']?>"><i class="fas fa-trash-alt"></i></button><br></div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </div>
            </table>
        </div>
    </div>

<!-- =======================
MODAL REGISTRAR Unidades de medida 
============================= -->

        <div class="modal fade" id="modalregistrarUnidad" tabindex="-1" aria-labelledby="modalregistrarUnidadLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar Unidad de medida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="formregistrarUnidad" method="post">
                            <!--   TIPO DE MEDIDA      -->
                            <div class="form-group">
                                <label for="tipo_medida">Tipo de medida</label>
                                <input type="text" class="form-control" name="tipo_medida" required>
                            </div>
                            <!--   PRESENTACIÓN      -->
                            <div class="form-group">
                                <label for="presentacion">Presentación</label>
                                <input type="text" class="form-control" id="presentacion" name="presentacion" required>
                            </div>
                            <!--   CANTIDAD DE PRESENTACIÓN      -->
                            <div class="form-group">
                                <label for="cantidad_presentacion">Cantidad en la presentación</label>
                                <input type="text" class="form-control"  name="cantidad_presentacion" required>
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
<script>
    $('#presentacion').blur(function (e){
        var buscar=$('#presentacion').val();
        $.post('index.php?pagina=unidad', {buscar}, function(response){
        if(response != ''){
            alert('La presentacion ya se encuentra registrada');
        }
        },'json');
    });
</script>
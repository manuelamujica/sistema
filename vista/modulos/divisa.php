<?php require_once 'controlador/divisa.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Gestión de Divisas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Divisas</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarDivisa">
                                    Registrar Divisa
                                </button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                            <div class="table-responsive">
                                <table id="paymentTypesTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre</th>
                                            <th>Símbolo/Abreviatura</th>
                                            <th>Status</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se llenará la tabla dinámicamente con PHP -->
                                        <?php 
                                    
                                        foreach ($consulta as $divisa) {
                                        ?>
                                        <tr>
                                            <td><?php echo $divisa['cod_divisa']?></td>
                                            <td><?php echo $divisa['nombre']?></td>
                                            <td><?php echo $divisa['abreviatura'] ?></td>
                                            <td>
                                                <?php if ($divisa['status']==1):?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else:?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                            <form method="POST">
                                            <button name="modificar" title="Editar" class="btn btn-primary btn-sm editar" value="<?php echo $dato['cod_divisa']; ?>">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['cod_divisa']; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    <!-- registrar Divisa Modal -->
<div class="modal fade" id="modalregistrarDivisa">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                <h4 class="modal-title">Registrar Divisa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form role="form" method="post">
                <div class="modal-body">
                    <div class="form-group">
                    <label for="nombre">Nombre de la Divisa</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                    <label for="simbolo">Símbolo o Abreviatura</label>
                    <input type="text" class="form-control" id="simbolo" name="simbolo" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="registrar">Guardar</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
<script>
    $('#nombre').blur(function (e){
        var buscar=$('#nombre').val();
        $.post('index.php?pagina=divisa', {buscar}, function(response){
        if(response != ''){
            alert('La divisa ya se encuentra registrada');
        }
        },'json');
    });
</script>
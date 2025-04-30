
<?php require_once 'controlador/tipocuenta.php'; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tipo de Cuenta</h1>
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
        
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                        <table id="tipocuenta" class="table table-bordered table-striped table-hover" style="width: 100%;">

        
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Tipo de Cuenta</th>
                                    </tr>         
                                </thead>
                                <tbody>
                                <!-- Tabla con los datos que se muestren dinamicamente -->
                                    <?php foreach ($registro as $tipo_cuenta) { ?>
                               <tr>
                                    <td><?php echo $tipo_cuenta["cod_tipo_cuenta"]; ?></td>
                                 <td><?php echo $tipo_cuenta["nombre"]; ?></td>
                                </tr>
                                <?php } ?>


                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
    </div>      
    </section>
</div>


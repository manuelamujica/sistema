<?php require_once 'controlador/bitacora.php'; ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bitacora</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="carga" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Acción</th>
                                            <th>Modulo</th>
                                            <th>Detalles</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bitacora as $dato): ?>
                                            <?php if ($dato['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $dato['nombre']; ?></td>
                                                    <td><?php echo $dato['accion']; ?></td>
                                                    <td><?php echo $dato['modulo']; ?></td>
                                                    <td><?php echo $dato['detalles']; ?></td>
                                                    <td><?php echo date("d-m-Y H:i:s", strtotime($dato['fecha'])); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
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
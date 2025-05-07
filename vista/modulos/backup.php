<?php 
require_once "controlador/backup.php"; 
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h1>Copias de Seguridad</h1>
                    <p>En esta sección puedes generar y configurar copias automáticas de seguridad.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalConfigBackup">
                        Configurar respaldo automático
                    </button>
                    <form method="POST" class="d-inline">
                        <button name="generar_respaldo" class="btn btn-primary">
                            Generar respaldo manual
                        </button>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaRespaldos" class="table table-bordered table-striped datatable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Archivo</th>
                                    <th>Tamaño (MB)</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>         
                            </thead>
                            <tbody>
                                <?php foreach ($respaldos as $i => $r): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><a href="<?= $r['ruta_archivo'] ?>" download><?= $r['nombre_archivo'] ?></a></td>
                                        <td><?= $r['fecha_creacion'] ?></td>
                                        <td><?= round($r['tamano_archivo'], 2) ?></td>
                                        <td>
                                            <span class="badge <?= $r['tipo'] === 'manual' ? 'bg-info' : 'bg-secondary' ?>">
                                                <?= ucfirst($r['tipo']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id_respaldo" value="<?= $r['id_respaldo'] ?>">
                                                <button name="eliminar_respaldo" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

<!-- =============================
    MODAL CONFIGURAR RESPALDO 
============================= -->
<div class="modal fade" id="modalConfigBackup" tabindex="-1" aria-labelledby="modalConfigBackupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="formConfigBackup">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Configuración automática de respaldo</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-4">
                        <label for="frecuencia">Frecuencia</label>
                        <select name="frecuencia" class="form-control" required>
                            <option value="diaria" <?= $config['frecuencia'] == 'diaria' ? 'selected' : '' ?>>Diaria</option>
                            <option value="semanal" <?= $config['frecuencia'] == 'semanal' ? 'selected' : '' ?>>Semanal</option>
                            <option value="quincenal" <?= $config['frecuencia'] == 'quincenal' ? 'selected' : '' ?>>Quincenal</option>
                            <option value="mensual" <?= $config['frecuencia'] == 'mensual' ? 'selected' : '' ?>>Mensual</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dia">Día</label>
                        <input type="text" name="dia" class="form-control" value="<?= $config['dia'] ?? '' ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="hora">Hora</label>
                        <input type="time" name="hora" class="form-control" value="<?= $config['hora'] ?? '20:00' ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="retencion">Retención máxima</label>
                        <!-- TOOLTIPS-->
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Define las politicas de retención de los respaldos. Por defecto se guardan 10 respaldos.">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="number" name="retencion" class="form-control" value="<?= $config['retencion'] ?? 10 ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>¿Activar respaldo automático?</label><br>
                        <!-- TOOLTIPS-->
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Permite activar o desactivar el respaldo automático. Puedes configurar y decidir si la activas o no.">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="checkbox" name="habilitado" data-bootstrap-switch <?= $config['habilitado'] ? 'checked' : '' ?>>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar_config" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

        </div>
    </section>
</div>

<script>
    $document.ready(function(){
        $(function () {
    $('#tablaRespaldos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
    $("input[data-bootstrap-switch]").each(function () {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});
    });

</script>

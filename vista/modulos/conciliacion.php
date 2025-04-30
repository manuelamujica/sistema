
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Conciliación bancaria</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Subir extracto</h3>
                        </div>
                        <div class="card-body">
                            <form id="formSubirExtracto" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="pdfFile">Seleccionar archivo PDF</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="pdfFile" name="pdfFile" accept="application/pdf" required>
                                            <label class="custom-file-label" for="pdfFile">Buscar archivo...</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Solo se aceptan archivos en formato PDF</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cuentaBancaria">Cuenta bancaria asociada</label>
                                    <select class="form-control" id="cuentaBancaria" name="cuentaBancaria" required>
                                        <option value="" selected disabled>Seleccione una cuenta</option>
                                        <?php foreach($cuentas as $cuenta): ?>
                                            <option value="<?= $cuenta['cod_cuenta_bancaria'] ?>">
                                                <?= $cuenta['nombre_banco'] ?> - <?= $cuenta['numero_cuenta'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="formSubirExtracto" class="btn btn-primary">
                                <i class="fas fa-upload mr-2"></i> Subir extracto
                            </button>
                        </div>
                    </div>
                    
                    <?php if (!empty($transactions)): ?>
                    <div class="card card-success mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Transacciones detectadas</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">#</th>
                                            <th>Referencia</th>
                                            <th style="width: 20%">Monto</th>
                                            <th style="width: 15%">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transactions as $index => $transaction): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $transaction['Referencia'] ?></td>
                                            <td><?= number_format($transaction['Monto'], 2, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $transaction['Tipo'] == 'CREDITO' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $transaction['Tipo'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success" id="btnConfirmarImportacion">
                                <i class="fas fa-check mr-2"></i> Confirmar importación
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Preloader-->
<div class="preloader flex-column justify-content-center align-items-center">
<?php 
        if(isset($_SESSION["logo"])): ?>
            <img src="<?php echo $_SESSION["logo"];?>" alt="Quesera Don Pedro" class="" height="200" width="200">
        <?php else: ?>
            <img src="vista/dist/img/logo_generico.png" alt="Quesera Don Pedro"  class="" height="200" width="200">
        <?php endif; ?>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Inicio</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
        <h3 class="card-title">¡BIENVENIDOS!</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
            </button>
        </div>
        </div>
        <div class="card-body">
        Sistema para la comercialización de productos
        </div>
        <!-- /.card-body -->
        </div>
    <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

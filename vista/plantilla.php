<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SAVYC</title>
  <link rel="icon" href="vista/dist/img/logo_generico.png">

<!-- ==================
PLUGINGS DE CSS
======================= -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vista/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vista/dist/css/adminlte.css">
  <link rel="stylesheet" href="vista/dist/css/custom.css">

  <!-- Date Range Picker -->
  <link rel="stylesheet" href="vista/plugins/daterangepicker/daterangepicker.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="vista/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="vista/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="vista/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- =======================
    PLUGINGS DE JAVASACRIPT  
=============================-->
  <!-- jQuery -->
  <script src="vista/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="vista/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="vista/dist/js/adminlte.min.js"></script>

  <!-- Date Range Picker -->
  <script src="vista/plugins/moment/moment.min.js"></script>
  <script src="vista/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- DataTables  & Plugins -->
<script src="vista/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="vista/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vista/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="vista/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="vista/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="vista/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="vista/plugins/jszip/jszip.min.js"></script>
<script src="vista/plugins/pdfmake/pdfmake.min.js"></script>
<script src="vista/plugins/pdfmake/vfs_fonts.js"></script>
<script src="vista/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="vista/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="vista/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Custom JS -->
<script src="vista/dist/js/custom.js"></script>
<!-- SweetAlert2 -->
<script src="vista/plugins/sweetalert2/sweetalert2.all.js"></script>
<!-- Toastr -->
<link rel="stylesheet" href="vista/plugins/toastr/toastr.min.css">
<script src="vista/plugins/toastr/toastr.min.js"></script>
<!-- ChartJS -->
<script src="vista/plugins/chart.js/Chart.min.js"></script>

</head>
<!-- =======================
    BODY
=============================-->
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
  <?php 
  if(isset($_SESSION["iniciarsesion"]) && $_SESSION["iniciarsesion"] == 'ok'){
  ?>
  <div class="wrapper">
  <?php
  include "modulos/header.php";
  include "modulos/sidebar.php";

  if(isset ($_GET["ruta"])){
    if($_GET["ruta"] == "inicio" || 
    $_GET["ruta"] == "categorias" && $_SESSION["categoria"]==1 ||
    $_GET["ruta"] == "carga" && $_SESSION["inventario"]==1 ||
    $_GET["ruta"] == "descarga" && $_SESSION["inventario"]==1 ||
    $_GET["ruta"] == "rep-inventario" && $_SESSION["reporte"]==1 ||
    $_GET["ruta"] == "rep-proveedores" && $_SESSION["reporte"]==1 ||
    $_GET["ruta"] == "rep-cliente" && $_SESSION["reporte"]==1 ||
    $_GET["ruta"] == "rep-venta" && $_SESSION["reporte"]==1 ||
    $_GET["ruta"] == "rep-compra" && $_SESSION["reporte"]==1 ||
    $_GET["ruta"] == "productos" && $_SESSION["producto"]==1 ||
    $_GET["ruta"] == "usuarios" && $_SESSION["usuario"]==1 ||
    $_GET["ruta"] == "compras" && $_SESSION["compra"]==1 ||
    $_GET ["ruta"] == "banco" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "tpago" && $_SESSION["configuracion"]==1 || 
    $_GET["ruta"] == "divisa" && $_SESSION["configuracion"]==1 || 
    $_GET["ruta"] == "proveedores" && $_SESSION["proveedor"]==1 || 
    $_GET["ruta"] == "unidad" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "general" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "clientes" && $_SESSION["cliente"]==1 ||
    $_GET["ruta"] == "roles" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "bitacora" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "caja" && $_SESSION["configuracion"]==1 ||
    $_GET["ruta"] == "venta" && $_SESSION["venta"]==1 ||
    $_GET["ruta"] == "catalogocuentas" ||
    $_GET["ruta"] == "gastos" ||
    $_GET["ruta"] == "cuentaspend" ||
    $_GET["ruta"] == "movimientos" ||
    $_GET["ruta"] == "cerrarsesion"){
      include "modulos/". $_GET["ruta"] . ".php";
    } else {
      include "modulos/inicio.php";
    }

  }else{
      include "modulos/inicio.php";
  } 
  ?>
  <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
  </aside>
  </div>
  <?php
  //include "modulos/footer.php";
  ?>
</div>
<?php
}else{
  include "modulos/login.php";
}
?>

</body>
</html>

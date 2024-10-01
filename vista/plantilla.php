<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SAVYC</title>
  <link rel="icon" href="vista/dist/img/logo-icono.png"> <!--Logo del navegador -->

<!-- ==================
PLUGINGS DE CSS
======================= -->

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="vista/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="vista/dist/css/adminlte.css">
  <link rel="stylesheet" href="vista/dist/css/custom.css">
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
    $_GET["ruta"] == "categorias" ||
    $_GET["ruta"] == "productos" ||
    $_GET["ruta"] == "usuarios" ||
    $_GET["ruta"] == "tpago" || 
    $_GET["ruta"] == "divisa" || 
    $_GET["ruta"] == "proveedores" || 
    $_GET["ruta"] == "unidad" ||
    $_GET["ruta"] == "general" ||
    $_GET["ruta"] == "clientes" ||
    $_GET["ruta"] == "roles" ||
    $_GET["ruta"] == "venta2" ||
    $_GET["ruta"] == "cerrarsesion"){
      
      include "modulos/". $_GET["ruta"] . ".php";
    } else {
      include "modulos/construccion.php";
    }

  }else{
      include "modulos/inicio.php";
  } 

  include "modulos/footer.php";
  ?>
</div>
<?php
}else{
  include "modulos/login.php";
}

?>

</body>
</html>
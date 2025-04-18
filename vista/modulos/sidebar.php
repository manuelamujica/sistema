<!-- MAIN SIDEBAR CONTAINER-->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Logo -->
    <a href="inicio" class="brand-link">
        <?php 
        if(isset($_SESSION["logo"])): ?>
            <img src="<?php echo $_SESSION["logo"];?>" alt="Quesera Don Pedro" class="brand-image img-circle elevation-3" style="opacity: .8">
        <?php else: ?>
            <img src="vista/dist/img/logo_generico.png" alt="Quesera Don Pedro" class="brand-image img-circle elevation-3" style="opacity: .8">
        <?php endif; ?>
        <span class="brand-text font-weight-bold">SAVYC</span>
    </a>
<!--=================
    MENU / sidebar
=====================-->
<?php if(isset($_SESSION["rif"]) || $_SESSION["cod_usuario"] == 1): ?>
<div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($_SESSION["producto"]==1): ?>
                <li class="nav-item">
                    <a href="productos" class="nav-link">
                        <i class="nav-icon fa fa-shopping-bag"></i>
                        <p>
                            Productos
                        </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["inventario"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link bitacora-link" data-modulo="Ajuste de INventario">
                        <i class="fas fa-dolly-flatbed nav-icon"></i>
                        <p>
                            Ajuste de Inventario<i class="right fas fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="carga" class="nav-link bitacora-link" data-modulo="Carga de productos">
                                    <i class="fas fa-sort-amount-up-alt nav-icon"></i>
                                    <p>Carga de productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="descarga" class="nav-link bitacora-link" data-modulo="Descarga de productos">
                                    <i class="fas fa-sort-amount-down-alt nav-icon"></i>
                                    <p>Descarga de productos</p>
                                </a>
                            </li>
                        </ul>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["categoria"]==1): ?>
                <li class="nav-item">
                    <a href="categorias" class="nav-link bitacora-link" data-modulo="Categorías">
                        <i class="nav-icon fa fa-table"></i>
                            <p>
                                Categorías
                            </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["compra"]==1): ?>
                <li class="nav-item">
                    <a href="compras" class="nav-link bitacora-link" data-modulo="Compras">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>
                                Compras
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["venta"]==1): ?>
                <li class="nav-item">
                    <a href="venta" class="nav-link bitacora-link" data-modulo="Ventas">
                        <i class="nav-icon fa fa-file"></i>
                            <p>
                                Ventas 
                            </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["cliente"]==1): ?>
                <li class="nav-item">
                    <a href="clientes" class="nav-link bitacora-link" data-modulo="Clientes">
                        <i class="nav-icon fa fa-users"></i>
                            <p>
                                Clientes
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["proveedor"]==1): ?>
                <li class="nav-item">
                    <a href="proveedores" class="nav-link bitacora-link" data-modulo="Proveedores">
                        <i class="nav-icon far fa fa-truck"></i>
                        <p>
                            Proveedores
                        </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["usuario"]==1): ?>
                <li class="nav-item">
                    <a href="usuarios" class="nav-link bitacora-link" data-modulo="Usuarios">
                        <i class="nav-icon fas fa-user"></i>
                            <p>
                                Usuarios
                            </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["reporte"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link bitacora-link" data-modulo="Reportes">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <p>
                            Reportes<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="rep-cliente" class="nav-link bitacora-link" data-modulo=" Reporte De clientes">
                                <i class="fas fa-user-friends nav-icon"></i>                                        
                                <p>
                                    De clientes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-proveedores" class="nav-link bitacora-link" data-modulo="Reporte De proveedores">
                                <i class="fas fa-store nav-icon"></i>                                        
                                    <p>
                                        De proveedores
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-inventario" class="nav-link bitacora-link" data-modulo="Reporte De inventario">
                                <i class="fas fa-pallet nav-icon"></i>                                        
                                    <p>
                                        De inventario
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-venta" class="nav-link bitacora-link" data-modulo="Reporte De ventas">
                                <i class="fas fa-file-invoice nav-icon"></i>                                        
                                    <p>
                                        De ventas
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-compra" class="nav-link bitacora-link" data-modulo="Reporte De compras">
                                <i class="fas fa-shopping-bag nav-icon"></i>                                       
                                    <p>
                                        De compras
                                    </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["configuracion"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog  nav-icon"></i>
                            <p>
                            Configuración<i class="right fas fa-angle-left"></i>
                            </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="general" class="nav-link bitacora-link" data-modulo="Ajuste general">
                                <i class="fas fa-cogs nav-icon"></i>
                                    <p>
                                        Ajuste general
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="divisa" class="nav-link bitacora-link" data-modulo="Divisas">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                    <p>
                                        Divisas
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tpago" class="nav-link bitacora-link" data-modulo="Tipos de pago">
                                <i class="fas fa-money-bill nav-icon"></i>
                                    <p>
                                        Tipos de pago
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="unidad" class="nav-link bitacora-link" data-modulo="Unidades de medida">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                        Unidades de medida
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="banco" class="nav-link bitacora-link" data-modulo="Banco">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                     Banco
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="roles" class="nav-link bitacora-link" data-modulo="Ajuste de roles"> 
                                <i class="fas fa-user-cog nav-icon"></i>
                                    <p>
                                        Ajuste de roles
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="bitacora" class="nav-link">
                                <i class="fas fa-calendar nav-icon"></i>
                                    <p>
                                       Bitacora
                                    </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>
            </ul>
        </nav>
    </div>


<?php else: ?>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($_SESSION["producto"]==1): ?>
                <li class="nav-item">
                    <a href="productos" class="nav-link disabled bitacora-link" data-modulo="Productos">
                        <i class="nav-icon fa fa-shopping-bag"></i>
                        <p>
                            Productos
                        </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["inventario"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link bitacora-link" data-modulo="Ajuste de Inventario">
                        <i class="fas fa-dolly-flatbed nav-icon"></i>
                        <p>
                            Ajuste de Inventario<i class="right fas fa-angle-left nav-icon"></i>
                        </p>
                    </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="carga" class="nav-link disabled bitacora-link" data-modulo="Carga de productos">
                                    <i class="fas fa-sort-amount-up-alt nav-icon"></i>
                                    <p>Carga de productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="descarga" class="nav-link disabled bitacora-link" data-modulo="Descarga de productos">
                                    <i class="fas fa-sort-amount-down-alt nav-icon"></i>
                                    <p>Descarga de productos</p>
                                </a>
                            </li>
                        </ul>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["categoria"]==1): ?>
                <li class="nav-item">
                    <a href="categorias" class="nav-link disabled bitacora-link" data-modulo="Categorías">
                        <i class="nav-icon fa fa-table"></i>
                            <p>
                                Categorías
                            </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["compra"]==1): ?>
                <li class="nav-item">
                    <a href="compras" class="nav-link disabled bitacora-link" data-modulo="Compras">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>
                                Compras
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["venta"]==1): ?>
                <li class="nav-item">
                    <a href="venta" class="nav-link disabled bitacora-link" data-modulo="Ventas">
                        <i class="nav-icon fa fa-file"></i>
                            <p>
                                Ventas 
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["banco"]==1): ?>
                <li class="nav-item">
                    <a href="banco" class="nav-link disabled bitacora-link" data-modulo="Banco">
                        <i class="nav-icon fa fa-file"></i>
                            <p>
                               Banco
                            </p>
                    </a>
                </li>
              
                <?php endif;?>
                <?php if ($_SESSION["cliente"]==1): ?>
                <li class="nav-item">
                    <a href="clientes" class="nav-link disabled bitacora-link" data-modulo="Clientes">
                        <i class="nav-icon fa fa-users"></i>
                            <p>
                                Clientes
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["proveedor"]==1): ?>
                <li class="nav-item">
                    <a href="proveedores" class="nav-link disabled  bitacora-link" data-modulo="Proveedores">
                        <i class="nav-icon far fa fa-truck"></i>
                        <p>
                            Proveedores
                        </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["usuario"]==1): ?>
                <li class="nav-item">
                    <a href="usuarios" class="nav-link disabled bitacora-link" data-modulo="Usuarios">
                        <i class="nav-icon fas fa-user"></i>
                            <p>
                                Usuarios
                            </p>
                    </a>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["reporte"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link bitacora-link" data-modulo="Reportes">
                        <i class="fas fa-chart-line nav-icon"></i>
                        <p>
                            Reportes<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="declientes" class="nav-link disabled bitacora-link" data-modulo="Reporte De clientes">
                                <i class="fas fa-user-friends nav-icon"></i>                                        
                                <p>
                                    De clientes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-proveedores" class="nav-link disabled bitacora-link" data-modulo="Reporte De proveedores">
                                <i class="fas fa-store nav-icon"></i>                                        
                                    <p>
                                        De proveedores
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-inventario" class="nav-link disabled bitacora-link" data-modulo="Reporte De inventario">
                                <i class="fas fa-pallet nav-icon"></i>                                        
                                    <p>
                                        De inventario
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-venta" class="nav-link disabled bitacora-link" data-modulo="Reporte De ventas">
                                <i class="fas fa-file-invoice nav-icon"></i>                                        
                                    <p>
                                        De ventas
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link disabled bitacora-link" data-modulo="Reporte De compras">
                                <i class="fas fa-shopping-bag nav-icon"></i>                                       
                                    <p>
                                        De compras
                                    </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>
                <?php if ($_SESSION["configuracion"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog  nav-icon"></i>
                            <p>
                            Configuración<i class="right fas fa-angle-left"></i>
                            </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="general" class="nav-link bitacora-link" data-modulo="Ajuste general">
                                <i class="fas fa-cogs nav-icon"></i>
                                    <p>
                                        Ajuste general
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="divisa" class="nav-link disabled bitacora-link" data-modulo="Divisas">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                    <p>
                                        Divisas
                                    </p>
                            </a>
                        </li>
                    
                        <li class="nav-item">
                            <a href="tpago" class="nav-link disabled bitacora-link" data-modulo="Tipos de pago">
                                <i class="fas fa-money-bill nav-icon"></i>
                                    <p>
                                        Tipos de pago
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="unidad" class="nav-link disabled bitacora-link" data-modulo="Unidades de medida">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                        Unidades de medida
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="banco" class="nav-link disabled bitacora-link" data-modulo="Banco">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                      Banco
                                    </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="roles" class="nav-link disabled bitacora-link" data-modulo="Ajuste de roles">
                                <i class="fas fa-user-cog nav-icon"></i>
                                    <p>
                                        Ajuste de roles
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="bitacora" class="nav-link">
                                <i class="fas fa-calendar nav-icon"></i>
                                    <p>
                                       Bitacora
                                    </p>
                            </a>
                        </li>
                        
                    </ul>
                </li>
                <?php endif;?>
            </ul>
        </nav>
    </div>
 
<?php endif;?>


</aside>

   <script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".bitacora-link").forEach(link => {
        link.addEventListener("click", (e) => {
            const modulo = link.getAttribute("data-modulo");

            // Enviar la solicitud AJAX sin interrumpir la navegación
            navigator.sendBeacon("index.php?pagina=bitacora", new URLSearchParams({
                modulo: modulo
            }));
        });
    });
});
</script>

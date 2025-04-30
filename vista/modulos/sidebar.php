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

<!--=====================
    MENU / sidebar
=====================-->
<?php if(isset($_SESSION["rif"]) || $_SESSION["cod_usuario"] == 1): ?>
    
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">INVENTARIO</li>
                    <?php if ($_SESSION["producto"]==1): ?>
                        <li class="nav-item">
                            <a href="productos" class="nav-link ">
                                <i class="nav-icon fa fa-shopping-bag"></i>
                                <p>
                                    Productos
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION["inventario"]==1): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="fas fa-dolly-flatbed nav-icon"></i>
                                <p>
                                    Ajustes<i class="right fas fa-angle-left nav-icon"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="carga" class="nav-link ">
                                        <i class="fas fa-sort-amount-up-alt nav-icon"></i>
                                        <p>Carga de productos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="descarga" class="nav-link ">
                                        <i class="fas fa-sort-amount-down-alt nav-icon"></i>
                                        <p>Descarga de productos</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </li> 

            
                <li class="nav-header">COMERCIO</li>
                <?php if ($_SESSION["compra"]==1): ?>
                <li class="nav-item">
                    <a href="compras" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>
                                Compras
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["venta"]==1): ?>
                <li class="nav-item">
                    <a href="venta" class="nav-link ">
                        <i class="nav-icon fa fa-file"></i>
                            <p>
                                Ventas 
                            </p>
                    </a>
                </li>
                <?php endif;?>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="fas fa-user-circle nav-icon"></i>
                        <p>
                            Terceros<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($_SESSION["cliente"]==1): ?>
                        <li class="nav-item">
                        
                            <a href="clientes" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                    <p>
                                        Clientes
                                    </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION["proveedor"]==1): ?>
                        <li class="nav-item">
                            <a href="proveedores" class="nav-link ">
                                <i class="nav-icon far fa fa-truck"></i>
                                <p>
                                    Proveedores
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                    <li class="nav-header">ADMINISTRACIÓN</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="fas fa-coins nav-icon"></i>
                                <p>
                                    Contabilidad<i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="catalogocuentas" class="nav-link ">
                                    <i class="fas fa-wallet nav-icon"></i>
                                        <p>Catálogo de cuentas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="movimientos" class="nav-link ">
                                    <i class="fas fa-cogs nav-icon"></i> 
                                        <p>Gestionar asientos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link ">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                        <p>Reportes contables</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>
                                    Finanzas
                                </p>
                            </a>
                        </li>
                    <li class="nav-header">TESORERÍA</li>
                        <li class="nav-item">
                            <a href="caja" class="nav-link">
                            <i class="fas fa-cash-register nav-icon"></i>

                                <p>
                                    Caja
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="cuentabancaria"  class="nav-link ">
                            <i class="fas fa-credit-card nav-icon"></i>
                                <p>
                                    Cuenta Bancaria
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="conciliacion" class="nav-link ">
                            <i class="fas fa-check-circle nav-icon"></i>



                                    <p>
                                     Conciliación bancaria
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="gastos" class="nav-link ">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>
                                    Gastos
                                </p>
                            </a>
                        </li>
                    
                        <li class="nav-item">
                            <a href="cuentaspend" class="nav-link ">
                            <i class="fas fa-wallet nav-icon"></i>                                 
                                <p>
                                    Cuentas pendientes</i>
                                </p>
                            </a>
                        </li>
        
                    <li class="nav-header">INFORMES</li>
                    <?php if ($_SESSION["reporte"]==1): ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <p>
                                Reportes<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                    <a href="rep-proveedores" class="nav-link ">
                                <i class="fas fa-store nav-icon"></i>                                        
                                    <p>
                                        De proveedores
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-inventario" class="nav-link">
                                <i class="fas fa-pallet nav-icon"></i>                                        
                                    <p>
                                        De inventario
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-venta" class="nav-link">
                                <i class="fas fa-file-invoice nav-icon"></i>                                        
                                    <p>
                                        De clientes
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-compra" class="nav-link">
                                <i class="fas fa-shopping-bag nav-icon"></i>                                       
                                    <p>
                                        De compras
                                    </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>

                <li class="nav-header">AJUSTES</li>
                <?php if ($_SESSION["configuracion"]==1): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog  nav-icon"></i>
                            <p>
                            Configuración<i class="right fas fa-angle-left"></i>
                            </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-header">GENERAL</li>
                            <li class="nav-item">
                                <a href="general" class="nav-link">
                                    <i class="fas fa-cogs nav-icon"></i>
                                        <p>
                                            Empresa
                                        </p>
                                </a>
                            </li>

                        <li class="nav-header">SEGURIDAD Y ACCESOS</li>
                        <?php if ($_SESSION["usuario"]==1): ?>
                            <li class="nav-item">
                                <a href="usuarios" class="nav-link ">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                        <p>
                                            Usuarios
                                        </p>
                                </a>
                            </li>
                        <?php endif;?>
                        
                        <!--php... -->
                        <li class="nav-item">
                            <a href="roles" class="nav-link ">
                                <i class="nav-icon fas fa-user-tag"></i>
                                    <p>
                                        Ajuste general
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="divisa" class="nav-link">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                    <p>
                                        Divisas
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tpago" class="nav-link ">
                                <i class="fas fa-money-bill nav-icon"></i>
                                    <p>
                                        Tipos de pago
                                    </p>
                            </a>
                        </li>
                    
                        <li class="nav-item">
                            <a href="unidad" class="nav-link ">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                        Unidades de medida
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="banco" class="nav-link ">
                            <i class="fas fa-landmark nav-icon"></i>

                                    <p>
                                     Banco
                                    </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                                <a href="tipocuenta" class="nav-link ">
                                <i class="fas fa-money-check-alt nav-icon"></i>


                                        <p>
                                            Tipo de cuenta
                                        </p>
                                </a>
                            </li>
                        <li class="nav-item">
                            <a href="roles" class="nav-link "> 
                                <i class="fas fa-user-cog nav-icon"></i>
                                    <p>
                                        Ajuste de roles
                                    </p>
                            </a>
                        </li>

                        <!--php... -->
                        <li class="nav-item">
                            <a href="bitacora" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                    <p>
                                        Bitacora
                                    </p>
                            </a>
                        </li>

                        <li class="nav-header">PRODUCTOS</li>
                            <li class="nav-item">
                                <a href="unidad" class="nav-link ">
                                    <i class="fas fa-balance-scale nav-icon"></i>
                                        <p>
                                            Unidades de medida
                                        </p>
                                </a>
                            </li>
                            <?php if ($_SESSION["categoria"]==1): ?>
                            <li class="nav-item">
                                <a href="categorias" class="nav-link ">
                                    <i class="nav-icon fa fa-table"></i>
                                        <p>
                                            Categorías
                                        </p>
                                </a>
                            </li>
                            <?php endif;?>

                            <li class="nav-item">

                                <a href="marcas" class="nav-link">

                                    <i class="fas fa-tags nav-icon"></i>
                                        <p>
                                            Marcas
                                        </p>
                                </a>
                            </li>
                            <li class="nav-header">FINANZAS</li>
                            <li class="nav-item">
                                <a href="divisa" class="nav-link ">
                                    <i class="fas fa-dollar-sign nav-icon"></i>
                                        <p>
                                            Divisas
                                        </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="tpago" class="nav-link ">
                                    <i class="fas fa-money-bill nav-icon"></i>
                                        <p>
                                            Tipos de pago
                                        </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="banco" class="nav-link bitacora-link" data-modulo="Banco">
                                    <i class="fas fa-university nav-icon"></i>
                                        <p>
                                            Bancos
                                        </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="categoriag" class="nav-link bitacora-link" data-modulo="Gastos">
                                    <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                        <p>
                                            Categoría de Gastos
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
            <li class="nav-header">INVENTARIO</li>
                    <?php if ($_SESSION["producto"]==1): ?>
                        <li class="nav-item">
                            <a href="productos" class="nav-link ">
                                <i class="nav-icon fa fa-shopping-bag"></i>
                                <p>
                                    Productos
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION["inventario"]==1): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-dolly-flatbed nav-icon"></i>
                                <p>
                                    Ajustes<i class="right fas fa-angle-left nav-icon"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="carga" class="nav-link ">
                                        <i class="fas fa-sort-amount-up-alt nav-icon"></i>
                                        <p>Carga de productos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="descarga" class="nav-link ">
                                        <i class="fas fa-sort-amount-down-alt nav-icon"></i>
                                        <p>Descarga de productos</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </li> 

            
                <li class="nav-header">COMERCIO</li>
                <?php if ($_SESSION["compra"]==1): ?>
                <li class="nav-item">
                    <a href="compras" class="nav-link ">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>
                                Compras
                            </p>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($_SESSION["venta"]==1): ?>
                <li class="nav-item">
                    <a href="venta" class="nav-link ">
                        <i class="nav-icon fa fa-file"></i>
                            <p>
                                Ventas 
                            </p>
                    </a>
                </li>
                <?php endif;?>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="fas fa-user-circle nav-icon"></i>
                        <p>
                            Terceros<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($_SESSION["cliente"]==1): ?>
                        <li class="nav-item">
                        
                            <a href="clientes" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                    <p>
                                        Clientes
                                    </p>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($_SESSION["proveedor"]==1): ?>
                        <li class="nav-item">
                            <a href="proveedores" class="nav-link ">
                                <i class="nav-icon far fa fa-truck"></i>
                                <p>
                                    Proveedores
                                </p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                    <li class="nav-header">ADMINISTRACIÓN</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="fas fa-coins nav-icon"></i>
                                <p>
                                    Contabilidad<i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="catalogocuentas" class="nav-link">
                                    <i class="fas fa-wallet nav-icon"></i>
                                        <p>Catálogo de cuentas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="movimientos" class="nav-link ">
                                    <i class="fas fa-cogs nav-icon"></i> 
                                        <p>Gestionar asientos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="descarga" class="nav-link ">
                                    <i class="fas fa-chart-line nav-icon"></i>
                                        <p>Reportes contables</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>
                                    Finanzas
                                </p>
                            </a>
                        </li>
                    <li class="nav-header">TESORERÍA</li>
                        <li class="nav-item">
                            <a href="caja" class="nav-link ">
                            <i class="fas fa-cash-register nav-icon"></i>

                                <p>
                                   Caja
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="cuentabancaria"  class="nav-link ">
                            <i class="fas fa-credit-card nav-icon"></i>
                                <p>
                                    Cuenta Bancaria
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="conciliacion" class="nav-link " >
                            <i class="fas fa-check-circle nav-icon"></i>

                                    <p>
                                     Conciliación bancaria
                                    </p>
                            </a>
                        </li>
                      
                            <li class="nav-item">
                                <a href="tipocuenta" class="nav-link">
                                    <i class="fas fa-university nav-icon"></i>
                                        <p>
                                            Tipo de cuenta
                                        </p>
                                </a>
                            </li>
                        <li class="nav-item">
                        <a href="gastos" class="nav-link ">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>
                                    Gastos
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="cuentaspend" class="nav-link">
                            <i class="fas fa-wallet nav-icon"></i>                                 
                                <p>
                                    Cuentas pendientes</i>
                                </p>
                            </a>
                        </li>
        
                    <li class="nav-header">INFORMES</li>
                    <?php if ($_SESSION["reporte"]==1): ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <p>
                                Reportes<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                    <a href="rep-proveedores" class="nav-link ">
                                <i class="fas fa-store nav-icon"></i>                                        
                                    <p>
                                        De proveedores
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-inventario" class="nav-link">
                                <i class="fas fa-pallet nav-icon"></i>                                        
                                    <p>
                                        De Inventario
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-venta" class="nav-link ">
                                <i class="fas fa-file-invoice nav-icon"></i>                                        
                                    <p>
                                        De clientes
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rep-compra" class="nav-link">
                                <i class="fas fa-shopping-bag nav-icon"></i>                                       
                                    <p>
                                        De compras
                                    </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>

                <li class="nav-header">AJUSTES</li>
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
                            <a href="general" class="nav-link">
                                <i class="fas fa-cogs nav-icon"></i>
                                    <p>
                                        Ajuste general
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="divisa" class="nav-link">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                    <p>
                                        Divisas
                                    </p>
                            </a>
                        </li>
                    
                        <li class="nav-item">
                            <a href="tpago" class="nav-link">
                                <i class="fas fa-money-bill nav-icon"></i>
                                    <p>
                                        Tipos de pago
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                                <a href="banco" class="nav-link">
                                <i class="fas fa-landmark nav-icon"></i>

                                        <p>
                                            Bancos
                                        </p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="tipocuenta" class="nav-link ">
                                    <i class="fas fa-university nav-icon"></i>
                                        <p>
                                            Tipo de cuenta
                                        </p>
                                </a>
                            </li>
                     
                        <li class="nav-item">
                            <a href="unidad" class="nav-link ">
                                <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>
                                        Unidades de medida
                                    </p>
                            </a>
                        </li>
                     
                    <ul class="nav nav-treeview">
                        <li class="nav-header">GENERAL</li>
                            <li class="nav-item">
                                <a href="general" class="nav-link ">
                                    <i class="fas fa-cogs nav-icon"></i>
                                        <p>
                                            Empresa
                                        </p>
                                </a>
                            </li>

                        <li class="nav-header">SEGURIDAD Y ACCESOS</li>
                        <?php if ($_SESSION["usuario"]==1): ?>
                            <li class="nav-item">
                                <a href="usuarios" class="nav-link ">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                        <p>
                                            Usuarios
                                        </p>
                                </a>
                            </li>
                        <?php endif;?>
                        
                        <!--php... -->
                        <li class="nav-item">
                            <a href="roles" class="nav-link ">
                                <i class="nav-icon fas fa-user-tag"></i>
                                    <p>
                                        Roles y Permisos
                                    </p>
                            </a>
                        </li>

                        <!--php... -->
                        <li class="nav-item">
                            <a href="bitacora" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                    <p>
                                        Bitacora
                                    </p>
                            </a>
                        </li>

                        <li class="nav-header">PRODUCTOS</li>
                            <li class="nav-item">
                                <a href="unidad" class="nav-link ">
                                    <i class="fas fa-balance-scale nav-icon"></i>
                                        <p>
                                            Unidades de medida
                                        </p>
                                </a>
                            </li>
                            <?php if ($_SESSION["categoria"]==1): ?>
                            <li class="nav-item">
                                <a href="categorias" class="nav-link ">
                                    <i class="nav-icon fa fa-table"></i>
                                        <p>
                                            Categorías
                                        </p>
                                </a>
                            </li>
                            <?php endif;?>

                            <li class="nav-item">
                                <a href="marcas" class="nav-link ">
                                    <i class="fas fa-tags nav-icon"></i>
                                        <p>
                                            Marcas
                                        </p>
                                </a>
                            </li>
                            <li class="nav-header">FINANZAS</li>
                            <li class="nav-item">
                                <a href="divisa" class="nav-link ">
                                    <i class="fas fa-dollar-sign nav-icon"></i>
                                        <p>
                                            Divisas
                                        </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="tpago" class="nav-link ">
                                    <i class="fas fa-money-bill nav-icon"></i>
                                        <p>
                                            Tipos de pago
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

<!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media();?>/images/mega.png" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">Alvaro</p>
          <p class="app-sidebar__user-designation">Administrador</p>
        </div>
      </div>
      <ul class="app-menu">
        <li class="border-top"></li>
     
          <li>
              <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
                  <i class="app-menu__icon fa fa-dashboard"></i>
                  <span class="app-menu__label">Dashboard</span>
              </a>
          </li>
   
        <li class="border-top"></li>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/proveedores">
                <i class="app-menu__icon fas fa-shipping-fast mr-2" aria-hidden="true"></i>
                <span class="app-menu__label">Proveedores</span>
            </a>
        </li>
         <li>
          <li class="mb-1">
            
                 <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"> <i class="app-menu__icon fa fa-archive mr-2"></i><span class="app-menu__label">Productos</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            
                    <ul class="treeview-menu">
                    
                          <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/productos" ><i class="icon fa fa-list-ol"></i> Productos</a></li>
                          <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/unidades"  rel="noopener"><i class="icon fa fa-magnet"></i> Unidades</a></li>
                         <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/categorias">  <i class="fas fa-clipboard-list mr-2"></i> Categorias</a></li>
                    </ul>
                </li>
           
             <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"> <i class="fas fa-cart-arrow-down mr-2"></i><span class="app-menu__label">Compras</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/compras" ><i class="icon fa fa fa-list-ol "></i> Compras</a></li>
                <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/ordendeCompra"   rel="noopener"><i class="icon fas fa-cart-arrow-down"></i> Orden de compra</a></li>
                </ul>
            </li>
     
          <li class="border-top"></li>
            <li class="border-top"></li>
        <li>
     
              <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fas fa-boxes"></i><span class="app-menu__label">Inventarios</span><i class="treeview-indicator fa fa-angle-right"></i></a>
           
              <ul class="treeview-menu">
                  <li><a class="treeview-item ml-3" href="<?= base_url(); ?>/existencias" ><i class="icon fa fa-list-ol"></i> Existencias</a></li>
                  <li><a class="treeview-item ml-3"href="<?= base_url(); ?>/entradas" ><i class="icon fa fa-sign-in-alt "></i> Entradas</a></li>
                  <li><a class="treeview-item ml-3"href="<?= base_url(); ?>/salidas" ><i class="icon fa fa-external-link-alt "></i> Salidas</a></li>
              </ul>
            <li>
      
        </li>

        <li class="border-top"></li>
    
            <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview">
              <i class="app-menu__icon fa fas fa-tools"></i><span class="app-menu__label">Administración</span><i class="treeview-indicator fa fa-angle-right"></i></a>
      
              <ul class="treeview-menu">
               
                  <li><a class="treeview-item ml-3"  href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-users "></i> Usuarios</a></li>
                
                  <li><a class="treeview-item ml-3"  href="<?= base_url(); ?>/roles"><i class="icon fa fa-scroll "></i> Roles</a></li>
             
              </ul>

        <li class="border-top"></li>
        <li>
            <a class="app-menu__item" href="<?= base_url(); ?>/logout">
                <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
                <span class="app-menu__label">Logout</span>
            </a>
        </li>
      </ul>
        <li>
      </ul>
    </aside>
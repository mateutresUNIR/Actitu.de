<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	include/menu.php
//	Propósito: 	Fichero generador de menú personalizado en función de los niveles de acceso del usuario


//Creación de instancia de usuario si no existe previamente
if(!isset($u))
	$u=new Usuario($dbData);

		
?>
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><i class="glyphicon glyphicon-eye-open"></i> <span>Actitu.de</span></a>
            </div>

            <div class="clearfix"></div>

            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="img/avatar.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>¡Bienvenido,</span>
                <h2><?php echo $u->obtenerNombre($_SESSION['id']); ?>!</h2>
              </div>
            </div>

            <br />

            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Menú de usuario</h3>
                <ul class="nav side-menu">
					<?php 
						if($_SESSION['centro']>0) { /* Bloque de menú para profesores */
					?>
							<li><a><i class="fa fa-home"></i> Profesor <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
								  <li><a href="index.php">Gestor de materias</a></li>
								  <li><a href="alumnos.php">Gestor de alumnos</a></li>
								  <li><a href="registro.php">Consulta de evidencias</a></li>
								  <li><a href="evidencias.php">Introducción de evidencias</a></li>
								  <li><a href="index.php?funcion=infoCentro">Información sobre el centro</a></li>
								</ul>
							  </li>
					<?php
						}
						else { /* Bloque de menú para usuarios registrados no vinculados a ningún centro */
					?>
							<li><a><i class="fa fa-home"></i> Profesor <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
								  <li><a href="index.php?funcion=vincularCentro">Vincular cuenta a un centro educativo</a></li>
								</ul>
							  </li>		
					<?php 
						}
						if($_SESSION['superadmin']==-1) {  /* Bloque de menú para usuarios superadministradores */
					?>
							  <li><a><i class="fa fa-users"></i> Gestor de usuarios <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
								  <li><a href="usuarios.php">Gestor de usuarios</a></li>
								  <li><a href="usuarios.php?funcion=newUsuario">Nuevo usuario</a></li>
								</ul>
							  </li>
							  <li><a><i class="fa fa-university"></i> Gestor de centros <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
								  <li><a href="centros.php">Gestor de centros</a></li>
								  <li><a href="centros.php?funcion=newCentro">Nuevo centro</a></li>
								</ul>
							  </li>
					<?php
						}
						if($_SESSION['gestorcentro']==-1) { /* Bloque de menú para usuarios gestores de centro */
					?>
							  <li><a><i class="fa fa-gears"></i> Mantenimiento datos del centro <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
								  <li><a href="criterios.php">Gestor de Criterios de Evaluación</a></li>
								  <li><a href="indicadores.php">Gestor de Indicadores</a></li>
								  <li><a href="notas.php">Configuración de notas</a></li>
								</ul>
							  </li>
					<?php 
						}
					?>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="img/avatar.jpg" alt=""><?php echo $u->obtenerNombre($_SESSION['id']); ?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="login.php?funcion=logout"><i class="fa fa-sign-out pull-right"></i> Cerrar sesión</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
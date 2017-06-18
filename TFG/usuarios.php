<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	usuarios.php
//	Propósito: 	Pantalla Gestor usuarios

require_once('include/definevars.php');
require_once('include/sesion.php');
require_once('include/MysqliDb.php');
require_once('class/alumno.php');
require_once('class/centro.php');
require_once('class/criterio.php');
require_once('class/grupo.php');
require_once('class/indicador.php');
require_once('class/nota.php');
require_once('class/usuario.php');

$titulo = "Gestión de usuarios";
include('include/header.php');

$u=new Usuario($dbData);
$c=new Centro($dbData);


// comprobación niveles acceso
if ($_SESSION['superadmin']!=-1) {
	echo " <body class=\"nav-md\">
    <div class=\"container body\">
      <div class=\"main_container\">";
	include('include/menu.php');
	echo "<div class=\"right_col\" role=\"main\">";
	echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">
		ERROR: No se disponen de los permisos adecuados para acceder en este apartado.</div>";
	echo " </div>";
	include("include/footer.php");
	exit();
}

	

//Métodos
if(@$_POST['funcion']=="newUsuarioProc"){
	//Procesado de nuevo usuario
	$id=$u->nuevo(@$_POST['nombre'],@$_POST['email'],@$_POST['contrasena']);
	if($id>0){
		$u->modificarSuperadmin($id,@$_POST['superadmin']);
		$u->modificarGestor($id,@$_POST['gestor']);
		$u->modificarCentro($id,@$_POST['centro']);
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el usuario \"".@$_POST['nombre']."\" con éxito</div>";
	}
	
}
elseif(@$_POST['funcion']=="editUsuarioProc"){	
	//Procesado de edición de usuario
	if(    $u->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $u->modificarEmail(@$_POST['id'],@$_POST['email'])
	   ){
		$u->modificarSuperadmin(@$_POST['id'],@$_POST['superadmin']);
		$u->modificarGestor(@$_POST['id'],@$_POST['gestor']);
		$u->modificarCentro(@$_POST['id'],@$_POST['centro']);
		$u->modificarContrasena(@$_POST['id'],@$_POST['contrasena']);
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el usuario \"".@$_POST['nombre']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteUsuarioProc"){
	//Procesado de eliminación de usuario
	if ($u->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el usuario con éxito</div>";
	}
}
?>
 <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include('include/menu.php'); ?>
		
		<div class="right_col" role="main">
		<?php
			/* Personalización de las pantallas según función */
			if (@$_GET['funcion']=="newUsuario"){ 
				// Alta nuevo usuario
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de usuarios: Alta nuevo usuario</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para dar de alta un nuevo usuario en la plataforma Actitu.de.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="POST">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre y apellidos <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contrasena">Contraseña <br>(dejar en blanco para no cambiar)</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="password" id="contrasena" name="contrasena" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <h3>Permisos y roles</h3>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Niveles de acceso</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
									  <div class="">
										<label>
										  <input type="checkbox" class="js-switch" name="superadmin" value="-1"/> Superadministrador
										</label>
									  </div>
									  <div class="">
										<label>
										  <input type="checkbox" class="js-switch" name="gestor" value="-1"/> Gestor de Centro
										</label>
									  </div>
									</div>
								  </div>


								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Centro docente</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
									  <select class="form-control" name="centro">
											<option></option>
											<?php
												$centros=$c->listar();
												foreach($centros as $centro){
													echo "<option value=\"".$centro['id']."\">".$centro['nombre']." (".$centro['codigo'].")</option>";
												}

											?>
									  </select>
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='usuarios.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo usuario</button>
									</div>
								  </div>
								  <input type="hidden" name="funcion" value="newUsuarioProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editUsuario"){ 
				//edición de usuario
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de usuarios: Editar usuario</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para actualizar los datos del usuario en la plataforma Actitu.de.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="POST">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre y apellidos <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $u->obtenerNombre(@$_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $u->obtenerEmail(@$_GET['id']); ?>">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contrasena">Contraseña <br>(dejar en blanco para no cambiar)</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="password" id="contrasena" name="contrasena" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <h3>Permisos y roles</h3>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Niveles de acceso</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
									  <div class="">
										<?php 
											if($u->obtenerSuperadmin(@$_GET['id'])) {
												$checked=" checked ";
											}
											else{
												$checked="";
											}
										?>
										<label>
										  <input type="checkbox" class="js-switch" name="superadmin" value="-1" <?php echo $checked; ?> /> Superadministrador
										</label>
									  </div>
									  <div class="">
										<?php 
											if($u->obtenerGestorCentro(@$_GET['id'])) {
												$checked=" checked ";
											}
											else{
												$checked="";
											}
										?>
										<label>
										  <input type="checkbox" class="js-switch" name="gestor" value="-1" <?php echo $checked; ?> />  Gestor de Centro
										</label>
									  </div>
									</div>
								  </div>


								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Centro docente</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
									  <select class="form-control" name="centro">
											<option></option>
											<?php
												$centros=$c->listar();
												foreach($centros as $centro){
													echo "<option ";
													if($centro['id']==$u->obtenerCentro(@$_GET['id'])) echo "selected";
													echo " value=\"".$centro['id']."\">".$centro['nombre']." (".$centro['codigo'].")</option>";
												}

											?>
									  </select>
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='usuarios.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Editar usuario</button>
									</div>
								  </div>
								  <input type="hidden" name="funcion" value="editUsuarioProc">
								  <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteUsuario" && @$_GET['id']>0){ 
				//Baja de usuario
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de usuarios: Baja usuario</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el usuario "<?php echo $u->obtenerNombre(@$_GET['id']); ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
									<a href="usuarios.php"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="funcion" value="deleteUsuarioProc">

								</form>

							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: listado de usuarios
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de usuarios</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newUsuario" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo usuario</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestran todos los usuarios activos en la plataforma Actitu.de.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Id</th>
								  <th>Nombre</th>
								  <th>Correo electrónico</th>
								  <th>Rol</th>
								  <th>Centro asociado</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
								$usuarios=$u->listar();
								if(!@$usuarios){
									echo "<tr><td colspan=\"6\">No existen usuarios registrados en la plataforma.</td></tr>";
								}
								foreach($usuarios as $usuario){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editUsuario&id=".$usuario['id']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteUsuario&id=".$usuario['id']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$usuario['id']."</td>
										  <td>".$usuario['nombre']."</td>
										  <td>".$usuario['email']."</td>
										  <td>";
									
									if($usuario['superadmin']) echo "<div><i class=\"fa fa-sliders\"></i> Superadmin</div>";
									if($usuario['gestorCentro']) echo "<div><i class=\"fa fa-gears\"></i> Gestor</div>";
									
									echo "</td>
										  <td>";
									$t=$c->obtenerNombreYCodigo($usuario['idCentro']);
									if($t)
										echo $t;
									else
										echo "<span class=\"label label-danger\">No vinculado a ningún centro</span>";
									echo "</td>
										</tr>";
								}
							  ?>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
			<?php
				}
			?>
          </div>
        </div>		
<?php include("include/footer.php");
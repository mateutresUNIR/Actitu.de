<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	centros.php
//	Propósito: 	Pantalla Gestor centros

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

$titulo = "Gestión de centros educativos";
include('include/header.php');

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
if(@$_POST['funcion']=="newCentroProc"){
	//Procesado de nuevo centro
	if($c->nuevo(@$_POST['nombre'],@$_POST['poblacion'],@$_POST['codigo'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el centro \"".@$_POST['nombre']."\" con éxito</div>";
	}
	
}
elseif(@$_POST['funcion']=="editCentroProc"){
	//Procesado de edición de centro
	if(    $c->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $c->modificarPoblacion(@$_POST['id'],@$_POST['poblacion'])
		&& $c->modificarCodigo(@$_POST['id'],@$_POST['codigo'])
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el centro \"".@$_POST['nombre']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteCentroProc"){
	//Procesado de eliminación de centro
	if ($c->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el centro con éxito</div>";
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
			if (@$_GET['funcion']=="newCentro"){ 
				//Alta nuevo centro
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-university"></i> Gestor de centros: Alta nuevo centro</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para dar de alta un nuevo centro en la plataforma Actitu.de.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="POST">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre del centro <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="poblacion">Población <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="poblacion" name="poblacion" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código del centro <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='centros.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo centro</button>
									</div>
								  </div>
								  <input type="hidden" name="funcion" value="newCentroProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editCentro"){ 
				//Edición de centro exsitente
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-university"></i> Gestor de centros: Editar centro</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, modifica los campos que se requieren para actualizar los datos del centro en la plataforma Actitu.de.</p>
							
							<form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="POST">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre del centro <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->obtenerNombre(@$_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="poblacion">Población <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="poblacion" name="poblacion" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->obtenerPoblacion(@$_GET['id']); ?>">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código del centro <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->obtenerCodigo(@$_GET['id']); ?>">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='centros.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar centro</button>
									</div>
								  </div>
								  <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
								  <input type="hidden" name="funcion" value="editCentroProc">

								</form>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteCentro" && @$_GET['id']>0){ 
				//Eliminación de centro
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-university"></i> Gestor de centros: Baja centro</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el centro "<?php echo $c->obtenerNombre(@$_GET['id']); ?>" con código nº <?php echo $c->obtenerCodigo(@$_GET['id']); ?> ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
									<a href="centros.php"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="funcion" value="deleteCentroProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: listado de centros
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-university"></i> Gestor de centros</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newCentro" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo centro</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestran todos los centros educativos activos en la plataforma Actitu.de.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Id</th>
								  <th>Nombre del centro</th>
								  <th>Población</th>
								  <th>Código</th>
								  <th>Clave</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
								$centros=$c->listar();
								if(!@$centros){
									echo "<tr><td colspan=\"6\">Aún no existe ningún centro en la plataforma</td></tr>";
								}
								foreach($centros as $centro){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editCentro&id=".$centro['id']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteCentro&id=".$centro['id']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$centro['id']."</td>
										  <td>".$centro['nombre']."</td>
										  <td>".$centro['poblacion']."</td>
										  <td>".$centro['codigo']."</td>
										  <td>".$centro['clave']."</td>
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
        </div>		
<?php include("include/footer.php");
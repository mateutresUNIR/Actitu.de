<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	criterios.php
//	Propósito: 	Pantalla Mantenimiento de centros - Criterios de evaluación

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

$titulo = "Configuración criterios de evaluación del centro";
include('include/header.php');

$c=new Centro($dbData);

// comprobación niveles acceso
if ($_SESSION['gestorcentro']!=-1) {
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
if(@$_POST['funcion']=="newCriterioProc"){
	//Procesado de nuevo criterio
	$c->cargarCriterios(@$_POST['centro']);
	if($c->criterio->nuevo(@$_POST['codigo'],@$_POST['nombre'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el elemento de notas \"".@$_POST['codigo']."\" con éxito</div>";
	}
}
elseif(@$_POST['funcion']=="editCriterioProc"){
	//Procesado de edición de criterio
	$c->cargarCriterios(@$_POST['centro']);
	if(    $c->criterio->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $c->criterio->modificarCodigo(@$_POST['id'],@$_POST['codigo'])
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el criterio de evaluación \"".@$_POST['codigo']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteCriterioProc"){
	//Procesado de eliminación de criterio
	$c->cargarCriterios(@$_POST['centro']);
	if ($c->criterio->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el elemento de notas con éxito</div>";
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
			if (@$_GET['funcion']=="newCriterio"){ 
				//Alta de nuevo criterio
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-puzzle-piece"></i> Gestor de criterios de evaluación del centro: Nuevo elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para configurar un criterio de evaluación para el centro.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Descripción <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='criterios.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo elemento</button>
									</div>
								  </div>
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="newCriterioProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editCriterio"){ 
				//Edición de criterio existente
				$c->cargarCriterios($_SESSION['centro']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-puzzle-piece"></i> Gestor de criterios de evaluación del centro: Modificar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, modifica los campos que se requieren para actualizar los datos del centro en la plataforma Actitu.de.</p>
							
						    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->criterio->obtenerCodigo(@$_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Descripción <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->criterio->obtenerNombre(@$_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='criterios.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar elemento</button>
									</div>
								  </div>
								  <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="editCriterioProc">

								</form>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteCriterio" && @$_GET['id'] && @$_GET['centro']>0){ 
				//Eliminación de criterio existente
				$c->cargarCriterios(@$_GET['centro']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-puzzle-piece"></i> Gestor de criterios de evaluación del centro: Borrar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el criterio de evaluación "<?php echo $c->criterio->obtenerCodigo(@$_GET['id']); ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
									<a href="?"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="centro" value="<?php echo @$_GET['centro']; ?>">
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="funcion" value="deleteCriterioProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: listado de criterios
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-puzzle-piece"></i> Gestor de criterios de evaluación del centro</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newCriterio" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo elemento</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestra la relación de criterios de evaluación de actitud configurados para el centro.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Id</th>
								  <th>Código</th>
								  <th>Descripción</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  
								$c->cargarCriterios($_SESSION['centro']);
								$criterios=$c->criterio->listar();
								if(!@$criterios){
									echo "<tr><td colspan=\"4\">No existen aún criterios de evaluación para este centro.</td></tr>";
								}
								foreach($criterios as $criterio){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editCriterio&id=".$criterio['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteCriterio&id=".$criterio['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$criterio['id']."</td>
										  <td>".$criterio['codigo']."</td>
										  <td>".$criterio['nombre']."</td>
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
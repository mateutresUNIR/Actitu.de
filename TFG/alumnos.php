<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	alumnos.php
//	Propósito: 	Pantalla profesor para gestión de alumnos de un grupo

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

$titulo = "Gestor de alumnos en materias";
include('include/header.php');

$c=new Centro($dbData);
$u=new Usuario($dbData);

//Métodos
if(@$_POST['funcion']=="importCSVProc"){
	//Procesado de importación de fichero CSV
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);
	if(strtolower(substr(@$_FILES['csv']['name'],-3))=="csv" && file_exists(@$_FILES['csv']['tmp_name'])){
		//Leer fichero CSV
		$csv=fopen(@$_FILES['csv']['tmp_name'], "r");
		$cabecera=@fgets($csv);
		if(trim($cabecera)!="Apellidos;Nombre") {
			echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">Error: el formato de la cabecera del fichero no es correcto.</div>";
		}
		else {
			$cuantos=0;
			while(!feof($csv)) {
				$t=str_getcsv(fgets($csv), ";");
				if(count($t)==2) {
					$c->grupo->alumnos->nuevo($t[1],$t[0],@$_POST['grupo']);
					$cuantos++;
				}
			}
			echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
			Se han importado $cuantos alumnos.</div>";
		}
		fclose($csv);
	}
	else {
		echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">Error: no se ha podido importar el fichero CSV.</div>";
	}
}
elseif(@$_POST['funcion']=="newAlumnoProc"){
	//Procesado de nuevo alumno
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);

	if($c->grupo->alumnos->nuevo(@$_POST['nombre'],@$_POST['apellidos'],@$_POST['grupo'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el alumno \"".@$_POST['apellidos'].", ".@$_POST['nombre']."\" con éxito</div>";
	}
}
elseif(@$_POST['funcion']=="editAlumnoProc"){
	//Procesado de edición de alumno existente
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);
	if(    $c->grupo->alumnos->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $c->grupo->alumnos->modificarApellidos(@$_POST['id'],@$_POST['apellidos'])
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el alumno \"".@$_POST['apellidos'].", ".@$_POST['nombre']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteAlumnoProc"){
	//Procesado de eliminación de alumno existente
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);
	if ($c->grupo->alumnos->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el alumno con éxito</div>";
	}
}
								
?>
 <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include('include/menu.php'); ?>
		
		<div class="right_col" role="main">
		<?php
			/* Personalización de pantallas */
				if (@$_GET['funcion']=="newAlumno" && @$_GET['grupo']>0){ 
					//Nuevo alumno
					$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
					?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-user"></i> Gestor de alumnos en materias: Nuevo alumno</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para insertar un nuevo alumno en el grupo-materia.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="?grupo=<?php echo @$_GET['grupo']; ?>">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grupoN">Grupo 
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="grupoN" name="grupoN" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerNombre($_GET['grupo']); ?>">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="apellidos">Apellidos <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="apellidos" name="apellidos" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='alumnos.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo alumno</button>
									</div>
								  </div>
								  <input type="hidden" name="grupo" value="<?php echo $_GET['grupo']; ?>">
								  <input type="hidden" name="funcion" value="newAlumnoProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editAlumno"){ 
				//Edición de alumno existente
				$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
				$c->grupo->cargarAlumnos($_GET['grupo']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-user"></i> Gestor de alumnos en materias: Modificar alumno</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para modificar un alumno en el grupo-materia.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" METHOD="POST" action="?grupo=<?php echo @$_GET['grupo']; ?>">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grupoN">Grupo 
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="grupoN" name="grupoN" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerNombre($_GET['grupo']); ?>">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->alumnos->obtenerNombre($_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="apellidos">Apellidos <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="apellidos" name="apellidos" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->alumnos->obtenerApellidos($_GET['id']); ?>">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='alumnos.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar alumno</button>
									</div>
								  </div>
								  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								  <input type="hidden" name="grupo" value="<?php echo $_GET['grupo']; ?>">
								  <input type="hidden" name="funcion" value="editAlumnoProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="importCSV"){ 
				//Importación de fichero CSV
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Gestor de alumnos en materias: Importación de elementos (CSV)</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Puedes importar una tabla de alumnos existentes a partir de un fichero CSV.</p>
							<p>La primera línea del fichero debe de contener el nombre de los campos: "Apellidos;Nombre;". La segunda y posteriores líneas se corresponderán cada una a un alumno distinto.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?grupo=<?php echo @$_GET['grupo']; ?>" method="post" enctype="multipart/form-data">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Fichero a importar <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
								      <input type="file" name="csv" id="csv" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='indicadores.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Importar fichero CSV</button>
									</div>
								  </div>
								  <input type="hidden" name="grupo" value="<?php echo $_GET['grupo']; ?>">
								  <input type="hidden" name="funcion" value="importCSVProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteAlumno" && @$_GET['id']>0 && @$_GET['grupo']>0){ 
				//Eliminación de usuario existente
				$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
				$c->grupo->cargarAlumnos($_GET['grupo']);
			?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-user"></i> Gestor de alumnos en materias: Borrar alumno</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el alumno "<?php echo $c->grupo->alumnos->obtenerApellidosNombre(@$_GET['id']); ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?grupo=<?php echo @$_GET['grupo']; ?>" id="formDelete">
									<a href="alumnos.php"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="button" class="btn btn-danger" onclick="document.getElementById('formDelete').submit();">Sí</button></a>
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="grupo" value="<?php echo @$_GET['grupo']; ?>">
									<input type="hidden" name="funcion" value="deleteAlumnoProc">

								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defefcto: listado de alumnos
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de alummnos en materias</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							<?php 
							if(@$_GET['grupo']>0) {
								echo "<li><a href=\"?funcion=newAlumno&grupo=".@$_GET['grupo']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-plus\"></i> Añadir nuevo alumno</a></li>";
								echo "<li><a href=\"?funcion=importCSV&grupo=".@$_GET['grupo']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-table\"></i> Importación alumnos (CSV)</a></li>";
							}
							?>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-caret-square-o-down"></i> Materias-grupos</a>
                                <ul class="dropdown-menu" role="menu">
								
								<?php
									$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
									$grupos=$c->grupo->listar();			
									foreach($grupos as $grupo){
										echo "<li><a href=\"?grupo=".$grupo['id']."\">".$grupo['codigo'].": ".$grupo['nombre']."</a></li>";
									}
								?>
                                </ul>
                              </li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
						  <?php 
							if(!@$_GET['grupo']){
								echo "<p>Selecciona una materia-grupo desde el desplegable ubicado en la parte superior derecha de esta página</p>";
							}
							else {
						  ?>
							<p>En este listado se muestra la relación alumnos para la materia seleccionada.</p>
							<p><input type="text" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerNombre(@$_GET['grupo']); ?>"></p>
							&nbsp;
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Id</th>
								  <th>Apellidos, Nombre</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  
								$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
								$c->grupo->cargarAlumnos(@$_GET['grupo']);
								$alumnos=$c->grupo->alumnos->listar();
								if(!@$alumnos){
									echo "<tr><td colspan=\"3\">Este grupo aún no tiene alumnos. Debes de añadirlos.</td></tr>";
								}
								
								foreach($alumnos as $alumno){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editAlumno&id=".$alumno['id']."&grupo=".$_GET['grupo']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteAlumno&id=".$alumno['id']."&grupo=".$_GET['grupo']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$alumno['id']."</td>
										  <td>".$alumno['apellidos'].", ".$alumno['nombre']."</td>
										</tr>";
								}
							  ?>
							  </tbody>
							</table>
							
							<?php 
								}
							?>
						  </div>
						</div>
					  </div>
			<?php
				}
			?>
          </div>
<?php include("include/footer.php");
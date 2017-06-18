<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	evidencias.php
//	Propósito: 	Pantalla Profesor para la toma de evidencias

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

$titulo = "Registro de evidencias";
include('include/header.php');

$c=new Centro($dbData);
$u=new Usuario($dbData);

//Métodos
if(@$_POST['funcion']=="deleteEvidenciasProc"){
	//Procesado de eliminación de evidencias
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);
	if($c->grupo->alumnos->borrarIndicador(@$_POST['alumno'],@$_POST['indicador'],@$_POST['fecha'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el indicador con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="regEvidenciasProc"){
	//Procesado de registro de nueva evidencia
	$c->cargarGrupos(@$_SESSION['centro'],@$_SESSION['id']);
	$c->grupo->cargarAlumnos($_POST['grupo']);
	
	$alumnos=$c->grupo->alumnos->listar();
	foreach($alumnos as $alumno){
		if(@$_POST['observ'.$alumno['id']]>0) {
			$c->grupo->alumnos->nuevoIndicador($alumno['id'],@$_POST['observ'.$alumno['id']],$_POST['fecha']);
		}
	}
	echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se han registrado las evidencias con éxito</div>";
}
								
?>
 <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include('include/menu.php'); ?>
		
		<div class="right_col" role="main">
		<?php
			/* Personalización de las pantallas según función */
			if (@$_GET['funcion']=="deleteEvidencias" && @$_GET['alumno']>0 && @$_GET['grupo']>0 && @$_GET['indicador']>0 && @$_GET['fecha']){ 
				//Eliminación de evidencia
				$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
				$c->grupo->cargarAlumnos($_GET['grupo']);
				$c->cargarIndicadores($_SESSION['centro']);
			?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Registro de evidencias en un grupo-materia: Borrar evidencia</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar la evidencia "<?php echo $c->indicador->obtenerNombre(@$_GET['indicador']); ?>" para el alumno "<?php echo $c->grupo->alumnos->obtenerApellidosNombre(@$_GET['alumno']); ?>" registrada el día "<?php echo @$_GET['fecha']; ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?grupo=<?php echo @$_GET['grupo']; ?>&fecha=<?php echo @$_GET['fecha']; ?>" id="formDelete">
									<a href="?"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="alumno" value="<?php echo @$_GET['alumno']; ?>">
									<input type="hidden" name="indicador" value="<?php echo @$_GET['indicador']; ?>">
									<input type="hidden" name="fecha" value="<?php echo @$_GET['fecha']; ?>">
									<input type="hidden" name="grupo" value="<?php echo @$_GET['grupo']; ?>">
									<input type="hidden" name="funcion" value="deleteEvidenciasProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: listado de registro de evidencias
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Registro de evidencias en un grupo-materia</small></h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
						  
							<p>En este listado se muestra la relación alumnos para la materia seleccionada.</p>
							
							<div class="row">
								<form>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="grupo" required="required">
										<option>--Escoge grupo-materia--</option>
										<?php
											$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
											$grupos=$c->grupo->listar();
											foreach($grupos as $grupo){
												echo "<option value=\"".$grupo['id']."\"";
												if ($grupo['id']==@$_GET['grupo']) echo " selected "; 
												echo ">".$grupo['codigo'].": ".$grupo['nombre']."</option>";
											}
										?>
										</select>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-8">
										<input type="text" class="form-control has-feedback-left" id="single_cal4" placeholder="First Name" aria-describedby="inputSuccess2Status4" name="fecha" value="<?php echo @$_GET['fecha']; ?>">
										<span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
										<span id="inputSuccess2Status4" class="sr-only">(success)</span>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-4">
									  <button type="submit" class="btn btn-success">Actualizar</button>
									</div>
								</form>
							</div>
							
						  <?php 
							if(@$_GET['grupo']){
						  ?>
							&nbsp;
							<form method="POST">
								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
								  <thead>
									<tr>
									  <th rowspan="2">Id</th>
									  <th rowspan="2" width="250">Apellidos, Nombre</th>
									  <?php
										$c->cargarCriterios($_SESSION['centro']);
										$criterios=$c->criterio->listar();
										foreach($criterios as $criterio){
											echo "<th width=\"60\" colspan=\"3\" style=\"text-align:center;\"><a href=\"#\" title=\"".$criterio['nombre']."\">".$criterio['codigo']."</a></th>";
										}
									  ?>
									  <th rowspan="2">Evidencias del día</th>
									  <th rowspan="2" width="300">Introducir nueva observación</th>
									</tr>
										<?php
										foreach($criterios as $criterio){
											echo "<th width=\"20\" style=\"text-align:center;\">Nº</th>";
											echo "<th width=\"20\" style=\"text-align:center;\">Suma</th>";
											echo "<th width=\"20\" style=\"text-align:center;\">Nota</th>";
										}
									  ?>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  
									$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
									$c->grupo->cargarAlumnos(@$_GET['grupo']);
									$c->cargarIndicadores($_SESSION['centro']);
									$c->cargarNotas($_SESSION['centro']);
									$alumnos=$c->grupo->alumnos->listar();
									foreach($alumnos as $alumno){
										echo"<tr>
											  <td>".$alumno['id']."</td>
											  <td>".$alumno['apellidos'].", ".$alumno['nombre']."</td>";
										
										$criterios=$c->criterio->listar();
										foreach($criterios as $criterio){
											echo "<td style=\"text-align:center;\">";
											echo $c->grupo->alumnos->listarIndicadores($alumno['id'],$criterio['id'],true);
											echo "</td><td style=\"text-align:center;\">";
											echo number_format($c->grupo->alumnos->listarIndicadores($alumno['id'],$criterio['id'],false),2,'.','');
											echo "</td><td style=\"text-align:center;\">";
											
											$max=$c->grupo->alumnos->indicadorMaximoClase($criterio['id']);
											$min=$c->grupo->alumnos->indicadorMinimoClase($criterio['id']);
											$nota=$c->grupo->alumnos->listarIndicadores($alumno['id'],$criterio['id'],false);
											/*ajustamos ponderación a los máximos y mínimos, si los hay*/
											if($min<-5) $nota=$nota*5/$max;
											if($max>5) $nota=$nota*5/$max;
											$nota+=5;
											echo "<a href=\"#\" title=\"".number_format($nota,2,'.','')."\">".$c->nota->calcularLetra($nota)."</a>";
											echo "</td>";
										}
										
										echo "<td>";
										$observaciones=$c->grupo->alumnos->listarIndicadoresNombres($alumno['id'],$criterio['id'],$_GET['fecha']);
										foreach($observaciones as $observacion){
											echo "<div><i class=\"fa fa-".$observacion['icono']."\"></i> ".$observacion['nombre']." (".$observacion['factor'].") 
											<a href=\"?funcion=deleteEvidencias&grupo=".$_GET['grupo']."&alumno=".$alumno['id']."&indicador=".$observacion['indicador']."&fecha=".$_GET['fecha']."\"><i class=\"fa fa-close\"></i></a></div>";
										}
										echo "</td>";
										echo "<td><select class=\"form-control\" name=\"observ".$alumno['id']."\">
													<option></option>";
										$indicadores=$c->indicador->listar();
										foreach($indicadores as $indicador){
												  echo "<option value=\"".$indicador['id']."\">".$indicador['nombre']." (".number_format($indicador['factor'],2,'.','').")</option>";
											  
										}
										echo "</select></td>";
										echo "	</tr>";
									}
								  ?>
								  </tbody>
								</table>
								<div class="col-md-10 col-sm-10 col-xs-8">
								</div>
								<input type="hidden" name="funcion" value="regEvidenciasProc"> 
								<input type="hidden" name="grupo"   value="<?php echo @$_GET['grupo']; ?>">
								<input type="hidden" name="fecha"   value="<?php echo @$_GET['fecha']; ?>">
								<div class="col-md-2 col-sm-2 col-xs-4">
								  <button type="submit" class="btn btn-success">Guardar cambios</button>
								</div>
							</form>
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
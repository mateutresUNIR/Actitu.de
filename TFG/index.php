<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	index.php
//	Propósito: 	Pantalla Panel profesor - gestor de materias

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

$titulo = "Panel del profesor";
include('include/header.php');

$c=new Centro($dbData);
$u=new Usuario($dbData);

//Métodos
if(@$_POST['funcion']=="vincularCentroProc"){
	//Procesado de vinculación de cuenta a centro
	if($u->modificarCentro($_SESSION['id'],@$_POST['centro'])) {
		$_SESSION['centro']=$_POST['centro'];
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha vinculado el usuario con el centro \"".$c->obtenerNombre(@$_POST['centro'])."\" con éxito</div>";
	}
	else{
		echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		No se ha podido vincular la cuenta de usuario con el centro educativo. Revisar la clave de vinculación</div>";
	}


}
elseif(@$_POST['funcion']=="newGrupoProc"){
	//Procesado de nuevo grupo
	$c->cargarGrupos(@$_POST['centro'],@$_SESSION['id']);
	if($c->grupo->nuevo(@$_POST['codigo'],@$_POST['nombre'],@$_POST['grupo'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el grupo\"".@$_POST['codigo']."\" con éxito</div>";
	}
}
elseif(@$_POST['funcion']=="editGrupoProc"){
	//Procesado de edición de grupo existente
	$c->cargarGrupos(@$_POST['centro'],@$_SESSION['id']);
	if(    $c->grupo->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $c->grupo->modificarCodigo(@$_POST['id'],@$_POST['codigo'])
		&& $c->grupo->modificarGrupo(@$_POST['id'],@$_POST['grupo'])
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el grupo \"".@$_POST['codigo']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteGrupoProc"){
	//Procesado de eliminación de grupo existente
	$c->cargarGrupos(@$_POST['centro'],@$_SESSION['id']);
	if ($c->grupo->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el grupo con éxito</div>";
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
			if (!$_SESSION['centro'] or @$_GET['funcion']=="vincularCentro"){ 
				//Pantalla de vinculación a centro educativo
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-graduation-cap"></i> Vincular cuenta a un centro educativo</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para vincular la cuenta de usuario con un centro educativo.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="?">

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
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="clave">Clave de vinculación <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="password" id="clave" name="clave" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='index.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Vincular cuenta a centro educativo</button>
									</div>
								  </div>
								  <input type="hidden" name="funcion" value="vincularCentroProc">

								</form>
								
						  </div>
						</div>
					  </div>

		<?php
			}
			elseif (@$_GET['funcion']=="newGrupo"){ 
				//Nuevo grupo-materia
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de materias: Nuevo elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para configurar un grupo-materia para el docente (un código abreviado, el nombre de la materia y el nombre del grupo clase).</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="?">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12">
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grupo">Grupo <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="grupo" name="grupo" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='index.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo grupo</button>
									</div>
								  </div>
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="newGrupoProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editGrupo"){ 
				//Edición de materia-grupo existente
				$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de materias: Editar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para configurar un grupo-materia para el docente.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="?">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="codigo">Código <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="codigo" name="codigo" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerCodigo($_GET['id']); ?>">
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerNombre($_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="grupo">Grupo <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="grupo" name="grupo" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerGrupo($_GET['id']); ?>">
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='index.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar grupo</button>
									</div>
								  </div>
								  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="editGrupoProc">

								</form>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteGrupo" && @$_GET['id'] && @$_GET['centro']>0){ 
				//Eliminación de materia-grupo
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de materias: Borrar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el elemento "<?php echo @$_GET['id']; ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
								<a href="?"><button type="button" class="btn btn-default" onclick="location.href='index.php';">No</button></a>
								<a href="#"><button type="button" class="btn btn-danger" onclick="document.getElementById('formDelete').submit();">Sí</button></a>
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="centro" value="<?php echo @$_GET['centro']; ?>">
									<input type="hidden" name="funcion" value="deleteGrupoProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="infoCentro"){ 
				//Pantalla información general del centro
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-gears"></i> Información sobre el centro educativo</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En esta página se pueden consultar los distintos parámetros configurados para el centro educativo.</p>							
						  </div>
						</div>
					  </div>
					  
					 <div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">
						  <div class="x_panel tile">
							<div class="x_title">
							  <h2><i class="fa fa-university"></i> Datos básicos del centro</h2>
							  <div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>En esta tabla se muestran los datos básicos del centro educativo.</p>
								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								  <th>Campo</th>
								  <th>Valor</th>
								</tr>
							  </thead>
							  <tbody>
								<tr>
								  <td>Centro</td>
								  <td><?php echo $c->obtenerNombre($_SESSION['centro']); ?></td>
								</tr>
								<tr>
								  <td>Población</td>
								  <td><?php echo $c->obtenerPoblacion($_SESSION['centro']); ?></td>
								</tr>
								<tr>
								  <td>Código</td>
								  <td><?php echo $c->obtenerCodigo($_SESSION['centro']); ?></td>
								</tr>
							  </tbody>
							</table>
							</div>
						  </div>
						</div>

						
						<div class="col-md-4 col-sm-4 col-xs-12">
						  <div class="x_panel tile">
							<div class="x_title">
							  <h2><i class="fa fa-calculator"></i> Configuración de notas</h2>
							  <div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>En este listado se muestra la relación de notas alfabéticas habilitadas para el centro y el rango equivalente numérico sobre 10.</p>
								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
								  <thead>
									<tr>
									  <th>Letra</th>
									  <th>Nota mínima</th>
									  <th>Nota máxima</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  
									$c->cargarNotas($_SESSION['centro']);
									$notas=$c->nota->listar();
									if(!$notas) {
										echo "<tr><td colspan=\"4\">El centro aún no tiene configurado ningúna relación de notas.</td></tr>";
									}

									foreach($notas as $nota){
										echo"<tr>
											  <td>".$nota['letra']."</td>
											  <td>".number_format($nota['notaMin'],2,'.','')."</td>
											  <td>".number_format($nota['notaMax'],2,'.','')."</td>
											</tr>";
									}
								  ?>
								  </tbody>
								</table>

							</div>
						  </div>
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12">
						  <div class="x_panel tile">
							<div class="x_title">
							  <h2><i class="fa fa-puzzle-piece"></i> Criterios de evaluación</h2>
							  <div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>En este listado se muestra la relación de criterios de evaluación de actitud configurados para el centro.</p>
								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
								  <thead>
									<tr>
									  <th>Id</th>
									  <th>Código</th>
									  <th>Descripción</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  
									$c->cargarCriterios($_SESSION['centro']);
									$criterios=$c->criterio->listar();
									if(!$criterios) {
										echo "<tr><td colspan=\"4\">El centro aún no tiene configurado ningún criterio.</td></tr>";
									}
									
									foreach($criterios as $criterio){
										echo"<tr>
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

					  </div>
						<div class="col-md-12 col-sm-12 col-xs-12">
						  <div class="x_panel tile">
							<div class="x_title">
							  <h2><i class="fa fa-eye"></i> Indicadores de actitud</h2>
							  <div class="clearfix"></div>
							</div>
							<div class="x_content">
								<p>En este listado se muestra la relación de indicadores de actitud configurados para el centro.</p>
								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
								  <thead>
									<tr>
									  <th>Id</th>
									  <th>Nombre</th>
									  <th colspan="2">Factor</th>
									  <th>Criterio</th>
									</tr>
								  </thead>
								  <tbody>
								  <?php
								  
									$c->cargarIndicadores($_SESSION['centro']);
									$c->cargarCriterios($_SESSION['centro']);
									$indicadores=$c->indicador->listar();
									if(!$indicadores) {
										echo "<tr><td colspan=\"4\">El centro aún no tiene configurado ningún indicador.</td></tr>";
									}

									foreach($indicadores as $indicador){
										echo"<tr>
											  <td>".$indicador['id']."</td>
											  <td><i class=\"fa fa-".$indicador['icono']."\"></i> &nbsp; ".$indicador['nombre']."</td> 
											  <td style=\"width: 30px;\">".number_format($indicador['factor'],2,'.','')."</td>
											  <td style=\"width: 200px;\">"; 
											  
										if($indicador['factor']<0) {
											echo "  <div class=\"progress right\" style=\"width:45%; display:inline-block; margin-bottom: 0px !important;\"> <div class=\"progress-bar progress-bar-danger\" data-transitiongoal=\"".number_format(abs($indicador['factor']*100),0,"","")."\"></div></div>";
											echo "  <div class=\"progress\" style=\"width:45%; display:inline-block; margin-bottom: 0px !important;\"> <div class=\"progress-bar progress-bar-success\" data-transitiongoal=\"0\"></div></div>";
										}
										else {
											echo "  <div class=\"progress right\" style=\"width:45%; display:inline-block; margin-bottom: 0px !important;\"> <div class=\"progress-bar progress-bar-danger\" data-transitiongoal=\"0\"></div></div>";
											echo "  <div class=\"progress\" style=\"width:45%; display:inline-block; margin-bottom: 0px !important;\"> <div class=\"progress-bar progress-bar-success\" data-transitiongoal=\"".number_format(abs($indicador['factor']*100),0,"","")."\"></div></div>";
										}	  
										echo "	  </td>
											  <td>".$c->criterio->obtenerCodigo($indicador['idCriterio'])."</td>
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
			else { 
				//Pantalla por defecto: gestor de materias-grupos
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-users"></i> Gestor de materias</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newGrupo" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo grupo</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestra la relación de materias impartidas por el docente.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Id</th>
								  <th>Código</th>
								  <th>Nombre</th>
								  <th>Grupo</th>
								  <th width="100">Nº alumnos</th>
								  <th width="100">Nº evidencias registradas</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  
								$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
								$grupos=$c->grupo->listar();
								if(!$grupos) {
									echo "<tr><td colspan=\"7\">Aún no has creado ningún grupo</td></tr>";
								}
								foreach($grupos as $grupo){
									$c->grupo->cargarAlumnos($grupo['id']);
									$t=$c->grupo->alumnos->listar();
									$numeroAlus=count($t);
									$numeroEvidencias=0;
									foreach($t as $alu){
										$numeroEvidencias+=$c->grupo->alumnos->listarIndicadores($alu['id'],"",true);
									}
									
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editGrupo&id=".$grupo['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteGrupo&id=".$grupo['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$grupo['id']."</td>
										  <td>".$grupo['codigo']."</td>
										  <td>".$grupo['nombre']."</td>
										  <td>".$grupo['grupo']."</td>
										  <td style=\"text-align:right;\">".$numeroAlus." &nbsp; <a href=\"alumnos.php?grupo=".$grupo['id']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-users\"></i></a></td>
										  <td style=\"text-align:right;\">".$numeroEvidencias." &nbsp; <a href=\"?funcion=editGrupo&id=".$grupo['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-eye\"></i></a></td>
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
<?php include("include/footer.php");
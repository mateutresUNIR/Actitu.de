<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	indicadores.php
//	Propósito: 	Pantalla Mantenimiento de centros - Indicadores

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

$titulo = "Configuración indicadores del centro";
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
	include("include/footer.php");
	exit();
}

//Métodos
if(@$_POST['funcion']=="importCSVProc"){
	//Procesado de importación de fichero CSV
	$c->cargarIndicadores(@$_POST['centro']);
	if(strtolower(substr(@$_FILES['csv']['name'],-3))=="csv" && file_exists(@$_FILES['csv']['tmp_name'])){
		//Leer fichero CSV
		$csv=fopen(basename(@$_FILES['csv']['tmp_name']), "r");
		$cabecera=@fgets($csv);
		if(trim($cabecera)!="Nombre;Factor;Criterio") {
			echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">Error: el formato de la cabecera del fichero no es correcto.</div>";
		}
		else {
			$c->cargarCriterios(@$_POST['centro']);
			$criterios=$c->criterio->listar();
			$cuantos=0;
			while(!feof($csv)) {
				$t=str_getcsv(fgets($csv), ";");
				if(count($t)==3) {
					$criterioexist=0;
					foreach ($criterios as $criterio){
						if($criterio['codigo']==$t[2])
							$criterioexist=$criterio['id'];
					}
					$c->indicador->nuevo($t[0],"question",$t[1],$criterioexist);
					$cuantos++;
				}
			}
			echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
			Se han importado $cuantos elementos. Por favor, debes de revisar la asignación a un criterio de evaluación y a un icono.</div>";
		}
		fclose($csv);
	}
	else {
		echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">Error: no se ha podido importar el fichero CSV.</div>";
	}
}
elseif(@$_POST['funcion']=="newIndicadorProc"){
	//Procesado de nuevo indicador
	$c->cargarIndicadores(@$_POST['centro']);
	if($c->indicador->nuevo(@$_POST['nombre'],@$_POST['icono'],@$_POST['factor'],@$_POST['criterio'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el indicador \"".@$_POST['nombre']."\" con éxito</div>";
	}
}
elseif(@$_POST['funcion']=="editIndicadorProc"){
	//Procesado de edición de indicador existente
	$c->cargarIndicadores(@$_POST['centro']);
	if(    $c->indicador->modificarNombre(@$_POST['id'],@$_POST['nombre'])
		&& $c->indicador->modificarIcono(@$_POST['id'],@$_POST['icono'])
		&& $c->indicador->modificarFactor(@$_POST['id'],@$_POST['factor'])
		&& $c->indicador->modificarCriterio(@$_POST['id'],@$_POST['criterio'])
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el indicador \"".@$_POST['nombre']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteIndicadorProc"){
	//Procesado de eliminación de indicador existente
	$c->cargarIndicadores(@$_POST['centro']);
	if ($c->indicador->borrar($_POST['id'])) {
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha borrado el indicador con éxito</div>";
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
			if (@$_GET['funcion']=="newIndicador"){ 
				//Nuevo indicador
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Gestor de indicadores del centro: Nuevo elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para configurar un indicador para el centro.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono">Icono <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="icono" required="required">
											<?php
												$icons=Array('asterisk','bus','bolt','car','camera','calendar','check','clock-o','cloud','comments','cutlery','envelope','exclamation','eye','exchange','eye','eye-slash','fire','flash','futbol','frown-o','gamepad','gears','group','headphones','info','home','life-bouy','lock','line-chart','meh-o','microphone','microphone-slash','mobile','music','moon-o','paper-plane','power-off','question','refresh','retweet','shield','share-alt','smile-o','sliders','star','thumb-tack','thumbs-down','thumbs-up','times','trophy','users','user','volume-up','warning','wrench');
												for($i=0;$i<=55;$i++){
													echo "<option ";
													//if($c->nota->obtenerNota(@$_GET['letra'],false)==($i/10)) echo "selected";
													echo " value=\"".$icons[$i]."\">".$icons[$i]."</option>";
												}

											?>
									  </select>
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="criterio">Criterio de evaluación <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="criterio" required="required">
											<?php
												$c->cargarCriterios($_SESSION['centro']);
												$criterios=$c->criterio->listar();
												foreach($criterios as $criterio){
													echo "<option ";
													//if($c->nota->obtenerNota(@$_GET['letra'],false)==($i/10)) echo "selected";
													echo " value=\"".$criterio['id']."\">".$criterio['codigo'].": ".$criterio['nombre']."</option>";
												}
											?>
									  </select>
									</div>
								  </div>

								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="factor">Factor de ponderación <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="factor" required="required">
											<?php
												for($i=-10;$i<=10;$i++){
													echo "<option ";
													if($i==0) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='indicadores.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo elemento</button>
									</div>
								  </div>
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="newIndicadorProc">

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
							<h2><i class="fa fa-eye"></i> Gestor de indicadores del centro: Importación de elementos (CSV)</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Puedes importar una tabla de indicadores existentes a partir de un fichero CSV.</p>
							<p>La primera línea del fichero debe de contener el nombre de los campos: "Nombre;Factor;Criterio". La segunda y posteriores líneas se corresponderán cada una a un indicador distinto.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post" enctype="multipart/form-data">

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
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="importCSVProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editIndicador"){ 
				//Edición de indicador exsitente
				$c->cargarIndicadores($_SESSION['centro']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Gestor de indicadores del centro: Modificar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, modifica los campos que se requieren para actualizar los datos del centro en la plataforma Actitu.de.</p>
							
						    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <input type="text" id="nombre" name="nombre" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $c->indicador->obtenerNombre(@$_GET['id']); ?>">
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="icono">Icono <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="icono" required="required">
											<?php
												$icons=Array('asterisk','bus','bolt','car','camera','calendar','check','clock-o','cloud','comments','cutlery','envelope','exclamation','eye','exchange','eye','eye-slash','fire','flash','futbol','frown-o','gamepad','gears','group','headphones','info','home','life-bouy','lock','line-chart','meh-o','microphone','microphone-slash','mobile','music','moon-o','paper-plane','power-off','question','refresh','retweet','shield','share-alt','smile-o','sliders','star','thumb-tack','thumbs-down','thumbs-up','times','trophy','users','user','volume-up','warning','wrench');
												for($i=0;$i<=55;$i++){
													echo "<option ";
													if($c->indicador->obtenerIcono(@$_GET['id'])==$icons[$i]) echo "selected";
													echo " value=\"".$icons[$i]."\">".$icons[$i]."</option>";
												}

											?>
									  </select>
									</div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="criterio">Criterio de evaluación <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="criterio" required="required">
											<?php
												$c->cargarCriterios($_SESSION['centro']);
												$criterios=$c->criterio->listar();
												foreach($criterios as $criterio){
													echo "<option ";
													if($c->indicador->obtenerCriterio(@$_GET['id'])==$criterio['id']) echo "selected";
													echo " value=\"".$criterio['id']."\">".$criterio['codigo'].": ".$criterio['nombre']."</option>";
												}
											?>
									  </select>
									</div>
								  </div>

								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="factor">Factor de ponderación <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="factor" required="required">
											<?php
												for($i=-10;$i<=10;$i++){
													echo "<option ";
													if($c->indicador->obtenerFactor(@$_GET['id'])==($i/10)) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									</div>
								  </div>
								  
								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='indicadores.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar elemento</button>
									</div>
								  </div>
								  
								  <input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="editIndicadorProc">

								</form>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteIndicador" && @$_GET['id'] && @$_GET['centro']>0){ 
				//Eliminación de indicador existente
				$c->cargarIndicadores(@$_GET['centro']);
			?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Gestor de indicadores del centro: Borrar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el indicador "<?php echo $c->indicador->obtenerNombre(@$_GET['id']); ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
									<a href="?"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="centro" value="<?php echo @$_GET['centro']; ?>">
									<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>">
									<input type="hidden" name="funcion" value="deleteIndicadorProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: listado de indicadores
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-eye"></i> Gestor de indicadores del centro</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newIndicador" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo elemento</a></li>
							  <li><a href="?funcion=importCSV" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-table"></i> Importación elementos (CSV)</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestra la relación de indicadores de actitud configurados para el centro.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th width="60">Id</th>
								  <th width="200">Icono</th>
								  <th>Nombre</th>
								  <th width="60">Factor</th>
								  <th>Criterio</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  
								$c->cargarIndicadores($_SESSION['centro']);
								$c->cargarCriterios($_SESSION['centro']);
								$indicadores=$c->indicador->listar();
								if(!@$indicadores){
									echo "<tr><td colspan=\"4\">No existen aún indicadores para este centro.</td></tr>";
								}
								foreach($indicadores as $indicador){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editIndicador&id=".$indicador['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteIndicador&id=".$indicador['id']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
										  <td>".$indicador['id']."</td>
										  <td><i class=\"fa fa-".$indicador['icono']."\"></i> ".$indicador['icono']."</td>
										  <td>".$indicador['nombre']."</td>
										  <td>".number_format($indicador['factor'],2,'.','')."</td>";
									if(!$c->criterio->obtenerCodigo($indicador['idCriterio']))
										echo "<td><span class=\"label label-danger\">No vinculado a ningún criterio de evaluación</span></>";
									else
										echo "	<td>".$c->criterio->obtenerCodigo($indicador['idCriterio']).": ".$c->criterio->obtenerNombre($indicador['idCriterio'])."</td>";
									echo"	</tr>";
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
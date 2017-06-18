<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	notas.php
//	Propósito: 	Pantalla Mantenimiento de centros - Notas

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

$titulo = "Configuración notas del centro";
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
if(@$_POST['funcion']=="newNotaProc"){
	//Procesado de nueva nota
	$c->cargarNotas(@$_POST['centro']);
	if($c->nota->nuevo(@$_POST['letra'],@$_POST['notaMin'],@$_POST['notaMax'])){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha añadido el elemento de notas \"".@$_POST['letra']."\" con éxito</div>";
	}
}
elseif(@$_POST['funcion']=="editNotaProc"){
	//Procesado de edición de nota existente
	$c->cargarNotas(@$_POST['centro']);
	if(    $c->nota->modificarNota(@$_POST['letra'],@$_POST['notaMin'],true)
		&& $c->nota->modificarNota(@$_POST['letra'],@$_POST['notaMax'],false)
	   ){
		echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
		Se ha modificado el elemento de notas \"".@$_POST['letra']."\" con éxito</div>";
	}	
}
elseif(@$_POST['funcion']=="deleteNotaProc"){
	//Procesado de eliminación de nota existente
	$c->cargarNotas(@$_POST['centro']);
	if ($c->nota->borrar($_POST['letra'])) {
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
			/* Personalización de pantallas */
			if (@$_GET['funcion']=="newNota"){ 
				//Nuevo elemento de nota
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-calculator"></i> Gestor de notas del centro: Nuevo elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, introduce los campos que se requieren para configurar un elemento de nota para el centro.</p>
							
							    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="letra">Letra <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="letra" required="required">
											<?php
												for($i=65;$i<=90;$i++){
													echo "<option ";
													//if($centro['id']==$u->obtenerCentro(@$_GET['id'])) echo "selected";
													echo " value=\"".chr($i)."\">".chr($i)."</option>";
												}

											?>
									  </select>									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="notaMin">Nota Mínima <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="notaMin" required="required">
											<?php
												for($i=0;$i<=100;$i++){
													echo "<option ";
													//if($centro['id']==$u->obtenerCentro(@$_GET['id'])) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									  </div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="notaMax">Nota Máxima <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="notaMax" required="required">
											<?php
												for($i=0;$i<=100;$i++){
													echo "<option ";
													//if($centro['id']==$u->obtenerCentro(@$_GET['id'])) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='notas.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Alta nuevo elemento</button>
									</div>
								  </div>
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="newNotaProc">

								</form>
								
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="editNota"){ 
				//Edición de nota existente
				$c->cargarNotas($_SESSION['centro']);
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-calculator"></i> Gestor de notas del centro: Modificar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>Por favor, modifica los campos que se requieren para actualizar los datos del centro en la plataforma Actitu.de.</p>
							
						    <form id="centro" data-parsley-validate class="form-horizontal form-label-left" action="?" method="post">

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="letra">Letra <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="letra" required="required">
										<option value="<?php echo @$_GET['letra']; ?>"><?php echo @$_GET['letra']; ?></option>
									  </select>
									</div>
								  </div>
								  
								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="notaMin">Nota Mínima <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="notaMin" required="required">
											<?php
												for($i=0;$i<=100;$i++){
													echo "<option ";
													if($c->nota->obtenerNota(@$_GET['letra'],true)==($i/10)) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									  </div>
								  </div>

								  <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="notaMax">Nota Máxima <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <select class="form-control" name="notaMax" required="required">
											<?php
												for($i=0;$i<=100;$i++){
													echo "<option ";
													if($c->nota->obtenerNota(@$_GET['letra'],false)==($i/10)) echo "selected";
													echo " value=\"".number_format($i/10,2,'.','')."\">".number_format($i/10,2,'.','')."</option>";
												}

											?>
									  </select>
									</div>
								  </div>

								  <div class="ln_solid"></div>
								  <div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									  <button class="btn btn-primary" type="button" onclick="location.href='notas.php';">Cancelar</button>
									  <button type="submit" class="btn btn-success">Modificar elemento</button>
									</div>
								  </div>
								  <input type="hidden" name="centro" value="<?php echo $_SESSION['centro']; ?>">
								  <input type="hidden" name="funcion" value="editNotaProc">

								</form>
						  </div>
						</div>
					  </div>
		<?php 
			}
			elseif (@$_GET['funcion']=="deleteNota" && @$_GET['letra'] && @$_GET['centro']>0){ 
				//Eliminación de nota existente
				?>
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-calculator"></i> Gestor de notas del centro: Borrar elemento</h2>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<div class="alert alert-danger alert-dismissible fade in" role="alert">¿ Estás seguro que deseas borrar el elemento "<?php echo @$_GET['letra']; ?>" ?</div>
							<div style="float: right;">
								<form method="POST" action="?" id="formDelete">
									<a href="notas.php"><button type="button" class="btn btn-default">No</button></a>
									<a href="#"><button type="submit" class="btn btn-danger">Sí</button></a>
									<input type="hidden" name="letra" value="<?php echo @$_GET['letra']; ?>">
									<input type="hidden" name="centro" value="<?php echo @$_GET['centro']; ?>">
									<input type="hidden" name="funcion" value="deleteNotaProc">
								</form>
							</div>
						  </div>
						</div>
					  </div>
		<?php 
			}
			else { 
				//Pantalla por defecto: Listado de notas
				?>
			
					  <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
						  <div class="x_title">
							<h2><i class="fa fa-calculator"></i> Gestor de notas del centro</small></h2>
							<ul class="nav navbar-right panel_toolbox">
							  <li><a href="?funcion=newNota" class="dropdown-toggle" role="button" aria-expanded="false"><i class="fa fa-plus"></i> Alta nuevo elemento</a></li>
							</ul>
							<div class="clearfix"></div>
						  </div>
						  <div class="x_content">
							<p>En este listado se muestra la relación de notas alfabéticas habilitadas para el centro y el rango equivalente numérico sobre 10.</p>
							<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							  <thead>
								<tr>
								 <th>&nbsp;</th>
								  <th>Letra</th>
								  <th>Nota mínima</th>
								  <th>Nota máxima</th>
								</tr>
							  </thead>
							  <tbody>
							  <?php
							  
								$c->cargarNotas($_SESSION['centro']);
								$notas=$c->nota->listar();
								if(!@$notas){
									echo "<tr><td colspan=\"4\">No existen aún notas para este centro.</td></tr>";
								}
								foreach($notas as $nota){
									echo"<tr>
										  <td style=\"width: 60px;\">
											<a href=\"?funcion=editNota&letra=".$nota['letra']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-edit\"></i></a>
											<a href=\"?funcion=deleteNota&letra=".$nota['letra']."&centro=".$_SESSION['centro']."\" class=\"dropdown-toggle\" role=\"button\" aria-expanded=\"false\"><i class=\"fa fa-trash\"></i></a>
										  </td>
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
			<?php
				}
			?>
          </div>
<?php include("include/footer.php");
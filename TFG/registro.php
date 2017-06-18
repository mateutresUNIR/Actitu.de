<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	registro.php
//	Propósito: 	Pantalla profesor para la consulta de evidencias de un grupo

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
							
?>
 <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include('include/menu.php'); ?>
		
		<div class="right_col" role="main">
		  <div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
			  <div class="x_title">
				<h2><i class="fa fa-users"></i> Registro de evidencias</small></h2>
				<ul class="nav navbar-right panel_toolbox">
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
				<p>En este listado se muestra la relación de evidencias registradas para esta materia-grupo.</p>
				<p><input type="text" disabled class="form-control col-md-7 col-xs-12" value="<?php echo $c->grupo->obtenerNombre(@$_GET['grupo']); ?>"></p>
				&nbsp;
				<?php
					$c->cargarGrupos($_SESSION['centro'],$_SESSION['id']);
					$c->grupo->cargarAlumnos($_GET['grupo']);
					
					$alus=$c->grupo->alumnos->listar();
					$fechaMin="";
					$fechaMax="";
					foreach ($alus as $alu){
						$aluMin=$c->grupo->alumnos->fechaIndicadores($alu['id'],false);
						$aluMax=$c->grupo->alumnos->fechaIndicadores($alu['id'],true);
						if (!$fechaMin or $fechaMin>$aluMin)
							$fechaMin=$aluMin;
						if (!$fechaMax or $fechaMax<$aluMax)
							$fechaMax=$aluMax;
					}
				?>
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
										
										$fechaActual=$fechaMin;
										while($fechaActual<=$fechaMax) {
											$cuantos=0;
											foreach ($alus as $alu){
												$cuantos+=$c->grupo->alumnos->listarIndicadores($alu['id'],"",true,$fechaActual);
											}
											if($cuantos>0)
												echo "<th rowspan=\"2\">".date('Y',strtotime($fechaActual))."<br>".date('m-d',strtotime($fechaActual))."</th>";
											$fechaActual=date('Y-m-d', strtotime($fechaActual.' +1 day'));
										}
									  ?>
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
										
										$fechaActual=$fechaMin;
										while($fechaActual<=$fechaMax) {
											$cuantos=0;
											foreach ($alus as $alu){
												$cuantos+=$c->grupo->alumnos->listarIndicadores($alu['id'],"",true,$fechaActual);
											}
											if($cuantos>0){
												echo "<td>";
												$observaciones=$c->grupo->alumnos->listarIndicadoresNombres($alumno['id'],$criterio['id'],date('m/d/Y',strtotime($fechaActual)));
												foreach($observaciones as $observacion){
													echo "<div><i class=\"fa fa-".$observacion['icono']."\" title=\"".$observacion['nombre']." (".$observacion['factor'].")\"></i></div>";
												}
												echo "</td>";
											}
											$fechaActual=date('Y-m-d', strtotime($fechaActual.' +1 day'));
										}

										echo "	</tr>";
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
          </div>
<?php include("include/footer.php");
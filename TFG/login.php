<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	login.php
//	Propósito: 	Pantalla de inicio de sesión/registro y métodos POST

require_once('include/definevars.php');
//require_once('include/sesion.php');
require_once('include/MysqliDb.php');
require_once('class/alumno.php');
require_once('class/centro.php');
require_once('class/criterio.php');
require_once('class/grupo.php');
require_once('class/indicador.php');
require_once('class/nota.php');
require_once('class/usuario.php');

$titulo = "Inicio de sesión";
include('include/header.php');

@session_start();

$u=new Usuario($dbData);

$fallologin=false;
//Métodos POST
if(@$_POST['funcion']=="loginUsuario"){
	//Proceso de inicio de sesión
	$id=$u->comprobarContrasena(@$_POST['email'],@$_POST['contrasena']);
	if($id>0) {
		//cfg parámetros sesión
		$_SESSION=Array();
		$_SESSION['id']=$id;
		$_SESSION['superadmin']=$u->obtenerSuperadmin($id);
		$_SESSION['gestorcentro']=$u->obtenerGestorCentro($id);
		$_SESSION['centro']=$u->obtenerCentro($id);
		echo "<script type=\"text/javascript\"> location.href=\"index.php\"; </script>";
	}
	else
		$fallologin=true;
}
elseif(@$_GET['funcion']=="logout"){
	//Proceso de cierre de sesión
	$_SESSION=Array();
}
elseif(@$_POST['funcion']=="newUsuario"){
	//Proceso de registro de usuario
	$registro=false;
	if($u->nuevo(@$_POST['nombre'],@$_POST['email'],@$_POST['contrasena']))
		$registro=true;
}
?>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="POST">
			  <input type="hidden" name="funcion" value="loginUsuario">
              <h1>Inicio de sesión</h1>
              <div>
				<h1><i class="glyphicon glyphicon-eye-open"></i> Actitu.de</h1>
			  </div>
			  <?php
				if ($fallologin)
					echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
					Usuario y/o contraseña incorrectos</div>";
				elseif (@$_POST['funcion']=="newUsuario" && !$registro)
					echo "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
					Ha ocurrido un error al registar el nuevo usuario</div>";
				elseif (@$_POST['funcion']=="newUsuario" && $registro)
					echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span></button>
					Se ha registrado el nuevo usuario con éxito</div>";
			  ?>
              <div>
                <input type="text" class="form-control" placeholder="Usuario/email" required="" name="email"/>
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Contraseña" required="" name="contrasena"/>
              </div>
              <div>
				<button type="submit" class="btn btn-success">Iniciar sesión</button>

              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">¿Eres nuevo en este sitio?
                  <a href="#signup" class="to_register"> Crear cuenta </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <?php echo $licenseP; ?>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form method="POST" action="#signin">
				<input type="hidden" name="funcion" value="newUsuario">
              <h1>Nueva cuenta</h1>
              <div>
				<h1><i class="glyphicon glyphicon-eye-open"></i> Actitu.de</h1>
			  </div>
              <div>
                <input type="text" class="form-control" placeholder="Nombre y apellidos" required="" name="nombre"/>
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" name="email"/>
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Contraseña" required="" name="contrasena"/>
              </div>
              <div>
				<button type="submit" class="btn btn-success">Crear nuevo usuario</button>

              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Ya eres miembro de Actitu.de ?
                  <a href="#signin" class="to_register"> Iniciar sesión </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <?php echo $licenseP; ?>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>

<?php include("include/footer2.php");
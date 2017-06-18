<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	include/sesion.php
//	Propósito: 	Control de sesión común para todas las páginas

@session_start();

//Consulta si existe campo id usuario en los parámetros de sesión
if(!@$_SESSION['id']) {
	$_SESSION=Array();
	echo "<script type=\"text/javascript\"> location.href=\"login.php\"; </script>";
	exit();
}
	
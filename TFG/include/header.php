<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	include/header.php
//	Propósito: 	Cabecera común para todo el proyecto

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Actitu.de<?php echo (strlen(@$titulo) > 0) ? ' | '.$titulo : ''; ?></title>

    <link href="<?php echo $relPlant; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <link href="<?php echo $relPlant; ?>/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $relPlant; ?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $relPlant; ?>/vendors/switchery/dist/switchery.min.css" rel="stylesheet">


    <link href="<?php echo $relPlant; ?>/build/css/custom.min.css" rel="stylesheet">
  </head>
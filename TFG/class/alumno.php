<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/alumno.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de alumnos

class Alumno {

	//	Variables de clase
	private $dbData; // Parámetros de conexión a base de datos MySQL
	private $db; // Variable de instancia de conexión a base de datos MySQL
	private $grupo; // Variable id de grupo para contextualizar datos a tratar


	/*	Función de construcción de la instancia
		@Param dbData, vector con los datos de conexión a base de datos MySQL
		@Param grupo, id de grupo para contextualizar datos a tratar
	*/
	function __construct($dbData,$grupo) {
		$this->dbData=$dbData;
		$this->grupo=$grupo;
		$this->db = new MySqliDb($this->dbData);
	}
		

	/*	Función para listar todos los alumnos contextualizados en la función de construcción
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el nombre (string) y apellidos (string)
	*/	
	function listar(){
		$campos=Array("id","nombre","apellidos");
		$this->db->where("grupomateria",$this->grupo);
		$this->db->orderby("apellidos","asc");
		$this->db->orderby("nombre","asc");
		$this->db->orderby("id","asc");
		return $this->db->get("alumnos",null,$campos);
	}
	

	/*	Función para modificar el campo apellidos de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarApellidos($id,$valor){
			return $this->modificarCampo($id,"apellidos",$valor);
	}
	
	
	/*	Función para modificar el campo nombre de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNombre($id,$valor){
			return $this->modificarCampo($id,"nombre",$valor);
	}


	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}


	/*	Función para obtener el campo apellidos de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerApellidos($id){
		return $this->obtenerCampo($id,"apellidos");
	}
	

	/*	Función para obtener el conjunto de campos apellidos y nombre en un string de un elemento
		@Param id (int) del elemento a consultar
		@Return campo combinado en formato "APELLIDOS, NOMBRE" (string), false en cualquier otro caso
	*/
	function obtenerApellidosNombre($id){
		return $this->obtenerCampo($id,"apellidos").", ".$this->obtenerCampo($id,"nombre");
	}
	

	/*	Función para crear un nuevo grupo contextualizado
		@Param nombre (string) del nuevo elemento
		@Param apellidos (string) del nuevo elemento
		@Param grupo (string) del nuevo elemento
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($nombre,$apellidos,$grupo){
		$campos=Array(
			"nombre" => $nombre,
			"apellidos" => $apellidos,
			"grupomateria" => $this->grupo
		);
		return $this->db->insert("alumnos",$campos);
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id);
		return $this->db->delete("alumnos");
	}

	
	/*	Función para crear un nuevo elemento indicador
		@Param id (int) del alumno
		@Param indicador (int) del indicador
		@Param fecha (string) del indicador
		@Return true si se ha creado correctamente, false en cualquier otro caso
	*/
	function nuevoIndicador($id,$indicador,$fecha){
		$campos=Array(
			"alumno" => $id,
			"indicador" => $indicador,
			"fecha" => $this->fechaAMysql($fecha)
		);
		return $this->db->insert("registroindicadores",$campos);
	}


	/*	Función para borrar un elemento indicador
		@Param id (int) del alumno
		@Param indicador (int) del elemento indicador a borrar
		@Param fecha (string) del elemento indicador a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrarIndicador($id,$indicador,$fecha){
		$this->db->where("alumno",$id)->where("indicador",$indicador)->where("fecha",$this->fechaAMysql($fecha));
		return $this->db->delete("registroindicadores");

	}
	
	
	/*	Función para listar todos los indicadores contextualizados a un alumno y fecha concretos
		@Param id (int) de alumno
		@Param criterio (int) de criterio ** SIN USO **
		@Param fecha (string) indica fecha de asignación
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int) de indicador, el nombre (string), el icono (string), el factor (float) y el id (int) del criterio
	*/	
	function listarIndicadoresNombres($id,$criterio,$fecha){
		$this->db->join("indicadores i","i.id=r.indicador","LEFT");
		$this->db->where("alumno",$id);
		if($fecha)
			$this->db->where("fecha",$this->fechaAMysql($fecha));
		return $this->db->get("registroindicadores r",null,"r.indicador, i.nombre, i.icono, i.factor, i.idCriterio");
	}
	

	/*	Función para obtener el valor máximo de suma de indicadores de un alumno para un grupo
		@Param criterio (int) indica para qué criterio se deben de consultar los indicadores
		@Return valor máximo (int)
	*/	
	function indicadorMaximoClase($criterio){
		$max=-999;
		$us=$this->listar();
		foreach($us as $u){
			$n=$this->listarIndicadores($u['id'],$criterio,false);
			if($n>$max)	$max=$n;
		}
		return $max;
	}
	

	/*	Función para obtener el valor mínim de suma de indicadores de un alumno para un grupo
		@Param criterio (int) indica para qué criterio se deben de consultar los indicadores
		@Return valor mínimo (int)
	*/
	function indicadorMinimoClase($criterio){
		$min=999;
		$us=$this->listar();
		foreach($us as $u){
			$n=$this->listarIndicadores($u['id'],$criterio,false);
			if($n<$min)	$min=$n;
		}
		return $min;
	}
	
	
	/*	Función para obtener la suma o el recuento de factores de los indicadores para un alumno y criterio
		@Param id (int) de alumno
		@Param criterio (int) indica para qué criterio se deben de consultar los indicadores
		@Param countORsum (bool) true para devolver suma, false para devolver recuento
		@Param opcional fecha (string) para filtrar el recuento o suma a una fecha en concreto
		@Return recuento o suma (int) en función de parámetro countORsum
	*/
	function listarIndicadores($id,$criterio,$countORsum,$fecha=false){
		$this->db->join("indicadores i","i.id=r.indicador","LEFT");
		$this->db->where("alumno",$id);
		if($fecha)
		if($fecha)
			$this->db->where("r.fecha",$fecha);
		$ts=$this->db->get("registroindicadores r",null,"r.indicador, i.factor, i.idCriterio");
		if($this->db->count>0){
			$count=0;
			$sum=0;
			foreach($ts as $t){
				if($t['idCriterio']==$criterio or !$criterio){
					$count++;
					$sum+=$t['factor'];
				}
			}
			if($countORsum)
				return $count;
			else	
				return $sum;
		}
		else 
			return 0;
	}
	

	/*	Función para obtener la primera o la última fecha en la que se han registrado evidencias para un alumno
		@Param alumno (int) a consultar
		@Param max (bool) true para devolver última fecha, false para devolver primera fecha
		@Return fecha (string) de primera o última ocurrencia de registro de evidencias
	*/
	function fechaIndicadores($id,$max){
		$this->db->join("indicadores i","i.id=r.indicador","LEFT");
		$this->db->where("alumno",$id);
		if($max)
			$t=$this->db->getOne("registroindicadores r","MAX(fecha)");
		else
			$t=$this->db->getOne("registroindicadores r","MIN(fecha)");
		
		if($max)
			return $t['MAX(fecha)'];
		else 
			return $t['MIN(fecha)'];
	}


	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("alumnos",null,Array($campo));
		$this->db->where("id",$id);
		$datos=$this->db->getOne("alumnos");
		return $datos[$campo];
	}
	

	/*	Función PRIVADA para modificar un campo de un elemento
		@Param id (int) del elemento a modificar
		@Param campo (string) del elemento a modificar
		@Param valor (string) del campo a modificar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	private function modificarCampo($id,$campo,$valor){
		$this->db->where("id",$id);
		return $this->db->update("alumnos",Array($campo => $valor));
	}
	

	/*	Función PRIVADA para conversión de formato de fecha
		@Param fecha (string) en formato mm/dd/yyyy
		@Return fecha (string) en formato MySQL (yyyy-mm-dd)
	*/
	private function fechaAMysql($str){
		return substr($str,6,4)."-".substr($str,0,2)."-".substr($str,3,2);
	}
}
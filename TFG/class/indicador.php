<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/indicador.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de indicadores de evaluación

class Indicador {

	//	Variables de clase
	private $dbData; // Parámetros de conexión a base de datos MySQL
	private $db; // Variable de instancia de conexión a base de datos MySQL
	private $centro; // Variable id centro para contextualizar datos a tratar

	/*	Función de construcción de la instancia
		@Param dbData, vector con los datos de conexión a base de datos MySQL
		@Param centro, id de centro para contextualizar datos a tratar
	*/
	function __construct($dbData,$centro) {
		$this->dbData=$dbData;
		$this->centro=$centro;
		$this->db = new MySqliDb($this->dbData);
	}

	
	/*	Función para listar todos los grupos contextualizados en la función del constructor
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el nombre (string), el icono (string), el factor (float) y el id de criterio (int)
	*/	
	function listar(){
		$campos=Array("id","nombre","icono","factor","idCriterio");
		$this->db->where("centro",$this->centro);
		$this->db->orderby("idCriterio","asc");
		$this->db->orderby("factor","desc");
		return $this->db->get("indicadores",null,$campos);
	}
	

	/*	Función para modificar el campo icono de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarIcono($id,$valor){
			return $this->modificarCampo($id,"icono",$valor);
	}

	
	/*	Función para modificar el campo factor de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (float) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarFactor($id,$valor){
			return $this->modificarCampo($id,"factor",$valor);
	}
	

	/*	Función para modificar el campo criterio de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (int) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarCriterio($id,$valor){
			return $this->modificarCampo($id,"idCriterio",$valor);
	}
	

	/*	Función para modificar el campo nombre de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNombre($id,$valor){
			return $this->modificarCampo($id,"nombre",$valor);
	}


	/*	Función para obtener el campo icono de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerIcono($id){
		return $this->obtenerCampo($id,"icono");
	}


	/*	Función para obtener el campo factor de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (float) del campo, false en cualquier otro caso
	*/
	function obtenerFactor($id){
		return $this->obtenerCampo($id,"factor");
	}


	/*	Función para obtener el campo idCriterio de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (int) del campo, false en cualquier otro caso
	*/
	function obtenerCriterio($id){
		return $this->obtenerCampo($id,"idCriterio");
	}

	
	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}

	
	/*	Función para crear un nuevo indicador contextualizado
		@Param icono (string) del nuevo elemento
		@Param factor (float) del nuevo elemento
		@Param idCriterio (int) del criterio al cuál se vincula
		@Param nombre (string) del nuevo elemento
		@El parámetro centro se contextualiza a los datos asociados a la clase
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($nombre,$icono,$factor,$criterio){
		$campos=Array(
			"centro" => $this->centro,
			"icono" => $icono,
			"factor" => $factor,
			"idCriterio" => $criterio,
			"nombre" => $nombre
		);
		$this->db->insert("indicadores",$campos);
		echo $this->db->getLastError();
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id)->where("centro",$this->centro);
		return $this->db->delete("indicadores");
	}
	

	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("indicadores",null,Array($campo));
		$this->db->where("id",$id)->where("centro",$this->centro);
		$datos=$this->db->getOne("indicadores");
		return $datos[$campo];
	}
	

	/*	Función PRIVADA para modificar un campo de un elemento
		@Param id (int) del elemento a modificar
		@Param campo (string) del elemento a modificar
		@Param valor (string) del campo a modificar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	private function modificarCampo($id,$campo,$valor){
		$this->db->where("id",$id)->where("centro",$this->centro);
		return $this->db->update("indicadores",Array($campo => $valor));
	}
}
<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/criterio.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de criterios de evaluación

class Criterio {

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
	
	
	/*	Función para listar todos los elementos del centro contextualizado en la función de construcción
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el código (string) y el nombre (string)
	*/
	function listar(){
		$campos=Array("id","codigo","nombre");
		$this->db->where("centro",$this->centro);
		$this->db->orderby("codigo","asc");
		return $this->db->get("criterios",null,$campos);
	}
	
	
	/*	Función para modificar el campo código de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/
	function modificarCodigo($id,$valor){
			return $this->modificarCampo($id,"codigo",$valor);
	}

	
	/*	Función para modificar el campo nombre de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNombre($id,$valor){
			return $this->modificarCampo($id,"nombre",$valor);
	}
	
	
	/*	Función para obtener el campo código de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerCodigo($id){
		return $this->obtenerCampo($id,"codigo");
	}

	
	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}
	
	
	/*	Función para crear un nuevo criterio para el centro contextualizado
		@Param codigo (string) del nuevo elemento
		@Param nombre (string) del nuevo elemento
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($codigo,$nombre){
		$campos=Array(
			"centro" => $this->centro,
			"codigo" => $codigo,
			"nombre" => $nombre
		);
		return $this->db->insert("criterios",$campos);
	}
	
	
	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id)->where("centro",$this->centro);
		return $this->db->delete("criterios");
	}
	
	
	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("criterios",null,Array($campo));
		$this->db->where("id",$id)->where("centro",$this->centro);
		$datos=$this->db->getOne("criterios");
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
		return $this->db->update("criterios",Array($campo => $valor));
	}
}
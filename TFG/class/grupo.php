<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/grupo.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de materias-grupos, así como el acceso a los datos de los alumnos pertenecientes al grupo

class Grupo {

	//	Variables de clase
	private $dbData; // Parámetros de conexión a base de datos MySQL
	private $db; // Variable de instancia de conexión a base de datos MySQL
	private $centro; // Variable id centro para contextualizar datos a tratar
	private $usuario; // Variable id de usuario para contextualizar datos a tratar
	var $alumnos; // Variable de instancia de elemento de clase Alumno

	
	/*	Función de construcción de la instancia
		@Param dbData, vector con los datos de conexión a base de datos MySQL
		@Param centro, id de centro para contextualizar datos a tratar
		@Param usuario, id de usuario para contextualizar datos a tratar
	*/
	function __construct($dbData,$centro,$usuario) {
		$this->dbData=$dbData;
		$this->centro=$centro;
		$this->usuario=$usuario;
		$this->db = new MySqliDb($this->dbData);
	}
	

	/*	Función para listar todos los grupos contextualizados en la función de construcción
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el código (string), el nombre (string) y el grupo (string)
	*/	
	function listar(){
		$campos=Array("id","codigo","nombre","grupo");
		$this->db->where("centro",$this->centro);
		$this->db->where("usuario",$this->usuario);
		$this->db->orderby("codigo","asc");
		return $this->db->get("gruposmaterias",null,$campos);
	}


	/*	Función para modificar el campo código de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarCodigo($id,$valor){
			return $this->modificarCampo($id,"codigo",$valor);
	}

	
	/*	Función para modificar el campo grupo de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/
	function modificarGrupo($id,$valor){
			return $this->modificarCampo($id,"grupo",$valor);
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


	/*	Función para obtener el campo grupo de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerGrupo($id){
		return $this->obtenerCampo($id,"grupo");
	}


	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}
	
	
	/*	Función para crear una instancia de la clase alumnos
		@Param id (int) indica el grupo que se debe de consultar
	*/
	function cargarAlumnos($id){
		$this->alumnos=new Alumno($this->dbData,$id);
	}
		

	/*	Función para crear un nuevo grupo contextualizado
		@Param codigo (string) del nuevo elemento
		@Param nombre (string) del nuevo elemento
		@Param grupo (string) del nuevo elemento
		@Los parámetros centro y usuario se contextualizan a los datos asociados a la clase
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($codigo,$nombre,$grupo){
		$campos=Array(
			"centro" => $this->centro,
			"usuario" => $this->usuario,
			"codigo" => $codigo,
			"grupo" => $grupo,
			"nombre" => $nombre
		);
		return $this->db->insert("gruposmaterias",$campos);
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id)->where("centro",$this->centro)->where("usuario",$this->usuario);
		return $this->db->delete("gruposmaterias");
	}
	

	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("gruposmaterias",null,Array($campo));
		$this->db->where("id",$id)->where("centro",$this->centro)->where("usuario",$this->usuario);
		$datos=$this->db->getOne("gruposmaterias");
		return $datos[$campo];
	}
	

	/*	Función PRIVADA para modificar un campo de un elemento
		@Param id (int) del elemento a modificar
		@Param campo (string) del elemento a modificar
		@Param valor (string) del campo a modificar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	private function modificarCampo($id,$campo,$valor){
		$this->db->where("id",$id)->where("centro",$this->centro)->where("usuario",$this->usuario);
		return $this->db->update("gruposmaterias",Array($campo => $valor));
	}
}
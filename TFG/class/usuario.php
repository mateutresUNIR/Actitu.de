<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/usuario.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de usuarios de la plataforma

class Usuario {

	//	Variables de clase
	private $dbData; // Parámetros de conexión a base de datos MySQL
	private $db; // Variable de instancia de conexión a base de datos MySQL
	

	/*	Función de construcción de la instancia
		@Param dbData, vector con los datos de conexión a base de datos MySQL
	*/
	function __construct($dbData) {
		$this->dbData=$dbData;
		$this->db = new MySqliDb($this->dbData);
	}
	

	/*	Función para listar todos los elementos de usuario
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el nombre (string), el email (string), superadmin (bool), gestorCentro (bool) y el código de centro asignado (int)
	*/
	function listar(){
		$campos=Array("id","nombre","email","superadmin","gestorCentro","idCentro");
		return $this->db->get("usuarios",null,$campos);
	}
	

	/*	Función para modificar el campo nombre de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNombre($id,$valor){
		return $this->modificarCampo($id,"nombre",$valor);
	}


	/*	Función para modificar el campo email de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarEmail($id,$valor){
		return $this->modificarCampo($id,"email",$valor);
	}
	

	/*	Función para modificar el campo idCentro de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarCentro($id,$valor){
		if ($valor=="") $valor=0;
		return $this->modificarCampo($id,"idCentro",$valor);
	}
	

	/*	Función para modificar el campo superadmin de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarSuperadmin($id,$valor){
		if ($valor=="") $valor=0;
		return $this->modificarCampo($id,"superAdmin",$valor);
	}
	

	/*	Función para modificar el campo gestorCentro de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarGestor($id,$valor){
		if ($valor=="") $valor=0;
		return $this->modificarCampo($id,"gestorCentro",$valor);
	}
	

	/*	Función para modificar el campo contraseña de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarContrasena($id,$valor){
		if (strlen($valor)==0) 
			return true;
		return $this->modificarCampo($id,"contrasena",$valor);
	}

	/*	Función para consultar si el binomio email-contraseña son correctos
		@Param email (string)
		@Param contraseña(string) 
		@Return id (int) de usuario en caso de comprobación correcta, false en cualquier otro caso
	*/	
	function comprobarContrasena($email,$contrasena){
		return $this->comprobar($email,$contrasena);
	}


	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}


	/*	Función para obtener el campo email de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerEmail($id){
		return $this->obtenerCampo($id,"email");
	}	

	
	/*	Función para obtener el campo superadmin de un elemento
		@Param id (int) del elemento a consultar
		@Return true si es superadmin, false en cualquier otro caso
	*/
	function obtenerSuperadmin($id){
		return $this->obtenerCampo($id,"superAdmin");
	}	


	/*	Función para obtener el campo gestorCentro de un elemento
		@Param id (int) del elemento a consultar
		@Return true si es gestorCentro, false en cualquier otro caso
	*/
	function obtenerGestorCentro($id){
		return $this->obtenerCampo($id,"gestorCentro");
	}	


	/*	Función para obtener el campo idCentro de un elemento
		@Param id (int) del elemento a consultar
		@Return id del centro (int) , false en cualquier otro caso
	*/
	function obtenerCentro($id){
		return $this->obtenerCampo($id,"idCentro");
	}	
	

	/*	Función para crear un nuevo usuario
		@Param nombre (string) del nuevo elemento
		@Param email (string) del nuevo elemento
		@Param contraseña (string) del nuevo elemento
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($nombre,$email,$contrasena){
		$campos=Array(
			"nombre" => $nombre,
			"email" => $email,
			"contrasena" => $this->db->func('MD5(?)',Array($contrasena))
		); 		
		return $this->db->insert("usuarios",$campos);
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id);
		return $this->db->delete("usuarios");
	}
	
	
	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("usuarios",null,Array($campo));
		$this->db->where("id",$id);
		$datos=$this->db->getOne("usuarios");
		return $datos[$campo];
	}
	

	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function comprobar($email,$contrasena){
		$this->db->get("usuarios",null,Array("id"));
		$this->db->where("email",$email)->where("contrasena",MD5($contrasena));
		$datos=$this->db->getOne("usuarios");
		return $datos['id'];
	}
	

	/*	Función PRIVADA para modificar un campo de un elemento
		@Param id (int) del elemento a modificar
		@Param campo (string) del elemento a modificar
		@Param valor (string) del campo a modificar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	private function modificarCampo($id,$campo,$valor){
		$this->db->where("id",$id);
		return $this->db->update("usuarios",Array($campo => $valor));
	}
}
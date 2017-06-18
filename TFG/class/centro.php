<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/centro.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de los centros educativos, así como el acceso a los datos de materias-grupos, indicadores, criterios y notas

class Centro {

	//	Variables de clase
	private $dbData; // Parámetros de conexión a base de datos MySQL
	private $db; // Variable de instancia de conexión a base de datos MySQL	var $nota;
	var $criterio; // Variable de instancia de elemento de clase Criterio
	var $indicador; // Variable de instancia de elemento de clase Indicador
	var $grupo; // Variable de instancia de elemento de clase Grupo


	/*	Función de construcción de la instancia
		@Param dbData, vector con los datos de conexión a base de datos MySQL
	*/
	function __construct($dbData) {
		$this->dbData=$dbData;
		$this->db = new MySqliDb($this->dbData);
	}
	
	
	/*	Función para listar todos los centros
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve el id (int), el nombre (string), la población (string), el código (string) y la clave (string)
	*/	
	function listar(){
		$campos=Array("id","nombre","poblacion","codigo","clave");
		return $this->db->get("centros",null,$campos);
	}
	

	/*	Función para modificar el campo nombre de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNombre($id,$valor){
		return $this->modificarCampo($id,"nombre",$valor);
	}


	/*	Función para modificar el campo población de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarPoblacion($id,$valor){
		return $this->modificarCampo($id,"poblacion",$valor);
	}


	/*	Función para modificar el campo codigo de un elemento
		@Param id (int) del elemento a modificar
		@Param nuevo valor (string) 
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarCodigo($id,$valor){
		return $this->modificarCampo($id,"codigo",$valor);
	}


	/*	Función para obtener el campo nombre de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombre($id){
		return $this->obtenerCampo($id,"nombre");
	}

	
	/*	Función para obtener un conjunto de campos en formato string de un elemento
		@Param id (int) del elemento a consultar
		@Return combinación de campos en forma de "NOMBRE (CODIGO)" (string) del campo, false en cualquier otro caso
	*/
	function obtenerNombreYCodigo($id){
		if ($id==0)
			return false;
		return $this->obtenerCampo($id,"nombre")." (".$this->obtenerCampo($id,"codigo").")";
	}


	/*	Función para obtener el campo código de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerCodigo($id){
		return $this->obtenerCampo($id,"codigo");
	}	
	

	/*	Función para obtener el campo clave de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerClave($id){
		return $this->obtenerCampo($id,"clave");
	}	
	

	/*	Función para obtener el campo población de un elemento
		@Param id (int) del elemento a consultar
		@Return valor (string) del campo, false en cualquier otro caso
	*/
	function obtenerPoblacion($id){
		return $this->obtenerCampo($id,"poblacion");
	}
	

	/*	Función para crear un nuevo centro
		@Param nombre (string) del nuevo elemento
		@Param poblacion (string) del nuevo elemento
		@Param codigo (string) del nuevo elemento
		@El parámetro clave se genera aleatóriamente (8 caracteres)
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($nombre,$poblacion,$codigo){
		$campos=Array(
			"nombre" => $nombre,
			"poblacion" => $poblacion,
			"codigo" => $codigo,
			"clave" => $this->randomString(8)
		);
		return $this->db->insert("centros",$campos);
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($id) {
		$this->db->where("id",$id);
		return $this->db->delete("centros");
	}
	

	/*	Función para crear una instancia de la clase Nota
		@Param id (int) indica el centro al que se debe de contextualizar
	*/
	function cargarNotas($id){
		$this->nota = new Nota($this->dbData,$id);
	}	
	

	/*	Función para crear una instancia de la clase Criterio
		@Param id (int) indica el centro al que se debe de contextualizar
	*/
	function cargarCriterios($id){
		$this->criterio = new Criterio($this->dbData,$id);
	}	
	
	
	/*	Función para crear una instancia de la clase Grupo
		@Param id (int) indica el centro al que se debe de contextualizar
		@Param usuario (int) indica el usuario al que se debe de contextualizar
	*/
	function cargarGrupos($id,$usuario){
		$this->grupo = new Grupo($this->dbData,$id,$usuario);
	}
		

	/*	Función para crear una instancia de la clase Indicador
		@Param id (int) indica el centro al que se debe de contextualizar
	*/	
	function cargarIndicadores($id){
		$this->indicador = new Indicador($this->dbData,$id);
	}
	

	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("centros",null,Array($campo));
		$this->db->where("id",$id);
		$datos=$this->db->getOne("centros");
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
		return $this->db->update("centros",Array($campo => $valor));
	}

	/*	Función PRIVADA para generar cadenas string aleatorias
		@Param longitud(int) de la cadena resultante
		@Return (string) aleatorio de longitud determinada 
	*/
	private function randomString($longitud){
		$caracteres=str_split('23456789ABCDEFGHJKLMNPRSTUVWXYZ');
		$rs="";
		for($i=0;$i<$longitud;$i++){
			$rs.=$caracteres[rand(0,30)];
		}
		return $rs;
	}
}
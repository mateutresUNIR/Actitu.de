<?php
//	Trabajo Final de Grado Ingenieria - Universidad Internacional de La Rioja (UNIR)
//		Actitu.de – Herramienta para la adquisición de evidencias en la actitud de los alumnos 
//	
//	Autor:		Mateu Tres Bosch
//  Fecha:		Junio 2017
//	Licencia:	Creative Commons Reconocimiento – NoComercial – SinObraDerivada (by-nc-nd)
//	Fichero: 	class/nota.php
//	Propósito: 	Clase para el acceso y la manipulación de datos de nota

class Nota {

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
		

	/*	Función para listar todos los rangos de nota contextualizados en la función de construcción
		@Return vector multidimensional asociativo con todos los elementos, para cada elemento devuelve una letra (string), la nota mínima (float) y la nota máxima (float)
	*/	
	function listar(){
		$campos=Array("letra","notaMin","notaMax");
		$this->db->where("centro",$this->centro);
		$this->db->orderby("letra","asc");
		return $this->db->get("notasletras",null,$campos);
	}
	
	
	/*	Función para realizar el cálculo de nota numérica a nota alfabética, según parámetros contextualizados para el centro
		@Param nota (float)
		@Return letra (string) que le corresponde a la nota numérica
	*/
	function calcularLetra($nota){
		$this->db->get("notasletras",null,Array("letra"));
		$this->db->where("notaMin",$nota,"<=")->where("notaMax",$nota,">=")->where("centro",$this->centro);
		$this->db->orderby("letra","desc");
		$datos=$this->db->getOne("notasletras");
		return $datos['letra'];
	}

	
	/*	Función para modificar un elemento
		@Param letra (string) del elemento a modificar
		@Param valor (float) del campo a modificar
		@Param min (bool), true si se desea modificar valor mínimo, false si se desea modificar valor máximo
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function modificarNota($letra,$valor,$min){
		if($min)
			return $this->modificarCampo($letra,"notaMin",$valor);
		else
			return $this->modificarCampo($letra,"notaMax",$valor);
	}


	/*	Función para obtener el campo notaMin/notaMax de un elemento
		@Param letra (string) del elemento a consultar
		@Param min (bool), true si se desea consultar valor mínimo, false si se desea consultar valor máximo
		@Return valor (float) del campo notaMin o notaMax, false en cualquier otro caso
	*/
	function obtenerNota($letra,$min){
		if($min)
			return $this->obtenerCampo($letra,"notaMin");
		else
			return $this->obtenerCampo($letra,"notaMax");
	}
		

	/*	Función para crear un nuevo elemento
		@Param letra (string) del nuevo elemento
		@Param notamin (float) del nuevo elemento
		@Param notamax (float) del nuevo elemento
		@Return true si se ha almacenado correctamente, false en cualquier otro caso
	*/	
	function nuevo($letra,$notaMin,$notaMax){
		$campos=Array(
			"centro" => $this->centro,
			"letra" => $letra,
			"notaMin" => $notaMin,
			"notaMax" => $notaMax
		);
		return $this->db->insert("notasLetras",$campos);
	}


	/*	Función para borrar un elemento
		@Param id (int) del elemento a borrar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	function borrar($letra) {
		$this->db->where("letra",$letra)->where("centro",$this->centro);
		return $this->db->delete("notasletras");
	}
	

	/*	Función PRIVADA para obtener un campo de un elemento
		@Param id (int) del elemento a consultar
		@Param campo (string) del elemento a consultar
		@Return valor (string) del campo
	*/
	private function obtenerCampo($id,$campo){
		$this->db->get("notasletras",null,Array($campo));
		$this->db->where("letra",$id)->where("centro",$this->centro);
		$datos=$this->db->getOne("notasletras");
		return $datos[$campo];
	}
	

	/*	Función PRIVADA para modificar un campo de un elemento
		@Param id (int) del elemento a modificar
		@Param campo (string) del elemento a modificar
		@Param valor (string) del campo a modificar
		@Return true si se ha borrado correctamente, false en cualquier otro caso
	*/
	private function modificarCampo($id,$campo,$valor){
		$this->db->where("letra",$id)->where("centro",$this->centro);
		return $this->db->update("notasletras",Array($campo => $valor));
	}
}
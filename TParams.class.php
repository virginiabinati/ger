<?php

class TParams 
{
	public static $instance;
	private static $strParams;
	private static $params;
	
	private function __construct(){
		$this->strParams = $_GET['cod'];
		$this->parseParams();
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)){
			self::$instance = new TParams();
		}
		return self::$instance;
	}
	
	private function parseParams(){
		$this->params = explode('/', $this->strParams);
	}
	
	public function getClass(){ //Parametro que indica a classe a ser instanciada na aplicação
		return $this->params[0]; 
	}
	
	public function getMethod(){ //Metodo a ser chamado na classe instanciada
		return $this->params[1]; 
	}
	
	public function getParams(){ //retorna o array de parametros original
		$temp_array = $this->params;
		array_shift($temp_array); //remove o parametro da classe
		array_shift($temp_array); //remove o parametro do metodo
		return $temp_array;
	}
	
	public function getParam($index){ //retorna uma posiçao especifica do array de parametros
		return $this->params[$index];
	}
}
?>
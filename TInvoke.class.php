<?php
class TInvoke {
	
	private $instance = null;
	private $content = null;
	public $echoContent = false;
	
	public function __construct()
	{
	}
	
	function invoke($class, $method, $params = null) {

		$this->content = "";
		if($class){
			if(class_exists($class, true))
				$this->instance = new $class;
		}
		if($this->instance){
			if(method_exists($this->instance, $method)){
				ob_start();
				$this->instance->$method();
				call_user_method_array($method, $this->instance, $params);
				$this->content = ob_get_contents();
				ob_end_clean();
			}
		}
		if($this->echoContent)
			echo $this->content;
		return $this->content;
	}
	
	function classInvoke($class) {
		if(class_exists($class, true))
			$instance = new $class;
		return $instance;	
	}
	
	function methodInvoke($method, $instance = null, $params = null){
		if($instance)
			$this->instance = $instance;
		if(method_exists($this->instance, $method)){
			ob_start();
			$this->instance->$method();
			$content = ob_get_contents();
			ob_end_clean();
		}
		if($this->echoContent)
			echo $content;
		return $content;
	}
}
?>
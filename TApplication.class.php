<?php
class TApplication
{
	public function __construct(){
		session_start();
	}
	public function run()	
	{
		//$par = TParams::getInstance();
		//print_r($par->getParams());
		
		//echo TInvoke::invoke($par->getClass(), $par->getMethod(), $par->getParams());
		
		//$par2 = TParams::getInstance();
		//echo $par2->getClassParam();
		//echo $par2->getParam(3);
		
		
		//resgata nome da classe a instanciar e metodo a chamar
		$class = $_GET['class']?$_GET['class']:"Main";
		$method = $_GET['method']?$_GET['method']:"onInit";
		
		$content = $this->invoke($class, $method);
		$menu = $this->invoke("Menu","onInit");
		
		$template = new Template("main");
		$template->setContent("content",$content);
		$template->setContent("menu",$menu);
		$template->show();
	}
	
	private function invoke($class, $method){
		$instance = null;
		$content = null;
		if(class_exists($class, true))
			$instance = new $class;
		if(method_exists($instance, $method)){
			ob_start();
			$instance->$method();
			$content = ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}
}
?>
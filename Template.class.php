<?php
class Template
{
	private $template = "";
	
	public function __construct($path = NULL) {
		if($path){
			$this->load($path);
		}
	}
	
	private function load($templateName) {
		$this->template = file_get_contents("app/template/".$templateName.".template.html");
	}
	
	public function get() {
		return $this->template; 
	}
	
	public function show() {
		echo $this->template;
	}
	
	public function setContent($name, $content){
		$name = "#".strtoupper($name)."#";
		$this->template = str_replace($name, $content, $this->template);
	}
}
?>
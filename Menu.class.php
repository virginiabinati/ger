<?php
class Menu{
	public function __construct() {
	}
	
	public function onInit(){
		View::loadView("menu",$_GET['class']);
	}
}
?>
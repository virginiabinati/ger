<?php
class Main {
	
	public function __construct()
	{
	}
	
	public function onInit()
	{
		if(Login::estaLogado())
			Login::principal();			
		else
			Redirect::toLoginLogarUsuario();			
	}	
}
?>
<?php
class Session
{
	function __construct()
	{
		session_start();		
	}
	function salva($variavel,$valor)
	{
		$_SESSION[$variavel]=$valor;				
	}
	function retorna($variavel)
	{
		return $_SESSION[$variavel];
	}
	function destroi()
	{
		$_SESSION = array();
		session_destroy();
		echo "Sessão encerrada!";
	}
	function existe($variavel) {
		return $_SESSION[$variavel];
	}
	
	function remove($variavel){
		unset($_SESSION[$variavel]);
		unset($variavel);
		try
		{
			if (isset($_SESSION[$variavel]))
			{
				Exception::__construct('Variavel ainda Setada',$variavel);
			}
		}
		catch (Exception  $e)
		{
			print $e;
		}
	}
}
?>
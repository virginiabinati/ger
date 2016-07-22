<?php
class Classes
{
	public function __construct()
	{
		if(!$_SESSION["dados_usuario"])
			Login::logarUsuario();
	}

	function onListarClasses($id = NULL)
	{
		TTransaction::open();
		$repositorio = new TRepository("classe");
		$criterio = new TCriteria();
		$criterio->setProperty('order', 'nome');
		$classes = $repositorio->load($criterio);
		return $classes;
	}
}
?>
<?php

class Permissoes {
	
	function __construct()
	{
	}

	function onAddPermissoes($idgrupo,$classes)
	{
		
		TTransaction::open();
		foreach($classes as $classe)
		{
			$permissoes = new permissaoRecord();
			$permissoes->idgrupo = $idgrupo;
			$permissoes->classe = $classe;
			$permissoes->escrita = 1;
			$permissoes->avancado = 1;
			$permissoes->store();
		}
		TTransaction::close();
	}	
	
	function carregaPermissoes()
	{
		if(Session::existe('dados_usuario')) 
		{
			$dados = Session::retorna('dados_usuario');
			TTransaction::open();
			$repositorio = new TRepository("permissao");
			$criterio = new TCriteria();
			$criterio->add(new TFilter('idgrupo','=',$dados['idgrupo']));
			$permissao = $repositorio->load($criterio);
			$dados['permissao'] = $permissao;			
			Session::salva("dados_usuario",$dados);
		}
		else Redirect::toLoginLogarUsuario();
	}
	
	function checa($par = NULL)
	{
		switch ($par){
			case 'A': //Permissao avançada (Advanced)
				return self::checaPermissaoAvancada($_GET['class'], $_GET['method']);
			break;
			case 'W': //Permissao de escrita (Write)
				return self::checaPermissaoEscrita($_GET['class'], $_GET['method']);
			break;
			default:
				return self::checaPermissao($_GET['class'], $_GET['method'], FALSE);
			break;
		}
	}

	function checaPermissao($class, $method, $redirecionaHome = NULL){
		
		$class = $class?$class:$_GET['class'];
		$method = $method?$method:$_GET['method'];
		
		$dados = Session::retorna('dados_usuario');
		$permissoes = $dados['permissao'];
		$permissao_cedida = FALSE;
		if(!Login::estaLogado()) 
		{
			self::carregaPermissoes();
		}
		//if($class && $method)
		//{
			foreach( $permissoes as $permissao)
			{
				if($permissao['classe']==$class) 
				{
					if(( !$permissao['metodo']) || ($permissao['metodo']==$method))
					{
						$permissao_cedida = TRUE;
					}
					else
					{
						$permissao_cedida = FALSE;
					}
				}
			}
		//}
		if($redirecionaHome && !$permissao_cedida && Login::estaLogado())
		{
			Redirect::toMainOnInit();
		}
		else
			return $permissao_cedida;
	}
	
	function checaPermissaoEscrita($class = NULL, $method = NULL) {
		
		$class = $class?$class:$_GET['class'];
		$method = $method?$method:$_GET['method'];
		
		$dados = Session::retorna('dados_usuario');
		$permissoes = $dados['permissao'];
		if(!Login::estaLogado())
			self::carregaPermissoes();
		if($class && $method)
		{
			foreach($permissoes as $permissao)
			{
				if($permissao['classe']==$class) 
				{
					if((!$permissao['metodo'])||$permissao['metodo']==$method)
					{ 
						if($permissao['escrita']=="1")
							return true;								
					}
				}
			}
		}
		return false;
	}

	function checaPermissaoAvancada($class = NULL, $method = NULL) {
		
		$class = $class?$class:$_GET['class'];
		$method = $method?$method:$_GET['method'];
		
		$dados = Session::retorna('dados_usuario');
		$permissoes = $dados['permissao'];
		if(!Login::estaLogado())
			self::carregaPermissoes();
		if($class && $method)
		{
			foreach($permissoes as $permissao)
			{
				if($permissao['classe']==$class) 
				{
					if((!$permissao['metodo'])||$permissao['metodo']==$method)
					{ 
						if($permissao['avancado']=="1") 
						{
							return true;				
						}		
					}
				}
			}
			return false;
		}
	}
	
	function onListarPermissoesPorGrupo($idgrupo)
	{
		TTransaction::open();
		$repositorio = new TRepository("permissao");
		$criterio = new TCriteria();
		$criterio->add(new TFilter('idgrupo','=',$idgrupo));
		$permissoes = $repositorio->load($criterio);
		return $permissoes;
	}
	
	function onDeletarPermissoes($idgrupo)
	{
		
		TTransaction::open();
		$conn=TTransaction::get();
		$sql= "	delete from permissao where idgrupo = {$idgrupo}";
		$res = $conn->Query($sql);
		TTransaction::close();
	}
}

?>

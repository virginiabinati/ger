<?php
class Login
{
	function logarUsuario()
	{
		if(Session::existe('dados_usuario')) 
		{
			self::principal();
		}
		else
		{	
			View::loadView("loginForm");
		}
	}
	
	function onLogarUsuario()
	{
		TTransaction::open();
		$senha = md5($_POST[senha]);
		$repositorio = new TRepository("usuario");
		$criterio = new TCriteria();
		$criterio->add(new TFilter('login','=',$_POST["login"]));
		$criterio->add(new TFilter('senha','=',$senha));
		$criterio->add(new TFilter('ativo','=',1));
		$usuario = $repositorio->load($criterio);
		TTransaction::close();
		
		if ($usuario)
		{
			Session::salva('dados_usuario',$usuario[0]);			
			Permissoes::carregaPermissoes();
			self::principal();
		}
		else
		{
			self::logarUsuario();					
		}
	}
	function principal(){
		$dados["aniversariantes"] = Usuarios::aniversariantes();
		$dados["pendencias"]["usuario"] = Pendencias::pendenciasUsuarioSession();
		$dados["pendencias"]["recentes"] = Pendencias::pendenciasRecentes();
		$dados["gradeAgora"] = Aulas::gradeAgora();
		View::loadView("principal",$dados);		
	}	
	function estaLogado()
	{
		return Session::existe('dados_usuario');
	}
	function retornaDados($dado = NULL)
	{
		$usuario = Session::retorna('dados_usuario');
		if ($dado)
		return $usuario[$dado];
		return $usuario;
	}
	function onLogoff()
	{		
		Session::destroi();
		header("location:?class=Main&method=onInit");
	}
}
?>
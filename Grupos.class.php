<?php
class Grupos
{
	public function __construct()
	{
		if(!$_SESSION["dados_usuario"])
			Login::logarUsuario();
		Permissoes::checa();
		if($_SESSION["dados_usuario"]["idgrupo"] != 1)
		{
			echo "<center><img src='images/stop.png'><br /><h2>Você não tem permissão para acessar esse link! <a href='javascript:history.go(-1)'>Voltar</a></h2></center>";
			die;
		}
	}
	function addGrupo()
	{
		$classes = Classes::onListarClasses();
		View::loadView("grupoForm",$classes);	
	}
	function alterarGrupo()
	{
		$dados[grupo]= self::onListarGrupos($_GET['id']);
		$dados[classes] = Classes::onListarClasses();
		$dados[permissoes] = Permissoes::onListarPermissoesPorGrupo($_GET['id']);
		View::loadView("grupoAlterarForm", $dados);	
	}
	function onAddGrupo()
	{
		
		TTransaction::open();
		$grupos = new grupoRecord();
		$grupos->nome = $_POST["nome"];
		$grupos->store();
		Permissoes::onAddPermissoes($grupos->id,$_POST['classe']);		
		TTransaction::close();
		echo "Grupo inserido com sucesso!";
	}	
	function onSelectGrupos($id = NULL)
	{
		TTransaction::open();
		$repositorio = new TRepository("grupo");
		$criterio = new TCriteria();
		$criterio->setProperty('order', 'id');		
		$dados['grupos'] = $repositorio->load($criterio);
		$dados['selected'] = $id;
		View::loadView("gruposSelect",$dados);		
	}	
	function onListarGrupos($id=null)
	{
		TTransaction::open();
		$repositorio = new TRepository("grupo");
		$criterio = new TCriteria();
		if ($id)
			$criterio->add(new TFilter('id','=',$id));
		else
		$criterio->setProperty("order","id");
		$grupos = $repositorio->load($criterio);
		if ($id)
			return $grupos[0];
		else
		View::loadView("gruposList",$grupos);	
			TTransaction::close();	
	}
	function onAlterarGrupo()
	{
		Permissoes::onDeletarPermissoes($_POST["id"]);
		Permissoes::onAddPermissoes($_POST["id"],$_POST['classe']);
		echo "Grupo alterado com sucesso!";
	}
	function onDeletarGrupo()
	{

		$total = Usuarios::listarUsuariosPorGrupo($_GET['id']);
		if ($total > 0)
			echo 'Erro ao apagar. Existem usuários no grupo!';
		else
		{
			Permissoes::onDeletarPermissoes($_GET["id"]);
			TTransaction::open();
			$grupo = new grupoRecord();
			$grupo->id = $_GET["id"];
			$grupo->delete();
			
			$grupo = new grupoRecord();
			$grupo->id = $_GET["id"];
			$grupo->delete();
			TTransaction::close();
			echo "Grupo apagado com sucesso!";
		
		}
	}
	
	function ListaGrupos($id = NULL)
	{
		TTransaction::open();
		$repositorio = new TRepository('grupo');
		$criterio = new TCriteria();
		if ($id)
			$criterio->add(new TFilter('id','=',$id));
		else
			$criterio->setProperty('order','nome');
		$grupos = $repositorio->load($criterio);
		TTransaction::close();	
		return $grupos;
	}
}
?>
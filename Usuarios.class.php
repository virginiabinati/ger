<?php
	class Usuarios {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function addUsuario() 
		{
			$grupos=Grupos::ListaGrupos();
			View::loadView("usuarioForm",$grupos);
		}
		
		function onAddUsuario() 
		{
			TTransaction::open();
			$usuario = new usuarioRecord();
			$usuario->login = $_POST['login'];
			$usuario->senha = md5($_POST['senha']);			
			$usuario->idgrupo = $_POST["idgrupo"];
			$usuario->ativo = 1;
			$usuario->nome = $_POST['nome'];
			$usuario->sobrenome = $_POST['sobrenome'];
			$usuario->email = $_POST['email'];
			$usuario->sexo = $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$usuario->data_nascimento = $data;
			$usuario->telefone = $_POST["telefone"];
			$usuario->store();			
			$id_usuario = $usuario->id;
			TTransaction::close();
			
			if($_POST["idgrupo"] == 3){
				TTransaction::open();
				$escala = new escala_facilitadorRecord();
				$escala->id_usuario = $id_usuario;
				$escala->data_escala = date("Y-m-d");
				$escala->store();
				TTransaction::close();
			}
			
			$nomeCompleto = $_POST['nome'] . " " . $_POST['sobrenome'];
			$ad = new AD();
			$ad->onAddUser($_POST['login'], $nomeCompleto, "Monitors", null);
			Redirect::toListarUsuarios();
		}
		
		function onListarUsuarios($id = null, $select = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "select u.*, g.nome as grupo from usuario u, grupo g where g.id=u.idgrupo";
			if($id)
				$sql.=" and u.id=".$id;
			else
				$sql.=" order by u.nome";
			$res= $conn->Query($sql);
			$dados['usuario']=$res->fetchAll();
			if($id)
				return $dados['usuario'][0];
			if($select)
				return $dados['usuario'];	
			else
				View::loadView("usuariosList",$dados);
			TTransaction::close();
		}	
		
		function onBuscarUsuario() 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT t1.*, t2.id as idgrupo, t2.nome as grupo FROM usuario as t1 INNER JOIN grupo as t2 ON t1.idgrupo = t2.id WHERE t1.login like '%{$_POST["busca"]}%' OR t1.nome like '%{$_POST["busca"]}%' OR t1.sobrenome like '%{$_POST["busca"]}%' ORDER BY t1.nome";
			$res= $conn->Query($sql);
			$dados["usuario"]=$res->fetchAll();
			TTransaction::close();
			View::loadView("usuariosList", $dados);
		}
		
		function altUsuario()
		{
			$dados['usuario'] = self::onListarUsuarios($_GET['id']);
			ob_start();
			Grupos::onSelectGrupos($dados['usuario']['idgrupo']);
			$dados['grupos'] = ob_get_contents();
			ob_clean();
			View::loadView("usuarioAlterarForm",$dados);			
		}
			
		function onAlterarUsuario()
		{
			TTransaction::open();			
			$usuario = new usuarioRecord();
			$usuario->id = $_POST['id'];	
			$usuario->login = $_POST['login'];		
			$usuario->senha = md5($_POST['senha']);	
			$usuario->idgrupo = $_POST["idgrupo"];
			$usuario->ativo = $_POST["ativo"];
			$usuario->nome = $_POST['nome'];
			$usuario->sobrenome = $_POST['sobrenome'];
			$usuario->email = $_POST['email'];
			$usuario->sexo = $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$usuario->data_nascimento = $data;
			$usuario->telefone = $_POST["telefone"];
			$usuario->store();
			TTransaction::close();
			
			if($_POST["idgrupo"] == 3){
				TTransaction::open();
				$escala = new escala_facilitadorRecord();
				$escala->id_usuario = $_POST['id'];
				$escala->data_escala = date("Y-m-d");
				$escala->store();
				TTransaction::close();
			}
			
			//ALTERANDO SENHA NO AD
			$ad = new AD();
			if($_POST["ativo"] == 1)
				$ad->onChangeUserPassword($_POST['login'],$_POST['senha']);
			else
				$ad->onInactivateUser($_POST["login"]);
			
			Redirect::toListarUsuarios();
		}
		function onDeletarUsuario()
		{
			TTransaction::open();
			$usuario = new usuarioRecord();
			$usuario->id = $_GET["id"];
			$usuario->delete();
			TTransaction::close();
			
			$ad = new AD();
			$ad->onDelUser($_GET['login']);
			Redirect::toListarUsuarios();
		}
		function listarUsuariosPorGrupo($id_grupo)
		{
			TTransaction::open();
			$cliente = new TRepository('usuario');
			$criterio = new TCriteria();
			$criterio->add(new TFilter('idgrupo','=',$id_grupo));
			$dados = $cliente->load($criterio);
			TTransaction::close();
			
			return ($dados[0]['id']) ? $dados[0]['id'] : 0 ;	
		}
		function aniversariantes()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			
			$sql = "SELECT nome, sobrenome, DATE_FORMAT(data_nascimento,'%d/%m') as aniversario FROM usuario WHERE DATE_FORMAT(data_nascimento, '%m') = '".date("m")."'";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();
			return $dados;
		}
	}
?>
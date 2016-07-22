<?php
	class Computadores {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				echo "<center><img src='images/stop.png'><br /><h2>Você não tem permissão para acessar esse link! <a href='javascript:history.go(-1)'>Voltar</a></h2></center>";
				die;
			}
		}
		
		function addComputador() 
		{
			$salas=Salas::onListarSalas(null,1);
			View::loadView("computadorForm",$salas);
		}
		
		function onAddComputador() 
		{
			TTransaction::open();
			$computador = new computadorRecord();
			$computador->idsala = $_POST['idsala'];
			$computador->nome = $_POST['nome'];
			$computador->store();
			TTransaction::close();
			Redirect::toListarComputadores();
		}
		
		function onListarComputadores($id = null, $select = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			if ($id)
				$plusWhere.=" WHERE Com.id = {$id}";
			$sql = "SELECT 
								Com.id as idcomputador,
								Com.nome,
								Sal.id as idsala,
								Sal.numero, 
								Sal.bloco
					FROM 
								computador as Com
					LEFT JOIN 
								sala as Sal ON Com.idsala = Sal.id								
					{$plusWhere}
					ORDER BY 
								Sal.bloco, Sal.numero, Com.nome ASC";
								
			$res= $conn->Query($sql);
			$computador=$res->fetchAll();
			TTransaction::close();
			if ($id || $select)
				return $computador;
			else
				View::loadView("computadoresList", $computador);
		}

		function onDeletarComputador()
		{
			TTransaction::open();
			$computador = new computadorRecord();
			$computador->id = $_GET["id"];
			$computador->delete();
			TTransaction::close();
			Redirect::toListarComputadores();
		}
	}
?>
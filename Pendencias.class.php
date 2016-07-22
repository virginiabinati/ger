<?php
	class Pendencias {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function addPendencia() 
		{
			$dados["usuarios"]=Usuarios::onListarUsuarios(null,1);
			$dados["salas_computadores"]=Computadores::onListarComputadores(null,1);
			View::loadView("pendenciaForm",$dados);
		}
		
		function onAddPendencia() 
		{
			TTransaction::open();
			$pendencia = new pendenciaRecord();
			$session = Session::retorna("dados_usuario");
			$pendencia->id_usuario = $session["id"];
			$pendencia->data_hora = date("Y-m-d h:i:s");			
			$pendencia->id_computador = $_POST["sala_micro"];
			$pendencia->tipo = $_POST['tipo'];
			$pendencia->problema = $_POST['problema'];
			$pendencia->atendido = 0;
			$pendencia->store();			
			$id_pendencia = $pendencia->getLast();
			
			if($_POST["designar_para"]!="")
			{
				foreach($_POST["designar_para"] as $usu)
				{
					$usuario_pendencia = new usuario_pendenciaRecord();
					$usuario_pendencia->id_pendencia = $id_pendencia;
					$usuario_pendencia->id_usuario = $usu;
					$usuario_pendencia->store();
				}
			}
			TTransaction::close();
			Redirect::toListarPendencias();
		}
		
		function onListarPendencias($id = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			if($id)
				$plusWhere = " WHERE Pend.id = {$id}";
			if($_POST["busca"])
				$plusWhere = " WHERE 
									 Com.nome like '%{$_POST["busca"]}%'
								OR
									 Pend.problema like '%{$_POST["busca"]}%'";
				
			$sql = "SELECT 
								Usu.nome,
								Usu.sobrenome,
								Pend.id,
								Pend.id_computador,
								Pend.tipo,
								Pend.problema,
								Pend.atendido,
								DATE_FORMAT(Pend.data_hora,'%d/%m/%Y %H:%i') as data_hora,
								Com.nome as sala_micro
					FROM 
								pendencia as Pend
					LEFT JOIN 
								usuario as Usu ON Usu.id = Pend.id_usuario
					LEFT JOIN
								computador as Com ON Com.id = Pend.id_computador
					{$plusWhere}
					ORDER BY 
								Pend.tipo ASC";
			$res = $conn->Query($sql);
			$row = $res->fetchAll();
			foreach($row as $pend){
				//PEGANDO OS DADOS DA TABELA PENDENCIA
				$pendencia[$pend["id"]]["pendencia"]["id"] = $pend["id"];
				$pendencia[$pend["id"]]["pendencia"]["nome_cadastro"] = $pend["nome"];
				$pendencia[$pend["id"]]["pendencia"]["sobrenome_cadastro"] = $pend["sobrenome"];
				$pendencia[$pend["id"]]["pendencia"]["data_hora"] = $pend["data_hora"];
				$pendencia[$pend["id"]]["pendencia"]["id_computador"] = $pend["id_computador"];
				$pendencia[$pend["id"]]["pendencia"]["sala_micro"] = $pend["sala_micro"];
				$pendencia[$pend["id"]]["pendencia"]["tipo"] = $pend["tipo"];
				$pendencia[$pend["id"]]["pendencia"]["problema"] = $pend["problema"];
				$pendencia[$pend["id"]]["pendencia"]["atendido"] = $pend["atendido"];
				
				//CONTAGEM EM HORAS ENTRE DATA ATUAL E DATA DA PENDENCIA
				$qtdHoras = CompararDatas::diferenca($pendencia[$pend["id"]]["pendencia"]["data_hora"],"","h");
				$pendencia[$pend["id"]]["pendencia"]["qtdHoras"] = $qtdHoras;
				 
				//PEGANDO OS USUARIO DESIGNADOS PARA CADA PENDENCIA
				$sql = "SELECT 
								Usu.id,
								Usu.nome,
								Usu.sobrenome
						FROM 
								usuario_pendencia as UsuPend
						INNER JOIN
								usuario as Usu ON Usu.id = UsuPend.id_usuario
						WHERE
								UsuPend.id_pendencia = {$pend["id"]}";
				$res = $conn->Query($sql);
				$pendencia[$pend["id"]]["pendencia"]["usuarios_designados"] = $res->fetchAll();
				
				//PEGANDO OS RECADOS DE CADA PENDENCIA
				$sql = "SELECT 
								Usu.nome,
								Usu.sobrenome,
								RecPend.recado
						FROM 
								recado_pendencia as RecPend
						INNER JOIN
								usuario as Usu ON Usu.id = RecPend.id_usuario
						WHERE
								RecPend.id_pendencia = {$pend["id"]}";
				$res = $conn->Query($sql);
				$pendencia[$pend["id"]]["pendencia"]["recados"] = $res->fetchAll();
			}
			
			TTransaction::close();
			if ($id) {
				$pendencia[$id]["pendencia"]["usuarios"] = Usuarios::onListarUsuarios(null,1);
				$pendencia[$id]["pendencia"]["salas_computadores"] = Computadores::onListarComputadores(null,1);
				return $pendencia;
			} else if ($usuario){
				return $pendencia;
			}else
				View::loadView("pendenciasList", $pendencia);
		}	
		
		function altPendencia()
		{
			$dados = self::onListarPendencias($_GET['id']);
			View::loadView("pendenciaAlterarForm",$dados);			
		}

		function onAtendido()
		{
			TTransaction::open();			
			$Pendencia = new PendenciaRecord();
			$Pendencia->id = $_GET['id'];			
			$Pendencia->atendido = $_GET["atendido"];
			$pendencia->data_atendido = date('Y-m-d');
			$Pendencia->store();
			
			TTransaction::close();
			Redirect::toListarPendencias();
		}
		
		function onAlterarPendencia()
		{
			TTransaction::open();
			$pendencia = new pendenciaRecord();
			$session = Session::retorna("dados_usuario");
			$pendencia->id = $_POST["id"];
			$pendencia->id_usuario = $session["id"];
			$pendencia->data_hora = date("Y-m-d h:i:s");			
			$pendencia->id_computador = $_POST["sala_micro"];
			$pendencia->tipo = $_POST['tipo'];
			$pendencia->problema = $_POST['problema'];			
			$pendencia->store();			

			$conn=TTransaction::get();
			$sql = "DELETE FROM usuario_pendencia WHERE id_pendencia = {$_POST["id"]}";
			$res= $conn->Query($sql);
				
			if($_POST["designar_para"]!="")
			{
				foreach($_POST["designar_para"] as $usu)
				{
					$usuario_pendencia = new usuario_pendenciaRecord();
					$usuario_pendencia->id_pendencia = $_POST["id"];
					$usuario_pendencia->id_usuario = $usu;
					$usuario_pendencia->store();
				}
			}
			TTransaction::close();
			Redirect::toListarPendencias();
		}
		
		function addRecado()
		{
			 View::loadView("pendenciaRecadoForm",$_GET["id_pendencia"]);
		}
		
		function onAddRecado()
		{
			TTransaction::open();			
			$pendencia = new recado_pendenciaRecord();
			$pendencia->id_pendencia = $_POST["id_pendencia"];
			$session = Session::retorna("dados_usuario");
			$pendencia->id_usuario = $session["id"];
			$pendencia->recado = $_POST["recado"];
			$pendencia->store();
			
			TTransaction::close();
			Redirect::toListarPendencias();
		}
		
		function onDeletarPendencia()
		{
			TTransaction::open();
			
			$Pendencia = new PendenciaRecord();
			$Pendencia->id = $_GET["id"];
			$Pendencia->delete();

			$conn=TTransaction::get();
			$sql = "DELETE FROM usuario_pendencia WHERE id_pendencia = {$_GET["id"]}";
			$res= $conn->Query($sql);
			
			$sql = "DELETE FROM recado_pendencia WHERE id_pendencia = {$_GET["id"]}";
			$res= $conn->Query($sql);
			
			Redirect::toListarPendencias();
		}
		function pendenciasUsuarioSession()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
								pend.tipo,
								pend.problema,
								usu.nome,
								usu.sobrenome,
								comp.nome as computador
					FROM 
								usuario_pendencia usuPend
					INNER JOIN 
								pendencia AS pend ON pend.id = usuPend.id_pendencia
					INNER JOIN
								usuario AS usu ON usu.id = usuPend.id_usuario
					LEFT JOIN
								computador AS comp ON comp.id = pend.id_computador 
					WHERE 
								usuPend.id_usuario = '{$_SESSION["dados_usuario"]["id"]}'
					AND 
								pend.atendido = 0
					GROUP BY
								pend.id
					ORDER BY
								pend.id DESC";
			$res = $conn->Query($sql);
			$dados = $res->fetchAll();
			TTransaction::close();
			return $dados;	
		}
		function pendenciasRecentes()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
								pend.tipo,
								pend.problema,
								usu.nome,
								usu.sobrenome,
								comp.nome as computador
					FROM 
								pendencia AS pend
					LEFT JOIN 
								usuario_pendencia usuPend ON pend.id = usuPend.id_pendencia
					LEFT JOIN
								usuario AS usu ON usu.id = usuPend.id_usuario
					LEFT JOIN
								computador AS comp ON comp.id = pend.id_computador 
					WHERE 
								pend.atendido = 0
					GROUP BY
								pend.id
					ORDER BY
								pend.tipo, pend.id DESC";
			$res = $conn->Query($sql);
			$dados = $res->fetchAll();
			TTransaction::close();
			return $dados;	
		}
	}
?>
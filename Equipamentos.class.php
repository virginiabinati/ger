<?php
	class Equipamentos {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function addEquipamento() 
		{
			$dados["equipamento"] = self::onListarEquipamento($_GET["idsala"]);
			$dados["idsala"] = $_GET["idsala"];
			View::loadView("equipamentoForm",$dados);
		}
		
		function onAddEquipamento() 
		{
			$exists = self::onListarEquipamento($_POST["idsala"]);			
			$id = $exists[0]["id"];
						
			TTransaction::open();
			$equipamento= new equipamento_redeRecord();
			if($id != "") 
				$equipamento->id = $id;
			$equipamento->idsala = $_POST["idsala"];
			$equipamento->tipo = $_POST["tipo"];			
			$equipamento->marca = $_POST["marca"];
			$equipamento->codigo = $_POST['codigo'];
			$equipamento->modelo = $_POST['modelo'];
			$equipamento->velocidade = $_POST["velocidade"];
			$equipamento->portas = $_POST["portas"];
			$equipamento->store();			
			TTransaction::close();
			Redirect::toListarSalas();
		}
		
		function onListarEquipamento($idsala = null){
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
							*
					FROM 
							equipamento_rede
					WHERE 
							idsala = '{$idsala}'";
			$res = $conn->Query($sql);
			$row = $res->fetchAll();
			TTransaction::close();			
			return $row;
		}
	}
?>
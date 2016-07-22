<?php
	class Sessions {
		
		function __construct() 
		{

		}
		
		function addSession() 
		{
			View::loadView("sessionForm");
		}
		
		function onAddSession() 
		{
			TTransaction::open();
			$session = new sessionRecord();
			$session->IDUSUARIO = $_POST['idusuario'];
			$session->LOCALIZACAO = $_POST['localizacao'];
			$session->DATA = $_POST['data'];
			$session->store();
			TTransaction::close();
			Redirect::toListarSessions();
		}
		
		function onListarSessions($id = null) 
		{
			TTransaction::open();
			$repositorio = new TRepository("session");
			$criterio = new TCriteria();
			if($id)
				$criterio->add("ID","=",$id);
			$criterio->setProperty('order', 'ID');
			$session = $repositorio->load($criterio);
			if($id)
				return $session;
			else
				View::loadView("sessionsList", $session);
		}

		function altSession()
		{
			$dados = self::onListarSession($_GET['id']);
			View::loadView("sessionAlterarForm",$dados);			
		}
		
		function onAlterarSession()
		{
			TTransaction::open();			
			$session = new sessionRecord();
			$session->ID = $_POST['id'];
			$session->IDUSUARIO = $_POST['idusuario'];
			$session->LOCALIZACAO = $_POST['localizacao'];
			$session->DATA = $_POST['data'];			
			$session->store();
			TTransaction::close();
			Redirect::toListarSessions();
		}
		function onDeletarSession()
		{
			TTransaction::open();
			$session = new sessionRecord();
			$session->ID = $_GET["id"];
			$session->delete();
			TTransaction::close();
			Redirect::toListarSessions();
		}
	}
?>
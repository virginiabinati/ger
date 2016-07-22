<?php
	class Grade {
		
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
		
		function addGrade() 
		{
			View::loadView("gradeForm");
		}
		
		function onAddGrade() 
		{
			TTransaction::open();
			$grade = new gradeRecord();
			$grade->semestre = $_POST['semestre'];
			$grade->ano = $_POST['ano'];
			$grade->store();
			TTransaction::close();
			Redirect::toListarGrade();
		}
		
		function onListarGrade($id = null, $select = null) 
		{
			TTransaction::open();
			$repositorio = new TRepository("grade");
			$criterio = new TCriteria();
			if($id)
				$criterio->add("id","=",$id);
			$criterio->setProperty('order', 'id DESC');
			$grade = $repositorio->load($criterio);
			if($id || $select)
				return $grade;
			else
				View::loadView("gradeList", $grade);
		}

		function onDeletarGrade()
		{
			TTransaction::open();
			$grade = new gradeRecord();
			$grade->id = $_GET["id"];
			$grade->delete();
			TTransaction::close();
			Redirect::toListarGrade();
		}
	}
?>
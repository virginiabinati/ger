<?php
	class Redirect 
	{
		function __construct()
		{
		}
		function toMainOnInit()
		{
			header("location: ?class=Main&method=onInit");
		}
		function toListarUsuarios()
		{
			header("location: ?class=Usuarios&method=onListarUsuarios");
		}
		function toListarAlunos()
		{
			header("location: ?class=Alunos&method=onListarAlunos");
		}
		function toListarAulas()
		{
			header("location: ?class=Aulas&method=onBuscarAula");
		}
		function toListarCursos()
		{
			header("location: ?class=Cursos&method=onListarCursos");
		}
		function toListarDisciplinas()
		{
			header("location: ?class=Disciplinas&method=onListarDisciplinas");
		}
		function toListarGrupos()
		{
			header("location: ?class=Grupos&method=onListarGrupos");
		}
		function toListarGrade()
		{
			header("location: ?class=Grade&method=onListarGrade");
		}
		function toListarPendencias()
		{
			header("location: ?class=Pendencias&method=onListarPendencias");
		}
		function toLoginLogarUsuario()
		{
			header("location: ?class=Login&method=logarUsuario");
		}
		function toListarSalas()
		{
			header("location: ?class=Salas&method=onListarSalas");
		}
		function toListarComputadores()
		{
			header("location: ?class=Computadores&method=onListarComputadores");
		}
		function toListarProfessores()
		{
			header("location: ?class=Professores&method=onListarProfessores");
		}
		function to($location) {
			Session::remove('redirecionar');
			header('location: '.$location);
		}
	}
?>
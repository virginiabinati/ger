<?php
	class Aulas {
		
		function __construct() 
		{

		}
		
		function addAula() {
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			$dados["grade"]=Grade::onListarGrade(null,1);
			$dados["curso"]=Cursos::onListarCursos(null,1);
			$dados["disciplina"]=Disciplinas::onListarDisciplinas(null,1);
			$dados["professor"]=Professores::onListarProfessores(null,1);
			$dados["sala"]=Salas::onListarSalas(null,1);
			View::loadView("aulaForm",$dados);
		}
		
		function onAddAula() 
		{
			TTransaction::open();
			$aula = new aulaRecord();
			$aula->idgrade = $_POST['idgrade'];
			$aula->idcurso = $_POST['idcurso'];
			$aula->iddisciplina = $_POST['iddisciplina'];
			$aula->idprofessor = $_POST['idprofessor'];
			$aula->idsala = $_POST['idsala'];
			$aula->dia = Util::formataData($_POST['data_aula']);
			$aula->dia_semana = ($_POST['exporadica']== 1) ? "" : $_POST["dia_semana"];
			$aula->inicio = $_POST['horario_inicio'].":00";
			$aula->fim = $_POST['horario_fim'].":00";
			$aula->exporadica = $_POST['exporadica'];
			$aula->projetor = $_POST['projetor'];
			$aula->detalhes = $_POST['detalhes'];
			$aula->data_insercao = date("Y-m-d");
			$aula->store();
			TTransaction::close();
			Redirect::toListarAulas();
		}
		
		function onAulaExists(){
			TTransaction::open();
			$conn=TTransaction::get();	
			
			if($_POST["exporadica"] == 1)
				$d_semana = date("D",strtotime(Util::formataData($_POST['data_aula'])));
			else {
				$d_semana = $_POST["dia_semana"];
				$sql = "SELECT DATE_FORMAT(dia,'%a') as dia_semana, TIME_FORMAT(inicio,'%H:%i') as inicio, TIME_FORMAT(fim,'%H:%i') as fim FROM aula WHERE idsala = '{$_POST['idsala']}' AND exporadica = 1 AND dia >= CURDATE() ORDER BY dia_semana, inicio";
				$res= $conn->Query($sql);
				$dados=$res->fetchAll();
				foreach($dados as $aula){
					if($d_semana == $aula["dia_semana"] && $_POST['horario_inicio'] >= $aula["inicio"] && $_POST['horario_fim'] <= $aula["fim"]) {
						$exist = 1;
						echo $exist;
						die;
					}
				}
			}				

			$sql = "SELECT 
							id 
					FROM 
							aula 
					WHERE 
							idsala = '{$_POST['idsala']}'
					AND (
							(
								dia_semana = '{$d_semana}' 
								AND (
										(inicio between '{$_POST['horario_inicio']}:00' AND '{$_POST['horario_fim']}:00')
										OR
										(fim between '{$_POST['horario_inicio']}:00' AND '{$_POST['horario_fim']}:00')
									)
							)
					OR
							(
								dia = '" . Util::formataData($_POST['data_aula']) . "' 
								AND (
										(inicio between '{$_POST['horario_inicio']}:00' AND '{$_POST['horario_fim']}:00')
										OR
										(fim between '{$_POST['horario_inicio']}:00' AND '{$_POST['horario_fim']}:00')
									)
							)
					)";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();	
			$exist = count($dados);
			echo $exist;		
		}
		
		function onListarAulas($id = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			
			$sql = "SELECT id FROM grade ORDER BY id DESC LIMIT 1";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			$id_grade = $dados[0]["id"];
			
			if ($id)
				$plusWhere.=" WHERE a.id = {$id}";
			else
				$plusWhere.=" WHERE a.idgrade = '{$id_grade}' AND (a.dia_semana = '" . date("D") . "' OR dia = '" . DATE('Y-m-d'). "')";	
			$sql = "SELECT 
								a.id as id_aula,
								a.dia_semana,
								date_format(a.dia,'%d/%m/%Y') as dia, 
								TIME_FORMAT(a.inicio, '%H:%i') as inicio, 
								TIME_FORMAT(a.fim, '%H:%i') as fim,
								a.exporadica, 
								a.projetor, 
								a.detalhes, 
								a.idcurso,
								a.iddisciplina,								
								a.idsala,
								c.curso, 
								d.disciplina, 
								f.id as idprofessor,
								f.nome, 
								f.sobrenome, 
								s.numero, 
								s.bloco, 
								g.semestre, 
								g.ano 
					FROM 
								aula AS a
					INNER JOIN 
								curso AS c ON c.id=a.idcurso
					INNER JOIN 
								disciplina AS d ON d.id=a.iddisciplina
					INNER JOIN 
								professor AS f ON f.id=a.idprofessor
					INNER JOIN 
								sala AS s ON s.id=a.idsala
					INNER JOIN 
								grade AS g ON g.id=a.idgrade
								{$plusWhere}
					ORDER BY 
								inicio, s.numero, s.bloco";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			if($id)
				return $dados;
			else
				View::loadView("aulasList",$dados);
		}

		function gradeAgora()
		{			
			TTransaction::open();
			$conn=TTransaction::get();
			
			$sql = "SELECT id FROM grade ORDER BY id DESC LIMIT 1";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			$id_grade = $dados[0]["id"];
			
			$data = strtotime(date("Y-m-d"));
			
			$date = new DateTime();
			$date->modify("-30 minutes");	
			$inicial = $date->format("H:i");
			
			$date = new DateTime();
			$date->modify("+30 minutes");	
			$final = $date->format("H:i");
			
			$sql = "SELECT 
							a.id AS id_aula, 
							DATE_FORMAT( a.inicio, '%d/%m/%Y' ) AS data_aula, 
							TIME_FORMAT( a.inicio, '%H:%m' ) AS inicio, 
							TIME_FORMAT( a.fim, '%H:%m' ) AS fim, 
							a.exporadica, 
							a.projetor, 
							a.detalhes, 
							a.idcurso, 
							a.iddisciplina, 
							a.idsala, 
							c.curso, 
							d.disciplina, 
							f.id AS idprofessor, 
							f.nome, 
							f.sobrenome, 
							s.numero, 
							s.bloco, 
							g.semestre, 
							g.ano
					FROM 
							aula AS a
					INNER JOIN 
							curso AS c ON c.id = a.idcurso
					INNER JOIN 
							disciplina AS d ON d.id = a.iddisciplina
					INNER JOIN 
							professor AS f ON f.id = a.idprofessor
					INNER JOIN 
							sala AS s ON s.id = a.idsala
					INNER JOIN 
							grade AS g ON g.id = a.idgrade
					WHERE 
							a.idgrade = '{$id_grade}'
					AND (
							(
								a.exporadica = 0
								AND a.dia_semana = '". DATE("D",$data) ."'
							)
						OR (
								a.exporadica =1
								AND dia = '". DATE("Y-m-d",$data) ."'
						    )
						)
					AND 
						date_format( a.inicio, '%H:%m' ) BETWEEN '{$inicial}' AND '{$final}'
					ORDER BY 
						inicio, s.numero, s.bloco";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			return $dados;
		}
	
		function onBuscarAula() 
		{
			if(!isset($_GET["mes"]))
				$mes = date("m");
			else
				$mes = $_GET["mes"]+1;
			if(!isset($_GET["dia"]))
				$data = date("d/m/Y");
			else
				$data = $_GET["dia"]."/".$mes."/".$_GET["ano"];			
			
			$data = strtotime(Util::formataData($data));
			
			TTransaction::open();
			$conn=TTransaction::get();
			
			$sql = "SELECT id FROM grade ORDER BY id DESC LIMIT 1";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			$id_grade = $dados[0]["id"];
			
			$sql = "SELECT 
								a.id as id_aula,
								date_format(a.dia,'%d/%m/%Y') as data_aula, 
								TIME_FORMAT(a.inicio, '%H:%i') as inicio, 
								TIME_FORMAT(a.fim, '%H:%i') as fim,
								date_format(a.data_insercao,'%m%Y') as apartir,
								a.exporadica, 
								a.projetor, 
								a.detalhes, 
								a.idcurso,
								a.iddisciplina,								
								a.idsala,
								c.curso, 
								d.disciplina, 
								f.id as idprofessor,
								f.nome, 
								f.sobrenome, 
								s.numero, 
								s.bloco, 
								g.semestre, 
								g.ano 
					FROM 
								aula AS a
					INNER JOIN 
								curso AS c ON c.id=a.idcurso
					INNER JOIN 
								disciplina AS d ON d.id=a.iddisciplina
					INNER JOIN 
								professor AS f ON f.id=a.idprofessor
					INNER JOIN 
								sala AS s ON s.id=a.idsala
					INNER JOIN 
								grade AS g ON g.id=a.idgrade
		 			WHERE 
		 						a.idgrade ='{$id_grade}'
 					AND (
		 						(a.exporadica = 0 AND a.dia_semana = '" . date("D",$data) . "')
 							OR 
		 						(a.exporadica = 1 AND dia = '" . DATE('Y-m-d',$data). "')
		 				) 
		 			AND 
							date_format(a.inicio,'%H:%m') between '07:00' AND '23:00' 
					ORDER BY 
							s.numero,s.bloco, inicio";
			$res= $conn->Query($sql);
			$dados["conteudo"]=$res->fetchAll();
			
			$sql = "SELECT DISTINCT 
								concat(s.numero,s.bloco) as SALA
					FROM 
								aula AS a
					INNER JOIN 
								curso AS c ON c.id=a.idcurso
					INNER JOIN 
								disciplina AS d ON d.id=a.iddisciplina
					INNER JOIN 
								professor AS f ON f.id=a.idprofessor
					INNER JOIN 
								sala AS s ON s.id=a.idsala
					INNER JOIN 
								grade AS g ON g.id=a.idgrade
					WHERE 
								a.idgrade =  (SELECT id FROM grade ORDER BY id DESC LIMIT 1)
					AND (
								(a.exporadica = 0 AND a.dia_semana = '" . date("D",$data) . "')
							OR 
								(a.dia ='" . DATE('Y-m-d',$data). "' AND a.exporadica = '1')
						) 
					AND 
							date_format(a.inicio,'%H:%m') between '07:00' AND '23:00' 
					ORDER BY 
							s.numero,s.bloco";
			$res= $conn->Query($sql);
			$dados["salas"]=$res->fetchAll();
			
			$dados["exporadicas"]=self::onListarExporadicas($mes);
			$dados["data"] = DATE('mY',$data);
			$dados["horarioAbertura"] = 7;
			$dados["horarioEncerramento"] = 23;
			View::loadView("aulasList",$dados);
		}
		
		function onListarExporadicas($mes = null){
			TTransaction::open();
			$conn=TTransaction::get();
			
			$sql = "SELECT id FROM grade ORDER BY id DESC LIMIT 1";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			$id_grade = $dados[0]["id"];
			
			$sql = "SELECT 
								a.id as id_aula,
								date_format(a.dia,'%d') as dia, 
								TIME_FORMAT(a.inicio, '%H:%i') as inicio, 
								TIME_FORMAT(a.fim, '%H:%i') as fim,
								date_format(a.data_insercao,'%m%Y') as apartir,
								a.exporadica, 
								a.projetor, 
								a.detalhes, 
								a.idcurso,
								a.iddisciplina,								
								a.idsala,
								c.curso, 
								d.disciplina, 
								f.id as idprofessor,
								f.nome, 
								f.sobrenome, 
								s.numero, 
								s.bloco, 
								g.semestre, 
								g.ano 
					FROM 
								aula AS a
					INNER JOIN 
								curso AS c ON c.id=a.idcurso
					INNER JOIN 
								disciplina AS d ON d.id=a.iddisciplina
					INNER JOIN 
								professor AS f ON f.id=a.idprofessor
					INNER JOIN 
								sala AS s ON s.id=a.idsala
					INNER JOIN 
								grade AS g ON g.id=a.idgrade
		 			WHERE 
		 						a.idgrade ='{$id_grade}'
 					AND 
		 						a.exporadica = 1 
 					AND 
 								DATE_FORMAT(a.dia,'%m') like '%{$mes}%'
	 				ORDER BY
	 							dia";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();	
			TTransaction::close();	
			return $dados;
		}
		
		function altAula()
		{
			$dados["grade"]=Grade::onListarGrade(null,1);
			$dados["curso"]=Cursos::onListarCursos(null,1);
			$dados["disciplina"]=Disciplinas::onListarDisciplinas(null,1);
			$dados["professor"]=Professores::onListarProfessores(null,1);
			$dados["sala"]=Salas::onListarSalas(null,1);
			$dados["aula"] = self::onListarAulas($_GET['id']);
			View::loadView("aulaAlterarForm",$dados);			
		}	
		
		function onAlterarAula()
		{
			
			TTransaction::open();			
			$aula = new aulaRecord();
			$aula->id = $_POST['id'];
			$aula->idgrade = $_POST['idgrade'];
			$aula->idcurso = $_POST['idcurso'];
			$aula->iddisciplina = $_POST['iddisciplina'];
			$aula->idprofessor = $_POST['idprofessor'];
			$aula->idsala = $_POST['idsala'];
			$aula->dia = Util::formataData($_POST['data_aula']);
			$aula->dia_semana = ($_POST['exporadica']== 1) ? "" : $_POST["dia_semana"];
			$aula->inicio = $_POST['horario_inicio'].":00";
			$aula->fim = $_POST['horario_fim'].":00";
			$aula->exporadica = $_POST['exporadica'];
			$aula->projetor = $_POST['projetor'];
			$aula->detalhes = $_POST['detalhes'];
			$aula->data_insercao = date("Y-m-d");
			$aula->store();
			TTransaction::close();
			Redirect::toListarAulas();
		}
		
		function onDeletarAula()
		{
			TTransaction::open();
			$aula = new aulaRecord();
			$aula->id = $_GET["id"];
			$aula->delete();
			TTransaction::close();
			Redirect::toListarAulas();
		}
	}
?>
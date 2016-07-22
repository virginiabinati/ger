<?php
	class Cursos {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function addCurso() 
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			View::loadView("cursoForm",$_GET["popup"]);
		}
		
		function onAddCurso() 
		{
			TTransaction::open();
			$curso = new cursoRecord();
			$curso->codigo = $_POST['codigo'];
			$curso->curso = $_POST['curso'];
			$curso->exporadico = (int)$_POST["exporadico"];
			$curso->store();
			TTransaction::close();
			$ad = new AD();
			$ad->onAddCurso($_POST['codigo'],$_POST['curso']);
			if($_POST["popup"] == 1){
				echo "<script>
							opener.location.reload();
							window.close();
				     </script>";
			} else {
				Redirect::toListarCursos();
			}
		}
		
		function onXmlCursos(){
			try
			{
				ob_start();
				if ($stream = fopen('http://www1.unaerp.br/app/integracao/liape/cursos.jsp?key=UCLNIIATA', 'r')) {
					echo stream_get_contents($stream);
					fclose($stream);
				}
				$conteudo = ob_get_contents();
				ob_clean();
				$conteudo = trim($conteudo);
				$xml = simplexml_load_string($conteudo);
				$chave = $xml->CURSOS->CURSO;
				for($i=0; $i < count($chave); $i++ ){
					$codigo = (string) $xml->CURSOS->CURSO[$i]->CODIGO;
					$nome = (string) utf8_decode($xml->CURSOS->CURSO[$i]->NOMECOMPLETO);
					TTransaction::open();
					$repositorio = new TRepository("curso");
					$criterio = new TCriteria();
					$criterio->add(new TFilter('codigo','=',$codigo));
					$exists_curso = $repositorio->load($criterio);
					if(!$exists_curso){			
						$curso = new cursoRecord();
						$curso->codigo = $codigo;
						$curso->curso = $nome;
						$curso->store();	
					}
					TTransaction::close();				
				}
				Redirect::toListarCursos();
			}
			catch (Exception $e) 
			{
  				echo "O link está Off-line.";
			}
		}
		
		function onListarCursos($id = null, $select = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "select * from curso";
			if($id)
				$sql.=" where id=".$id;
			else
				$sql.=" order by curso";
			$res= $conn->Query($sql);
			$dados["cursos"]=$res->fetchAll();
			TTransaction::close();
			
			if ($id || $select)
			{
				return $dados['cursos'];
			}
			else
				View::loadView("cursosList", $dados["cursos"]);
		}
			
		function onListarCursoById($id) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "select * from curso where id=$id";
			$res= $conn->Query($sql);
			$dados['cursos']=$res->fetchAll();
			TTransaction::close();
			return $dados['cursos'];
		}
		
		function onListarCursoByCodigo($codigo) 
		{
			$conn=TTransaction::get();
			$sql = "select * from curso where codigo = '{$codigo}'";
			$res= $conn->Query($sql);
			$dados['cursos']=$res->fetchAll();
			return $dados['cursos'];
		}
		
		function onBuscarCurso()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT * FROM curso WHERE codigo like '%{$_POST["busca"]}%' OR curso like '%{$_POST["busca"]}%' ORDER BY curso";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();
			View::loadView("cursosList", $dados);
		}
		
		function altCurso()
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			$dados = self::onListarCursos($_GET['id']);
			View::loadView("cursoAlterarForm",$dados);			
		}
		
		function onAlterarCurso()
		{
			TTransaction::open();			
			$curso = new cursoRecord();
			$curso->id = $_POST['id'];
			$curso->codigo = $_POST['codigo'];
			$curso->curso = $_POST['curso'];
			$curso->exporadico = (int)$_POST["exporadico"];
			$curso->store();
			TTransaction::close();
			Redirect::toListarCursos();
		}
		function onDeletarCurso()
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			TTransaction::open();
			$curso = new cursoRecord();
			$curso->id = $_GET["id"];
			$curso->delete();
			TTransaction::close();
			Redirect::toListarCursos();
		}
		
		
	}
?>
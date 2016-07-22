<?php
	class Disciplinas {
		
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
		
		function addDisciplina() 
		{
			View::loadView("disciplinaForm",$_GET["popup"]);
		}
		
		function onAddDisciplina() 
		{
			TTransaction::open();
			$disciplina = new disciplinaRecord();
			$disciplina->codigo = $_POST['codigo'];
			$disciplina->disciplina = $_POST['disciplina'];
			$disciplina->exporadica = (int)$_POST["exporadica"];			
			$disciplina->store();
			TTransaction::close();
			if($_POST["popup"] == 1){
				echo "<script>
							opener.location.reload();
							window.close();
				     </script>";
				
			} else {
				Redirect::toListarDisciplinas();
			}
		}
		
		function onXmlDisciplinas(){
			try
			{
				ob_start();
				if ($stream = fopen('http://www1.unaerp.br/app/integracao/liape/disciplinas.jsp?key=UCLNIIATA', 'r')) {
					echo stream_get_contents($stream);
					fclose($stream);
				}
				$conteudo = ob_get_contents();
				ob_clean();
				$conteudo = trim($conteudo);
				$xml = simplexml_load_string($conteudo);
				$chave = $xml->DISCIPLINAS->DISCIPLINA;
				
				for($i=0; $i < count($chave); $i++ ){
					$codigo = (string) $xml->DISCIPLINAS->DISCIPLINA[$i]->CODIGO;
					$nome = (string) utf8_decode($xml->DISCIPLINAS->DISCIPLINA[$i]->NOME);
					
					TTransaction::open();
					$repositorio = new TRepository("disciplina");
					$criterio = new TCriteria();
					$criterio->add(new TFilter('codigo','=',$codigo));
					$exists_disciplina = $repositorio->load($criterio);
					if(!$exists_disciplina){
						$disciplina = new disciplinaRecord();
						$disciplina->codigo = $codigo;
						$disciplina->disciplina = strtolower($nome);			
						$disciplina->store();
					}			
					TTransaction::close();				
				}
				Redirect::toListarDisciplinas();
			}
			catch (Exception $e) 
			{
  				echo "O link está Off-line.";
			}
		}
		
		function onListarDisciplinas($id = null, $select = null) 
		{
			
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT
								* 
				    FROM
				    			disciplina";
			if($id)
				$sql.=" WHERE id=".$id;
			else
				$sql.=" ORDER BY disciplina";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			
			if($id)
				return $dados[0];
			else if($select)
				return $dados;	
			else
				View::loadView("disciplinasList",$dados);
			TTransaction::close();
		}	
		
		function onBuscarDisciplina() 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT
								* 
				    FROM
				    			disciplina 
				    WHERE 
				    	  		codigo like '%{$_POST["busca"]}%' 
				    OR 
				    	  		disciplina like '%{$_POST["busca"]}%'";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();
			View::loadView("disciplinasList", $dados);
		}
		
		function altDisciplina()
		{
			$dados['disciplina'] = self::onListarDisciplinas($_GET['id']);
			$dados['cursos'] = Cursos::onListarCursos(null,1);
			View::loadView("disciplinaAlterarForm",$dados);			
		}
			
		function onAlterarDisciplina()
		{
			TTransaction::open();			
			$disciplina = new disciplinaRecord();
			$disciplina->id = $_POST["id"];
			$disciplina->codigo = $_POST['codigo'];
			$disciplina->disciplina = $_POST['disciplina'];
			$disciplina->exporadica = (int)$_POST["exporadica"];			
			$disciplina->store();
			
			TTransaction::close();
			Redirect::toListarDisciplinas();
		}
		function onDeletarDisciplina()
		{
			TTransaction::open();
			$disciplina = new disciplinaRecord();
			$disciplina->id = $_GET["id"];
			$disciplina->delete();
			TTransaction::close();
			Redirect::toListarDisciplinas();
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
	}
?>
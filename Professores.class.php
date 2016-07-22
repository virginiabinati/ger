<?php
	class Professores {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
			Permissoes::checa();
		}
		
		function addProfessor() {			
			View::loadView("professorForm",$_GET["popup"]);
		}
		
		function onAddProfessor() 
		{
			TTransaction::open();
			$professor = new professorRecord();
			$professor->login = $_POST['login'];
			$professor->senha = md5($_POST['senha']);
			$professor->nome = $_POST['nome'];
			$professor->sobrenome = $_POST['sobrenome'];
			$professor->email = $_POST['email'];
			$professor->sexo = $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$professor->data_nascimento = $data;
			$professor->area_atuacao = $_POST["area_atuacao"];
			$professor->telefone = $_POST["telefone"];
			$professor->ativo = 1;
			$professor->convidado = (int)$_POST["convidado"];
			$professor->store();			
			TTransaction::close();
			
			$nomeCompleto = $_POST['nome'] . " " . $_POST['sobrenome'];
			
			//ALTERANDO SENHA NO AD
			$ad = new AD();
			$ad->onAddUser($_POST['login'], $nomeCompleto, "Teachers", null);
			$ad->onActivateUser($_POST['login']);
			$ad->onChangeUserPassword($_POST['login'],$_POST['senha']);
			
			if($_POST["popup"] == 1){
				echo "<script>
							opener.location.reload();
							window.close();
				     </script>";
				
			} else {
				Redirect::toListarProfessores();
			}
		}
		
		function onXmlProfessores(){
			try
			{
				TTransaction::open();
				ob_start();
				if ($stream = fopen('http://www1.unaerp.br/app/integracao/liape/docentes.jsp?key=UCLNIIATA', 'r')) {
					echo stream_get_contents($stream);
					fclose($stream);
				}
				$conteudo = ob_get_contents();
				ob_clean();
				$conteudo = trim($conteudo);
				$xml = simplexml_load_string($conteudo);
				$chave = $xml->DOCENTES->DOCENTE;
				
				for($i=0; $i < count($chave); $i++ ){
					$sobrenome = "";
					$xml->DOCENTES->DOCENTE[$i]->NOME;
					$nome_completo = (string) utf8_decode($xml->DOCENTES->DOCENTE[$i]->NOME);
					$aux_nome = explode(" ",$nome_completo);
					$nome = $aux_nome[0];
					$ultimo_sobrenome = end($aux_nome);
					for($x = 1; $x < count($aux_nome); $x++)
					{
						$sobrenome .= $aux_nome[$x] . " ";
					}
					
					$exists = new TRepository('professor');
					$criterio = new TCriteria();
					$criterio->add(new TFilter('login','=',"p".strtolower($ultimo_sobrenome)));
					$dados = $exists->load($criterio);
					if(count($dados) == 0){
						$professor = new professorRecord();
						$professor->login = "p".strtolower($ultimo_sobrenome);				
						$professor->nome = strtolower($nome);
						$professor->sobrenome = strtolower($sobrenome);
						$professor->ativo = 1;
						$professor->store();
						$nomeCompleto = strtolower(Alunos::removeAcentos($nome) . " " . Alunos::removeAcentos($sobrenome));
						//$ad = new AD();
						//$ad->onAddUser($professor->login, $nomeCompleto, "Teachers", null);
					}			
				}
				TTransaction::close();
				Redirect::toListarProfessores();
			}
			catch (Exception $e) 
			{
  				echo "O link está Off-line.";
			}
		}
		
		function onListarProfessores($id = null, $select = null) 
		{
			TTransaction::open();
			$repositorio = new TRepository("professor");
			$criterio = new TCriteria();
			if($id)
				$criterio->add(new TFilter("id","=",$id));
			$criterio->setProperty('order', 'nome');
			$professores = $repositorio->load($criterio);
			if($id || $select)
				return $professores;
			else
				View::loadView("professoresList", $professores);
		}	
		
		function onBuscarProfessor()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT * FROM professor WHERE login like '%{$_POST["busca"]}%' OR nome like '%{$_POST["busca"]}%' OR sobrenome like '%{$_POST["busca"]}%' ORDER BY nome";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();
			View::loadView("professoresList", $dados);
		}
		
		function altProfessor()
		{
			$dados = self::onListarProfessores($_GET['id']);
			View::loadView("professorAlterarForm",$dados);			
		}
			
		function onAlterarProfessor()
		{
			TTransaction::open();			
			$professor = new professorRecord();
			$professor->id = $_POST['id'];
			$professor->senha = md5($_POST['senha']);
			$professor->nome = $_POST['nome'];
			$professor->sobrenome = $_POST['sobrenome'];
			$professor->email = $_POST['email'];
			$professor->sexo = $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$professor->data_nascimento = $data;
			$professor->area_atuacao = $_POST["area_atuacao"];
			$professor->telefone = $_POST["telefone"];
			$professor->ativo = $_POST["ativo"];
			$professor->convidado = (int)$_POST["convidado"];
			$professor->store();
			TTransaction::close();
			
			//ALTERANDO SENHA NO AD
			$ad = new AD();
			$ad->onChangeUserPassword($_POST['login'],$_POST['senha']);
			
			Redirect::toListarProfessores();
		}
		function onDeletarProfessor()
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			TTransaction::open();
			$professor = new professorRecord();
			$professor->id = $_GET["id"];
			$professor->delete();
			TTransaction::close();
			
			$ad = new AD();
			$ad->onDelUser($_GET['login']);
			Redirect::toListarProfessores();
		}
	}
?>
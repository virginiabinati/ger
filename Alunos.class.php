<?php
	class Alunos {
		
		function __construct() 
		{

		}
		
		function addAluno() {
			$cursos = Cursos::onListarCursos(null,1);
			View::loadView("alunoForm",$cursos);
		}
		
		function onAddAluno() 
		{
			TTransaction::open();
			$aluno = new alunoRecord();
			$aluno->codigo 			= $_POST['codigo'];
			$aluno->senha 			= md5($_POST['senha']);
			$aluno->id_campus 		= $_POST['campus'];
			$aluno->nome 			= $_POST['nome'];
			$aluno->sobrenome 		= $_POST['sobrenome'];
			$aluno->email 			= $_POST['email'];
			$aluno->sexo 			= $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$aluno->data_nascimento = $data;
			$aluno->idcurso 		= $_POST["curso"];
			$aluno->etapa_corrente 	= $_POST["etapa"];
			$aluno->telefone 		= $_POST["telefone"];
			$aluno->ativo 			= 1;
			$aluno->id_campus		= 1;
			$aluno->store();
			TTransaction::close();
			
			$curso = Cursos::onListarCursos($_POST["curso"]);
			$codigoCurso = $curso[0]["codigo"];
			$nomeCompleto = $_POST['nome'] . " " . $_POST['sobrenome'];
			$ad = new AD();
			$ad->onAddUser($_POST['codigo'], $nomeCompleto, "Students", $codigoCurso);
			$ad->onActivateUser($_POST['codigo']);
			$ad->onChangePasswordNextLogonUser($_POST['codigo'],FALSE);
			$ad->onChangeUserPassword($_POST['codigo'], $_POST['senha']);
			Redirect::toListarAlunos();
		}
		
		function onListarAlunos($id = null) 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			if ($id)
				$plusWhere.=" WHERE t1.id = {$id}";
			$sql = "SELECT 
							t1.*, 
							t2.id as idcurso, 
							t2.curso 
					FROM 
							aluno as t1 
					INNER JOIN curso as t2 ON t1.idcurso = t2.id 
					{$plusWhere} 
					GROUP BY
							t1.id
					ORDER BY 
							t1.codigo, t1.nome
					limit 100";
			$res= $conn->Query($sql);
			$dados["alunos"]=$res->fetchAll();
			if ($id)
			{
				$dados["cursos"] = Cursos::onListarCursos(null,1);
				TTransaction::close();
				return $dados;
			}
			else
			{
				TTransaction::close();
				View::loadView("alunosList", $dados["alunos"]);
			}
		}	
		
		function onBuscarAluno() 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT t1.*, t2.id as idcurso, t2.curso FROM aluno as t1 INNER JOIN curso as t2 ON t1.idcurso = t2.id WHERE t1.codigo like '%{$_POST["busca"]}%' OR t1.nome like '%{$_POST["busca"]}%' OR t1.sobrenome like '%{$_POST["busca"]}%' ORDER BY t1.codigo, t1.nome";
			$res= $conn->Query($sql);
			$dados=$res->fetchAll();
			TTransaction::close();
			View::loadView("alunosList", $dados);
		}
		
		function altAluno()
		{
			$dados = self::onListarAlunos($_GET['id']);
			View::loadView("alunoAlterarForm",$dados);			
		}
			
		function onAlterarAluno()
		{
			
			TTransaction::open();
			//PEGANDO CODIGO DO CURSO
			$curso = new TRepository('curso');
			$criterio = new TCriteria();
			$criterio->add(new TFilter('id','=',$_POST["curso"]));
			$dados = $curso->load($criterio);
			$codigoCurso = $dados[0]["codigo"];
			
			//ALTERANDO DADOS DO ALUNO
			$aluno = new alunoRecord();
			$aluno->id = $_POST['id'];			
			$aluno->senha = md5($_POST['senha']);
			$aluno->id_campus = $_POST['campus'];
			$aluno->nome = $_POST['nome'];
			$aluno->sobrenome = $_POST['sobrenome'];
			$aluno->email = $_POST['email'];
			$aluno->sexo = $_POST['sexo'];
			$aux = explode("/",$_POST["data_nascimento"]);
			$data = $aux[2]."-".$aux[1]."-".$aux[0];
			$aluno->data_nascimento = $data;
			$aluno->idcurso = $_POST["curso"];
			$aluno->etapa_corrente = $_POST["etapa"];
			$aluno->telefone = $_POST["telefone"];
			$aluno->ativo = $_POST["ativo"];
			$aluno->store();			
			TTransaction::close();
			
			//ALTERANDO SENHA NO AD
			$dn = "CN={$_POST['codigo']},OU={$codigoCurso},OU=Students,OU=All Users,DC=liape,DC=unaerp,DC=br";
			$ad = new AD();
			$ad->onChangePassword($dn,$_POST['senha']);
			
			Redirect::toListarAlunos();
		}
		function onDeletarAluno()
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			TTransaction::open();
			$aluno = new alunoRecord();
			$aluno->id = $_GET["id"];
			$aluno->delete();
			TTransaction::close();
			
			$ad = new AD();
			$ad->onDelUser($_GET['codigo']);
			Redirect::toListarAlunos();
		}
		function xmlAlunos()
		{
			ob_start();
			if ($stream = fopen('http://www1.unaerp.br/app/integracao/liape/alunos.jsp?key=UCLNIIATA', 'r'))
			{
				echo stream_get_contents($stream);
				fclose($stream);
			}
			$conteudo = ob_get_contents();
			ob_clean();
			$conteudo = trim($conteudo);

			$xml = simplexml_load_string($conteudo);
			$alunos = array();
			$chave = $xml->ALUNOS->ALUNO;
			TTransaction::open();
			$ad = new AD();
			for($i=0; $i < count($chave); $i++ )
			{
				$sobrenome 		= "";
				$ativo 			= (string) $xml->ALUNOS->ALUNO[$i]->SITUACAO;
				$codigo_curso 	= (string) $xml->ALUNOS->ALUNO[$i]->CURSO->CODIGO;
				$curso 			= Cursos::onListarCursoCodigo($codigo_curso);
				$id_curso 		= $curso[0]["id"];
				$codigo_aluno 	= (string) $xml->ALUNOS->ALUNO[$i]->CODIGO;
				$nome_completo 	= (string) utf8_decode($xml->ALUNOS->ALUNO[$i]->NOME);
				$aux_nome 		= explode(" ",$nome_completo);
				$nome 			= $aux_nome[0];
				for($x = 1; $x < count($aux_nome); $x++){
					$sobrenome .= $aux_nome[$x] . " ";
				}
				$campus 		= trim(utf8_decode($xml->ALUNOS->ALUNO[$i]->CAMPUS));
				$campus 		= ($campus == "Ribeirão Preto") ? 1 : 2 ;
				$etapa 			= (string) $xml->ALUNOS->ALUNO[$i]->ETAPA;

				if ($campus == 1)
				{	
					if ( strtoupper($ativo) == 'A')
					{
						$exists = new TRepository('aluno');
						$criterio = new TCriteria();
						$criterio->add(new TFilter('codigo','=',$codigo_aluno));
						$dados = $exists->load($criterio);
						
						if(count($dados) == 0)
						{
							$alunos[] 	= array('codigo'=>$codigo_aluno);
							$nomeCurso	= self::removeAcentos($nome_curso);
							$BDaluno 	= new alunoRecord();
							$BDaluno->codigo = $codigo_aluno;
							$BDaluno->id_campus = $campus;
							$BDaluno->nome = $nome;
							$BDaluno->sobrenome = $sobrenome;
							$BDaluno->idcurso = $id_curso;
							$BDaluno->etapa_corrente = $etapa;
							$BDaluno->ativo = 1;
							$BDaluno->store();
							$user = $ad->searchUser($codigo_aluno);
							
							$nome_completo	= self::removeAcentos($nome_completo);
							
							if ($user[0]['dn'])
							{
								$dnArray = explode(',',$user[0]['dn']);				//Explodindo o DN do usuario
								$codigo_curso_atual = substr($dnArray[1],3);		//Capturando apenas o ID da OU, que representa o CURSO Ex. OU=2M, retornará 2M
								if ( strcasecmp($codigo_curso,$codigo_curso_atual) != 0)
								{
									echo 'Ativado : '.$codigo_aluno.'<BR>';
									$ad->onChangeUserCurso($codigo_aluno,$codigo_curso);
									$ad->onAddNameToUser($codigo_aluno,$nome_completo);
									$ad->onActivateUser($codigo_aluno);
								}
							}
							else
							{
								$ad->onAddUser($codigo_aluno, $nome_completo, "Students", $codigo_curso);
							}
						}
					}
					else
					{
							$user = $ad->searchUser($codigo_aluno);
							if (!$user[0]['dn'])
							{
								$ad->onAddUser($codigo_aluno, $nome_completo, "Students", $codigo_curso);
							}
							echo 'Inativado : '.$codigo_aluno.'<BR>';
							$ad->onInactivateUser($codigo_aluno);
					}
					
				}
			}
			TTransaction::close();
			//Redirect::toListarAlunos();
		} 
		function removeAcentos($str, $enc = 'UTF-8'){
		       $acentos = array(
		           'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
		           'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
		           'C' => '/&Ccedil;/',
		           'c' => '/&ccedil;/',
		           'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
		           'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
		           'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
		           'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
		           'N' => '/&Ntilde;/',
		           'n' => '/&ntilde;/',
		           'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
		           'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
		           'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
		           'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
		           'Y' => '/&Yacute;/',
		           'y' => '/&yacute;|&yuml;/',
		           'a.' => '/&ordf;/',
		           'o.' => '/&ordm;/'
		       );
		   return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
		}
	}
?>
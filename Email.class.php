<?php
class Email {

	public function __construct()
	{
		Permissoes::checa();
	}
	function onAddEmail()
	{
		View::loadView('emailForm');
	}
	function onAddModeloEmail()
	{
		$dados['editor'] = Editor::criarEditor();
		View::loadView('emailModeloForm',$dados);
	}
	function addEmail()
	{
		TTransaction::open();
		$email = new EmailRecord();
		$email->id = $_POST['id'];
		$email->de = $_POST['emissor'];
		$email->responderpara = $_POST['responderpara'];
		$email->store();
		TTransaction::close();
		self::onListarEmail();
	}
	function addModeloEmail()
	{
		TTransaction::open();
		$email = new EmailModeloRecord();
		$email->id = $_POST['id'];
		$email->titulo = $_POST['titulo'];
		$email->texto = stripslashes($_POST['editor']);
		$email->store();
		$id = $email->id;
		TTransaction::close();
		
		$pasta = substr($_SERVER['SCRIPT_FILENAME'],0,-10).'/anexos/toSave/';
		$novapasta = substr($_SERVER['SCRIPT_FILENAME'],0,-10).'/anexos/'.$id;
		if (!file_exists($novapasta))
			mkdir($novapasta);
		foreach (new DirectoryIterator($pasta) as $moverArquivo) 
		{
			if	($moverArquivo->isFile())
			{	
				$caminhoAntigo = $pasta.$moverArquivo->getFilename();
				$caminhoNovo = $novapasta.'/'.$moverArquivo->getFilename();
				rename($caminhoAntigo,$caminhoNovo);
			}
		}
		
		self::onAddModeloEmail();
	}
	function onAlterarEmail($id = NULL)
	{
		$id = $id ? $id : $_GET['id']; 
		$email = self::listarEmail($id);
		View::loadView('emailModeloForm',$email);
	}
	function onAlterarModeloEmail($idmodelo = NULL)
	{
		$idmodelo = $idmodelo ? $idmodelo : $_GET['idmodelo']; 
		$pasta = $_GET['idmodelo'] ? $_GET['idmodelo'] : 'temp';

		$dados['email'] = self::listarModeloEmail($idmodelo);
		$dados['texto'] = $dados['email'][0]['texto'];
		$dados['notitle'] = $_GET['notitle'];
		$dados['editor'] = Editor::criarEditor($dados);
		$dados['anexarPasta'] = $pasta;
		
		ob_start();
		$anexar[0] = array('anexarPasta'=>$pasta);
        View::loadView("emaiFormAnexos",$anexar);
		$dados['anexosForm'] = ob_get_contents();
		ob_clean();		
		
		View::loadView('emailModeloForm',$dados);
	}
	function listarEmail($id = NULL)
	{
		$id = $id ? $id : $_GET['id'];
		
		TTransaction::open();
		$repositorio = new TRepository("email");
		$criterio = new TCriteria();
		if ($id)
			$criterio->add(new TFilter('id','=',$id));
		else
			$criterio->setProperty("order","de");
		$emails = $repositorio->load($criterio);
		TTransaction::close();
			 
		return $emails;
	}
	function listarModeloEmail($id = NULL)
	{
		$id = $id ? $id : $_GET['id'];
		
		TTransaction::open();
		$repositorio = new TRepository("emailmodelo");
		$criterio = new TCriteria();
		if ($id)
			$criterio->add(new TFilter('id','=',$id));
		else
			$criterio->setProperty("order","titulo");
		$emails = $repositorio->load($criterio);
		TTransaction::close();
			 
		return $emails;
	}
	function onListarEmail()
	{
		$emails = self::listarEmail();
		View::loadView('emailsList',$emails);
	}
	function onListarModeloEmail()
	{
		$emails = self::listarModeloEmail();
		View::loadView('emailModelosList',$emails);
	}
	function sendEmail()
	{
		$dados['emails'] = self::listarEmail();
		$dados['clientes'] = Cliente::listarClientesAtivos();
		$dados['modelos'] = self::listarModeloEmail();
		$dados['editor'] = Editor::criarEditor();
		ob_start();
		$anexar[0] = array('anexarPasta'=>$_GET['id'] ? $_GET['id'] : 'temp');
		$dados['anexarPasta'] = $_GET['id'] ? $_GET['id'] : 'temp';
        View::loadView("emaiFormAnexos",$anexar);
		$dados['anexosForm'] = ob_get_contents();
		ob_clean();		
		View::loadView('emailToForm',$dados);
	}
	
	function onSendEmail()
	{
		$idEmail = $_POST['emissor'];
		$idDestinatarios = $_POST['list2'];
		$titulo = $_POST['titulo'];
		$mensagem = stripslashes($_POST['editor']);
		$anexarPasta =  $_POST['anexarPasta'];
		$pasta = substr($_SERVER['SCRIPT_FILENAME'],0,-10).'/anexos/'.$anexarPasta;
		$email 	 = Email::listarEmail($idEmail);
		$subject = $titulo;
		$message = $mensagem;

		$boundary = strtotime('NOW'); 
		$headers = 	'From: '.$email[0]['de']. "\r\n" .
					'Reply-To: '.$email[0]['responderpara']. "\r\n" .
					'X-Mailer: PHP/' .phpversion(). "\r\n";
		$headers .= "MIME-version: 1.0\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";

		$msg = "--" . $boundary . "\r\n";
		$msg .= "Content-type: text/html; charset=UTF-8\r\n\r\n";
		$msg .= $mensagem."\r\n";
		$msg .= "--" . $boundary . "\r\n";
		if ($anexarPasta != "")
		{
			foreach (new DirectoryIterator($pasta) as $anexarArquivo) 
			{
				if	($anexarArquivo->isFile())
				{
					$msg .= "--" . $boundary . "\r\n";
					$attach = $pasta.'/'.$anexarArquivo->getFilename();
					$attach_name = basename($attach);
					$fileatt_type = "application/octet-stream";
					$msg .= "Content-type: ".$fileatt_type."; name=\"".$attach_name."\"\r\n";
					$msg .= "Content-Transfer-Encoding: base64\r\n";
					$msg .= "Content-Disposition: attachment; filename=\"".$attach_name."\"\r\n\r\n";
		
					ob_start();
					   readfile($attach);
					   $enc = ob_get_contents();
					ob_end_clean();
					
					$msg_temp = base64_encode($enc). "\r\n";
					$tmp[1] = strlen($msg_temp);
					$tmp[2] = ceil($tmp[1]/76);
					
					for ($b = 0; $b <= $tmp[2]; $b++) 
					{
					    $tmp[3] = $b * 76;
					    $msg .= substr($msg_temp, $tmp[3], 76)."\r\n";
					}
					unset($msg_temp, $tmp, $enc);
					$msg .= "--" . $boundary . "\r\n";
					if ($anexarPasta == 'temp')
					{
						@unlink($attach);
					}
				}
			}
		} 
		
		foreach ($idDestinatarios as $idDestinatario)
		{
			$cliente = Cliente::listarClientes($idDestinatario);
			$to      = $cliente[0]['email'];
			$sucesso = mail($to, $subject, $msg, $headers);
			$dados['enviados'][]= array('email'=>$cliente[0]['email'],'sucesso'=>$sucesso);    
		}
		View::loadView('emailEnviadosList',$dados);
	}
	
	function onSendEmailTESTE(){
		ini_set("SMTP","uranus.liape.unaerp.br" );
		ini_set('sendmail_from', 'liape@liape.unaerp.br'); 
		self::onSendEmailTo('liape@unaerp.br','filipegallo@gmail.com','Teste','SAJKDLFHAS KLASDFASDFASDFASDF');
	}
	
	
	
    /*
     * método onSendEmailTo()
     *  Método Genérico para o envio de Email
     *
     * @param $emissor = endereço que deverá constar no header como emissor
     * @param $destinatarios = array que contendo os destinatários que receberam a mensagem
     * @param $titulo = titulo do e-mail
     * @param $mensagem = corpo da mensagem propriamente dita
     * @param $anexos = array de anexos com o caminho completo do arquivo.
     */	
	function onSendEmailTo($emissor, $destinatarios, $titulo, $mensagem, $anexos = NULL)
	{
		$mensagem = stripslashes($mensagem);
		$subject = $titulo;

		$boundary = strtotime('NOW'); 
		$headers = 	'From: '.$emissor. "\r\n" .
					'Reply-To: '.$emissor. "\r\n" .
					'X-Mailer: PHP/' .phpversion(). "\r\n";
		$headers .= "MIME-version: 1.0\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";

		$msg = "--" . $boundary . "\r\n";
		$msg .= "Content-type: text/html; charset=UTF-8\r\n\r\n";
		$msg .= $mensagem."\r\n";
		$msg .= "--" . $boundary . "\r\n";
		
		foreach ((array)$anexos as $anexarArquivo) 
		{
			if	(is_file($anexarArquivo))
			{
				$msg .= "--" . $boundary . "\r\n";
				$attach = $anexarArquivo;
				$attach_name = basename($attach);
				$fileatt_type = "application/octet-stream";
				$msg .= "Content-type: ".$fileatt_type."; name=\"".$attach_name."\"\r\n";
				$msg .= "Content-Transfer-Encoding: base64\r\n";
				$msg .= "Content-Disposition: attachment; filename=\"".$attach_name."\"\r\n\r\n";
	
				ob_start();
				   readfile($attach);
				   $enc = ob_get_contents();
				ob_end_clean();
				
				$msg_temp = base64_encode($enc). "\r\n";
				$tmp[1] = strlen($msg_temp);
				$tmp[2] = ceil($tmp[1]/76);
				
				for ($b = 0; $b <= $tmp[2]; $b++) 
				{
				    $tmp[3] = $b * 76;
				    $msg .= substr($msg_temp, $tmp[3], 76)."\r\n";
				}
				unset($msg_temp, $tmp, $enc);
				$msg .= "--" . $boundary . "\r\n";
			}
		}
		
		foreach ( (array) $destinatarios as $destinatario)
		{
			$sucesso = mail($destinatario, $subject, $msg, $headers);
			$dados['enviados'][]= array('email'=>$destinatario,'sucesso'=>$sucesso);    
		}
		View::loadView('emailEnviadosList',$dados);
	}
	
	function onUploadAnexo($anexarPasta = NULL)
    {
    	$anexarPasta = $anexarPasta ? $anexarPasta : $_GET['anexarPasta'] ;
    	$path = substr($_SERVER['SCRIPT_FILENAME'],0,-12).'anexos/'.$anexarPasta.'/';
        if ($_FILES['anexo'])
        {
            $Tmpname = $_FILES['anexo'][tmp_name];
            $name = $_FILES['anexo'][name];
			$anexo = $path.$name;
			move_uploaded_file($Tmpname,$anexo);
        }
        $dados[0] = array('anexarPasta'=>$anexarPasta);
        View::loadView("emaiFormAnexos",$dados);
    }
    function onRemoveUploadedAnexo($caminho = NULL)
    {
    	$path = substr($_SERVER['SCRIPT_FILENAME'],0,-12).'anexos/';
    	$caminho = $caminho ? $caminho : $_GET['caminho'];
    	$exploded = explode('/',$caminho);
    	$pathFile = $path.$caminho;
        @unlink($pathFile);
        $anexarPasta = $exploded[0];
		$dados[0] = array('anexarPasta'=>$anexarPasta);
        View::loadView("emaiFormAnexos",$dados);
    }
	
	function delEmail($id = NULL)
	{
		$id = $id ? $id : $_GET['id'];
		TTransaction::open();
		$email = new EmailRecord();
		$email->id = $id;
		$email->delete();
		TTransaction::close();
		Redirect::toListarEmail();
	}
	function delModeloEmail($id = NULL)
	{
		$id = $id ? $id : $_GET['id'];
		TTransaction::open();
		$email = new EmailModeloRecord();
		$email->id = $id;
		$email->delete();
		TTransaction::close();
		Redirect::toListarEmailModelo();
	}
}
?>
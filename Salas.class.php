<?php
	class Salas {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function addSala() 
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			View::loadView("salaForm");
		}
		
		function onAddSala() 
		{
			TTransaction::open();
			$sala = new salaRecord();
			$sala->bloco = strtoupper($_POST['bloco']);
			$sala->numero = $_POST['numero'];
			$sala->store();
			TTransaction::close();
			Redirect::toListarSalas();
		}
		
		function onListarSala($id = null) 
		{
			$dados['sala'] = $_GET['numero'].$_GET['bloco'];
			$sala =  self::onGetSalaId($_GET['numero'],$_GET['bloco']);
			$dados['hardware'] = self::onGetHardwareSala($sala[0]['id']);
			$dados['equipamentoRede'] = Equipamentos::onListarEquipamento($sala[0]['id']);
			View::loadView("sala", $dados);
		}
		
		function onListarSalas($id = null, $select = null) 
		{
			TTransaction::open();
			$repositorio = new TRepository("sala");
			$criterio = new TCriteria();
			$criterio->setProperty('order', 'bloco,numero');
			$sala = $repositorio->load($criterio);
			TTransaction::close();
			if($id || $select)
				return $sala;
			else
				View::loadView("salasList", $sala);
		}
		
		function onListarSalasLivres() 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
								*
					FROM 
								sala
					WHERE 
								livre = '1'
					ORDER BY 
								bloco, numero";
								
			$res= $conn->Query($sql);
			$salas["livres"]=$res->fetchAll();
			$salas["add"]=self::onListarSalas(null,1);
			TTransaction::close();
			View::loadView("salasLivres", $salas);			
		}
		
		function onAddSalaLivre() 
		{
			TTransaction::open();
			$sala = new salaRecord();
			$sala->id = $_POST['idsala'];
			$sala->livre = 1;
			$sala->store();
			TTransaction::close();
			self::onListarSalasLivres();
		}
		
		function onDeletarSalaLivre() 
		{
			TTransaction::open();
			$sala = new salaRecord();
			$sala->id = $_GET['id'];
			$sala->livre = 0;
			$sala->store();
			TTransaction::close();
			self::onListarSalasLivres();
		}
		
		function onDeletarSala()
		{
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				die;
			}
			TTransaction::open();
			$sala = new salaRecord();
			$sala->id = $_GET["id"];
			$sala->delete();
			TTransaction::close();
			Redirect::toListarSalas();
		}
		
		function onGetSalaId($numero= null, $bloco=null) 
		{
			$numero = $numero ? $numero : $_GET['numero'];
			$bloco = $bloco ? $bloco : $_GET['bloco'];
			TTransaction::open();
			$repositorio = new TRepository("sala");
			$criterio = new TCriteria();
			$criterio->add(new TFilter('numero','=',$numero ));
			$criterio->add(new TFilter('bloco','=',$bloco ));
			$sala = $repositorio->load($criterio);
			
			TTransaction::close();
			return $sala;
		}
		
		function onGetHardwareSala($idSala){
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT h.*, c.nome from hardware h, computador c 
					WHERE c.idhardware = h.id and c.idsala =".$idSala."
					ORDER BY c.nome ASC";
			$res= $conn->Query($sql);
			$hardware=$res->fetchAll();
			TTransaction::close();
			return $hardware;			
		}
		function shutdownPC($operacao=NULL, $microFQDN=null, $tempo=NULL, $mensagem=null)
		{
			$operacao = $operacao ? $operacao : $_POST['operacao'];
			$microFQDN = $microFQDN ? $microFQDN : $_POST['microFQDN'];
			$tempo = $tempo ? $tempo : $_POST['tempo'];
			$tempo = $tempo ? $tempo : '60';
			$mensagem = $mensagem ? $mensagem : $_POST['mensagem'];
			exec('shutdown -'.$operacao.' -m \\\\'.$microFQDN.' -t '.$tempo.' -c "'.$mensagem.'"');
			self::onListarSalas();
		}
	}
?>
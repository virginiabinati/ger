<?php
class Agenda{
	function addAgenda()
	{
		//Permissoes::checa();
		$dados["data"]=$_GET["data"];
		$dados["hora"]=$_GET["hora"];
		$dados["iddentista"]=$_GET["iddentista"];
		$dados["nomedentista"]=$_GET["nomedentista"];
		ob_start();
		Clientes::onSelectClientes($dados['idcliente']);
		$dados['cliente'] = ob_get_contents();
		ob_clean();	
		
		ob_start();
		Tratamentos::onSelectTratamento($dados['idtratamento']);
		$dados['tratamento'] = ob_get_contents();
		ob_clean();			
		View::loadView("consultaForm",$dados);
	}
	function onAddAgenda()
	{
		TTransaction::open();
		$agenda = new agendaRecord();
		$agenda->iddentista = $_POST["iddentista"];
		$agenda->idcliente = $_POST["idcliente"];
		if ($_POST["idtratamento"] <> '')
		$agenda->idtratamento = $_POST["idtratamento"];
		$agenda->datahora = $_POST["datahora"];
		$agenda->motivo = $_POST["motivo"];
		$agenda->store();		
		TTransaction::close();
		echo '
			<script type="text/javascript">
				self.parent.window.location.reload();
				self.parent.tb_remove()
				</script>
			';
	}
	function alterarAgenda()
	{
		//Permissoes::checa();
		TTransaction::open();
		$repositorio = new TRepository("agenda");
		$criterio = new TCriteria();
		$criterio->add(new TFilter("id","=",$_GET[idagenda]));
		$criterio->setProperty('order', 'id');		
		$agenda = $repositorio->load($criterio,Array('id','idcliente','idtratamento','datahora','status'));	
		
		$dados["agenda"]=$agenda[0];
		$dados["id"]=$_GET[id];
		$dados["data"]=$_GET[data];
		$dados["hora"]=$_GET[hora];
		$dados["status"]=$_GET[status];
		$dados["nomecliente"]=$_GET[nomecliente];
		$dados["nomedentista"]=$_GET[nomedentista];
		$dados["tratamento"]=$_GET[tratamento];	
		
		View::loadView("alterarConsultaForm",$dados);
		TTransaction::close();				
	}
	function onAlterarAgenda(){
		TTransaction::open();			
		$agenda = new agendaRecord();
		$agenda->id = $_POST["id"];
		$agenda->status = $_POST['status'];				
		$agenda->motivo = $_POST['motivo'];		
		$agenda->store();
		TTransaction::close();
		echo '
			<script type="text/javascript">
				self.parent.window.location.reload();
				self.parent.tb_remove()
				</script>
			';
	}
	function selecionarDentista($id = NULL)
	{

		$id = Login::retornaDados('id');
		
		TTransaction::open();
		$repositorio = new TRepository("dentista");
		$criterio = new TCriteria();
		$criterio->add(new TFilter("idusuario","=",$id));
		
		if (!$dentista)
		{
			$repositorio = new TRepository("dentista");
			$criterio = new TCriteria();
			$criterio->add(new TFilter("nome","LIKE","Juliano%"));
			$criterio->setProperty('order', 'nome');		
			
		}
		$dentista = $repositorio->load($criterio,Array('id'));
		TTransaction::close();
		
		self::onSelecionarDentista($dentista[0][id]);
	}
	function onSelecionarDentista($iddentista=NULL) 
	{
		Permissoes::checa();
		TTransaction::open();
		if (!$iddentista)
			$iddentista = $_GET['iddentista'];
		$repositorio = new TRepository("dentista");
		$criterio = new TCriteria();
		$criterio->add(new TFilter("id","=",$iddentista));
		$criterio->setProperty('order', 'nome');		
		$dentista = $repositorio->load($criterio,Array('id','nome','minutos_consulta'));	
		$dados["dentistas"]=$dentista[0];				
		Session::salva('dentista',$dentista[0]);
		if ($_GET["data"])
			$dados["dt"]=$_GET["data"];
		else
			$dados["dt"]=date('d/m/Y');	

		$sql="select a.id as idagenda,a.datahora,a.status,a.motivo,c.id as idcliente,c.nome,t.id as idtratamento,t.status as statust from agenda a inner join cliente c on a.idcliente = c.id inner join tratamento t on a.idtratamento = t.id";
		$conn=TTransaction::get();
		$res = $conn->Query($sql);
		$dados["agenda"]= $res->fetchAll();
		$dados["todos_dentistas"] = Dentistas::onListarTodosDentistas();
		View::loadView("agendaForm",$dados);
		TTransaction::close();		
	}
	function onListaAgenda($data_inicial, $data_final,$dentista)
	{
		TTransaction::open();
		$sql="select a.id as idagenda,a.datahora,a.status,a.motivo,c.id as idcliente,c.nome,t.id as idtratamento,t.status as statust 
		from agenda a inner join cliente c on a.idcliente = c.id left join tratamento t on a.idtratamento = t.id 
		where a.datahora between '{$data_inicial} 00:00:00' and '{$data_final} 23:59:00' and a.iddentista={$dentista}";		
		
		$conn=TTransaction::get();
		$res = $conn->Query($sql);
		return $res->fetchAll();
		TTransaction::close();
	}
	function onDeletarConsulta()
	{
		TTransaction::open();
		$agenda = new agendaRecord();
		$agenda->id = $_GET["id"];
		$agenda->delete();
		TTransaction::close();
		echo '<script type="text/javascript">
				self.parent.window.location.reload();
				self.parent.tb_remove()
			  </script>';	
	}	
}
?>
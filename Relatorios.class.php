<?php
	class Relatorios {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
			Permissoes::checa();
			if($_SESSION["dados_usuario"]["idgrupo"] != 1)
			{
				echo "<center><img src='images/stop.png'><br /><h2>Você não tem permissão para acessar esse link! <a href='javascript:history.go(-1)'>Voltar</a></h2></center>";
				die;
			}
		}
		
		function onListarRelatorios() 
		{
			View::loadView("relatoriosList");
		}
		function onRelatorioChamados()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
								Comp.nome as sala_computador,
								count(Comp.id) as qtd
					FROM 
								pendencia as Pend 
					INNER JOIN 
								computador as Comp ON Comp.id = Pend.id_computador 
					GROUP BY 
								Comp.id ";	
			$res = $conn->Query($sql);
			$rel = $res->fetchAll();
			TTransaction::close();
			View::loadView("relatorioChamadosList",$rel);		
		}
		function onRelatorioEscalas()
		{
			$ano = date("Y");
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT 
								Usu.id,
								Usu.nome,
								Usu.sobrenome,
								DATE_FORMAT(Esc.data_escala,'%m/%Y') as mes_ano
					FROM 
								escala_facilitador as Esc 
					INNER JOIN 
								usuario as Usu ON Usu.id = Esc.id_usuario
					WHERE 
								DATE_FORMAT(Esc.data_escala,'%Y') = '". $ano ."' 
					ORDER BY
								Usu.nome, mes_ano";	
			$res = $conn->Query($sql);
			$rel = $res->fetchAll();
			TTransaction::close();
			View::loadView("relatorioEscalasList",$rel);		
		}
		function onRelatorioLogon() 
		{
			$where="";
			if($_POST['sala'])
				$where.=" AND log.computador like 'sala".$_POST['sala']."%'";
			if($_POST['usuario'])
				$where.=" AND log.login='".$_POST['usuario']."'";
			TTransaction::open();
			$conn=TTransaction::get();
			if($_POST['de']){
				$data1 = CompararDatas::converte($_POST['de']);
				$data2 = CompararDatas::converte($_POST['ate']);
				$sql = "SELECT 
									cur.curso,
									log.computador, 
									log.login,									 
									log.data_hora 
						FROM 
									logon as log 
						INNER JOIN
									aluno as alu on log.login = alu.codigo 
						INNER JOIN
									curso as cur on alu.idcurso = cur.id  
						WHERE 
									log.data_hora between '".$data1." 00:00:00' 
						AND 
									'".$data2." 23:59:59'".$where." 
						ORDER BY
									log.computador DESC , log.data_hora DESC";

				/*
				SELECT 
									cur.curso,
									log.computador, 
									log.login,									 
									log.data_hora 
						FROM 
									logon as log 
						INNER JOIN
									aluno as alu on log.login = alu.codigo 
						INNER JOIN
									curso as cur on alu.idcurso = cur.id  
						WHERE 
									computador LIKE 'sala11a%'
						ORDER BY
									log.computador DESC , log.data_hora DESC
				Formula para o excel =SE( E((B292=B293);( (D292-D293) < 0,25));D292-D293;0)

				*/
				
			} else {
				$sql = "SELECT * FROM `logon` WHERE data_hora between '".date('Y-m-d')." 00:00:00' and '".date('Y-m-d')." 23:59:59'".$where." order by data_hora desc";
			}
			$res= $conn->Query($sql);
			$dados["logon"]=$res->fetchAll();
			TTransaction::close();
			$dados["sala"]=Salas::onListarSalas(null,1);			
			View::loadView("relatorioLogon", $dados);
		}
		function onRelatorioAtivos()
		{
			TTransaction::open();
			$conn=TTransaction::get();
			$sql = "SELECT count(*) as ativo FROM aluno WHERE ativo=1";
			$res= $conn->Query($sql);
			$dados["ativos"]=$res->fetchAll();
			TTransaction::close();
			View::loadView("relatorioAtivosList", $dados);
			
		}	
		function onRelatorioHardware() 
		{
			TTransaction::open();
			$conn=TTransaction::get();
			if($_POST['idsala'])
				$sql = "SELECT h.*, c.nome from hardware h, computador c where c.idhardware = h.id and c.idsala =".$_POST['idsala']." order by c.nome asc";
			else
				$sql = "SELECT h.*, c.nome from hardware h, computador c where c.idhardware = h.id and c.idsala = 7 order by c.nome asc";
			$res= $conn->Query($sql);
			$dados["hardware"]=$res->fetchAll();
			TTransaction::close();
			$dados["sala"]=Salas::onListarSalas(null,1);
			View::loadView("relatorioHardwareList", $dados);
		}
	}
?>
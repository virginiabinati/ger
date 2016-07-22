<? 
if ($params) 
{
	
	 $dentista= $_SESSION['dentista'];
	
	if($params["dt"])
		$dt = $params["dt"];
	else 
		$dt = date('d/m/Y');
	
		$a = explode("/",$dt);
		$dia = $a[0];
		$mes = $a[1];
		$ano = $a[2];	
		$w = date('w',mktime(0,0,0,$mes,$dia,$ano));		
?>
<form name="formData" id="formData" action="?class=Agenda&method=onSelecionarDentista&iddentista=<?=$dentista[id] ?>" method="post">
<script>DateInput('data', true, 'DD/MM/YYYY')</script>
</form>
<table cellpadding="0" cellspacing="0" class="tabela_dentista">
<tr>
	<td align="center">
	<? 
	foreach ($params[todos_dentistas] as $nome_dentista)
	{
		echo '<a href="?class=Agenda&method=onSelecionarDentista&iddentista='.$nome_dentista[id].'"> &nbsp;'.$nome_dentista['nome'].'&nbsp;</a> &nbsp;   ';
	}
	?>
	</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" class="tabela_agenda">
	<? 
	if($w<>0) 
	{ 
	?>	
			<caption>Agenda de <?=$dentista[nome] ?> - <?=$mes.'/'.$ano ?></caption>		
	<?
	}

		$d = $ano."-".$mes."-".$dia;
		switch ($w)
		{			
			case 0:	
				$segunda = new DateTime($d);
				$segunda->modify("+1 day");	
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$terca->modify("+2 day");								
				$tr = $terca->format("Y-m-d");

				$quarta = new DateTime($d);
				$quarta->modify("+3 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("+4 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("+5 day");								
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+6 day");								
				$sb = $sabado->format("Y-m-d");			
			break;
			case 1:				
				$segunda = new DateTime($d);
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$terca->modify("+1 day");								
				$tr = $terca->format("Y-m-d");

				$quarta = new DateTime($d);
				$quarta->modify("+2 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("+3 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("+4 day");								
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+5 day");								
				$sb = $sabado->format("Y-m-d");
			break;
			case 2:				
				$segunda = new DateTime($d);
				$segunda->modify("-1 day");								
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$tr = $terca->format("Y-m-d");

				$quarta = new DateTime($d);
				$quarta->modify("+1 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("+2 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("+3 day");								
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+4 day");								
				$sb = $sabado->format("Y-m-d");
			break;
			case 3:
				$segunda = new DateTime($d);
				$segunda->modify("-2 day");								
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$terca->modify("-1 day");								
				$tr = $terca->format("Y-m-d");
				
				$quarta = new DateTime($d);
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("+1 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("+2 day");								
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+3 day");								
				$sb = $sabado->format("Y-m-d");
			break;
			case 4:
				$segunda = new DateTime($d);
				$segunda->modify("-3 day");								
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$terca->modify("-2 day");								
				$tr = $terca->format("Y-m-d");
				
				$quarta = new DateTime($d);
				$quarta->modify("-1 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("+1 day");								
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+2 day");								
				$sb = $sabado->format("Y-m-d");
			break;
			case 5:
				$segunda = new DateTime($d);
				$segunda->modify("-4 day");								
				$sg = $segunda->format("Y-m-d");
				
				$terca = new DateTime($d);
				$terca->modify("-3 day");								
				$tr = $terca->format("Y-m-d");
				
				$quarta = new DateTime($d);
				$quarta->modify("-2 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("-1 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sx = $sexta->format("Y-m-d");
				
				$sabado = new DateTime($d);
				$sabado->modify("+1 day");								
				$sb = $sabado->format("Y-m-d");
			break;
			case 6:
				$segunda = new DateTime($d);
				$segunda->modify("-5 day");								
				$sg = $segunda->format("Y-m-d");
						
				$terca = new DateTime($d);
				$terca->modify("-4 day");								
				$tr = $terca->format("Y-m-d");

				$quarta = new DateTime($d);
				$quarta->modify("-3 day");								
				$qr = $quarta->format("Y-m-d");
				
				$quinta = new DateTime($d);
				$quinta->modify("-2 day");								
				$qu = $quinta->format("Y-m-d");
				
				$sexta = new DateTime($d);
				$sexta->modify("-1 day");								
				$sx = $sexta->format("Y-m-d");
								
				$sabado = new DateTime($d);
				$sb = $sabado->format("Y-m-d");
			break;			
		}	
		
		?>

	<thead>

	<tr>
		<th width="58"></th>
		<?						
					if ($sg == date('Y-m-d'))
						$cls="class='tabela_agenda_dia_atual'";
					if ($tr == date('Y-m-d'))
						$clt="class='tabela_agenda_dia_atual'";
					if ($qr == date('Y-m-d'))
						$clq="class='tabela_agenda_dia_atual'";
					if ($qu == date('Y-m-d'))
						$clu="class='tabela_agenda_dia_atual'";
					if ($sx == date('Y-m-d'))
						$clx="class='tabela_agenda_dia_atual'";
					if ($sb == date('Y-m-d'))
						$clb="class='tabela_agenda_dia_atual'";
					
					echo "<th {$cls}>Segunda - {$segunda->format('d/m')}</th>";
					echo "<th {$clt}>Terça - {$terca->format('d/m')}</th>";
					echo "<th {$clq}>Quarta - {$quarta->format('d/m')}</th>";
					echo "<th {$clu}>Quinta - {$quinta->format('d/m')}</th>";
					echo "<th {$clx}>Sexta - {$sexta->format('d/m')}</th>";
					echo "<th {$clb}>Sábado - {$sabado->format('d/m')}</th>";
		?>
	</tr>
	</thead>
		
		<?
		
		$data = new DateTime("7:00:00");
		$i=0;
		
		//$resultado = Agenda::onListaAgenda($segunda->format('Y-m-d'), $sabado->format('Y-m-d'),$dentista[id]);
		
		function busca ($dia, $hora, $resultado)
		{	
			if($resultado<>"")
			{
				foreach($resultado as $agenda)
				{
					$datahora=$dia.' '.$hora;
					if ($agenda[datahora] == $datahora)
					$dados[]=$agenda;							
				}
			}				
				return $dados;
							
		}
		
		while($i<=(780/$dentista[minutos_consulta]))
		{
			echo "<tr><td class='tabela_agenda_hora'>{$data->format("H:i")}</td>";
			$var = busca($sg,$data->format("H:i:s"),$resultado);
			echo '<td>';						
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				
				foreach($var as $valor)
				{ 
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$sg}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				

				}
			}
			
						
			//if ((!$chegou) && (!$aguardando))
				echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$sg}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			$var = busca($tr,$data->format("H:i:s"),$resultado);
			echo '<td>';			
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				foreach($var as $valor)
				{
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$tr}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				
	
					
				}
			}			
			//if ((!$chegou) && (!$aguardando))
			echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$tr}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			$var = busca($qr,$data->format("H:i:s"),$resultado);
			echo '<td>';			
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				foreach($var as $valor)
				{
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$qr}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				
	
				}
			}			
			//if ((!$chegou) && (!$aguardando))
			echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$qr}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			$var = busca($qu,$data->format("H:i:s"),$resultado);
			echo '<td>';			
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				foreach($var as $valor)
				{
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$qu}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				
				}
			}			
			//if ((!$chegou) && (!$aguardando))
			echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$qu}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			$var = busca($sx,$data->format("H:i:s"),$resultado);
			echo '<td>';			
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				foreach($var as $valor)
				{
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$sx}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				
				}
			}			
			//if ((!$chegou) && (!$aguardando))
			echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$sx}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			$var = busca($sb,$data->format("H:i:s"),$resultado);
			echo '<td>';			
			if ($var)
			{
				$aguardando=FALSE;
				$chegou=FALSE;
				foreach($var as $valor)
				{
					$nomeCompleto = $valor[nome]; 
					$valor[nome] = substr($valor[nome],0,10);
						echo "<span class='".$valor[status]."'><a href='controle.php?class=Agenda&method=alterarAgenda&id={$valor[idagenda]}&data={$sb}&hora={$data->format("H:i")}&nomedentista={$dentista[nome]}&nomecliente={$nomeCompleto}&tratamento={$valor[statust]}&status={$valor[status]}&keepThis=true&TB_iframe=true&height=335&width=350' title='{$nomeCompleto}' class='thickbox'>".substr($valor[motivo],0,3).' '.$valor[nome]."</a></span><br>";				
				}
			}			
			//if ((!$chegou) && (!$aguardando))
			echo "<a href='controle.php?class=Agenda&method=addAgenda&data={$sb}&hora={$data->format("H:i:s")}&iddentista={$dentista[id]}&nomedentista={$dentista[nome]}&keepThis=true&TB_iframe=true&height=335&width=350' title='Consulta' class='thickbox'> + </a>";
			$aguardando=FALSE;
			$chegou=FALSE;
			echo '</td>';
			
			echo "</tr>";											
			
			$data->modify("+".$dentista[minutos_consulta]." minutes");
			$i++;			
		}				
	?>
</table>
<? 
}
?>
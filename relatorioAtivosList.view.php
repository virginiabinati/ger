<?
	if($params['ativos'])
		$ativos = $params['ativos'];
?>
<div class="geral_form">
	<table class="tabela_comum" style="width: 100px;">
	<tr><th class="center">Ativos</th></tr>
	<?
	if($ativos)
	{
		foreach($ativos as $ativo)
		{
			echo "<tr><td>".$ativo['ativo']."</td></tr>";
		}
	}
	?>
	</table>
</div>
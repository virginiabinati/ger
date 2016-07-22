<?
	if($params['hardware'])
		$hardware = $params['hardware'];
?>
<script type="text/javascript">
function pesquisar(){
	document.forms.frmPesquisar.submit();
}
</script>
<div class="geral_form">
	<form name="frmPesquisar" action="?class=Relatorios&method=onRelatorioHardware" method="post">
		<table>
			<tr>
				<td class="titulo">Sala:</td>
				<td>
					<select name="idsala">
						<?
							foreach($params["sala"] as $sala)
							{
								echo "<option value='{$sala["id"]}'>{$sala["numero"]} {$sala["bloco"]}</option>";
							}
						?>
					</select>
				</td> 
				<td class="salvar" style="padding-bottom: 10px; padding-left: 10px"><div class="botao" onclick="pesquisar()">Ok</div></td>
			</tr> 
		</table>
	</form>
</div>
<div class="geral_form">
	<table class="tabela_comum" style="width: 800px;">
	<tr>
		<th class="center">Computador</th>
		<th class="center">processador</th>
		<th class="center">Ram</th>
		<th class="center">HD</th>
		<th class="center">Placa Mãe</th>
		<th class="center">Placa Video</th>
		<th class="center">Placa Rede</th>
		<th class="center">CD Rom</th>
	</tr>
	<?
	if($hardware)
	{
		foreach($hardware as $hard)
		{
			echo "<tr><td>".$hard['nome']."</td><td>".$hard['processador']."</td><td>".$hard['ram_qtd']."</td><td>".$hard['hd']."</td><td>".$hard['placa_mae']."</td><td>".$hard['placa_video']."</td><td>".$hard['placa_rede']."</td><td>".$hard['cdrom']."</td></tr>";
		}
	}
	?>
	</table>
</div>
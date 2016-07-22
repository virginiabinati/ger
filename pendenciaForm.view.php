<?
	/*echo "<pre>";
		print_r($params);
		echo "</pre>";*/
?>
<script type="text/javascript">
function salvar(){
	document.forms.addPendencia.submit();
}
function verificaTipo(option)
{
	if((option == "recado")||(option == "escala"))
		$("#trSalaMicro").hide();
	else
		$("#trSalaMicro").show();
}
</script>
<form name="addPendencia" method="POST" action="?class=Pendencias&method=onAddPendencia">
<div class="geral_form" style="width:40%">
<table class="tabela_form">
<caption>Cadastrar pendencia</caption>
<tr>
	<td>Tipo:</td>
	<td>
		<select name="tipo" onchange="verificaTipo(this.value)">
			<option value="recado">Recado</option>
			<option value="chamado">Chamado</option>
			<option value="escala">Escala</option>
			<option value="software">Software</option>
			<option value="manutencao">Manutenção</option>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2">
		<div id="trSalaMicro" style="display:none">
		Sala_Micro: 
		<select name="sala_micro" style="margin-left:21px">
			<option value="0">Selecione...</option>
		<? foreach($params["salas_computadores"] as $salaMicro) { ?>
			<option value="<?=$salaMicro["idcomputador"] ?>"><?=$salaMicro["nome"] ?></option>
		<? } ?>
		</select>
		</div>
	</td>
</tr>
<tr><td>Problema:</td><td><textarea name="problema"></textarea></td></tr>
<tr>
	<td>Designar para:</td>
	<td>
	<? foreach($params["usuarios"] as $usuario) { ?>
		<label><input type="checkbox" name="designar_para[]" value="<?=$usuario["id"] ?>" /> <?=$usuario["nome"] ?></label><br />
	<? } ?>
	</td>	
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
</table>
</div>	
</form>
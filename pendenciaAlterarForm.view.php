<?
if($params){
	foreach($params as $pendencia){
?>
<script type="text/javascript">
jQuery(function($){
   <? if($pendencia["pendencia"]["tipo"] == "recado" || $pendencia["pendencia"]["tipo"] == "escala") { ?>
		$("#trSalaMicro").hide();
   <? } else { ?>
		$("#trSalaMicro").show();
   <? } ?>
});
function verificaTipo(option)
{
	if((option == "recado")||(option == "escala"))
		$("#trSalaMicro").hide();
	else
		$("#trSalaMicro").show();
}
function alterar(){
	document.forms.altPendencia.submit();
}
</script>
<form name="altPendencia" method="POST" action="?class=Pendencias&method=onAlterarPendencia">
<input type="hidden" name="id" value="<?=$pendencia["pendencia"]["id"] ?>" />
<div class="geral_form" style="width:40%">
<table class="tabela_form">
<caption>Alterar pendencia</caption>
<tr>
	<td>Tipo:</td>
	<td>
		<select name="tipo" onchange="verificaTipo(this.value)">
			<option value="recado" <?=($pendencia["pendencia"]["tipo"]=="recado") ? "selected" : ""; ?>>Recado</option>
			<option value="chamado" <?=($pendencia["pendencia"]["tipo"]=="chamado") ? "selected" : ""; ?>>Chamado</option>
			<option value="escala" <?=($pendencia["pendencia"]["tipo"]=="escala") ? "selected" : ""; ?>>Escala</option>
			<option value="software" <?=($pendencia["pendencia"]["tipo"]=="software") ? "selected" : ""; ?>>Software</option>
			<option value="manutencao" <?=($pendencia["pendencia"]["tipo"]=="manutencao") ? "selected" : ""; ?>>Manutenção</option>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2">
		<div id="trSalaMicro" style="display:none">
		Sala_Micro: 
		<select name="sala_micro" style="margin-left:21px">
			<option value="0">Selecione...</option>
		<? foreach($pendencia["pendencia"]["salas_computadores"] as $salaMicro) { 
			if($pendencia["pendencia"]["id_computador"] == $salaMicro["idcomputador"]) 
				$selected = "selected";
			else
				$selected = "";
		?>
			<option value="<?=$salaMicro["idcomputador"] ?>" <?=$selected ?>><?=$salaMicro["nome"] ?></option>
		<? } ?>
		</select>
		</div>
	</td>
</tr>
<tr><td>Problema:</td><td><textarea name="problema"><?=$pendencia["pendencia"]["problema"] ?></textarea></td></tr>
<tr>
	<td>Designar para:</td>
	<td>
	
	<? foreach($pendencia["pendencia"]["usuarios"] as $usu){
			$checked = '';
		    for($i =0; $i < count($pendencia["pendencia"]["usuarios_designados"]); $i ++) {
		    	if($usu["id"] == $pendencia["pendencia"]["usuarios_designados"][$i]["id"]){
					$checked = 'checked="checked"';
					$i = count($pendencia["pendencia"]["usuarios_designados"]);
		    	} 
		    }
	?>
			<label><input type="checkbox" name="designar_para[]" value="<?=$usu["id"] ?>" <?=$checked ?> /> <?=$usu["nome"]. " " . $usu["sobrenome"] ?></label><br />
	<?
					
	}
	?>
	</td>	
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="alterar()">Salvar</div></td></tr>
</table>	
</div>
</form>
<?
	}
}
?>
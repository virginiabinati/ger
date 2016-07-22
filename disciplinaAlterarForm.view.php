<?
	$disciplina = $params["disciplina"];
?>
<script type="text/javascript">
function alterar(){
	document.forms.altDisciplina.submit();
}
</script>
<form name="altDisciplina" id="altDisciplina" method="POST" action="?class=Disciplinas&method=onAlterarDisciplina">
<input type="hidden" name="id" value="<?=$disciplina["id"] ?>">
<div class="geral_form" style="width:35%">
<table class="tabela_form">
<caption>Alterar disciplina</caption>
<tr><td>Código :</td><td><input type="text" name="codigo" id="codigo" value="<?=$disciplina["codigo"] ?>" /></td></tr>
<tr><td>Disciplina:</td><td><input type="text" name="disciplina" id="disciplina" value="<?=$disciplina["disciplina"] ?>" /></td></tr>
<tr>
	<td>Exporádica:</td>
	<td><input type="checkbox" name="exporadica" value="1" <? if($disciplina['exporadica'] == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="alterar()">Salvar</div></td></tr>
</table>	
</div>
</form>
<script type="text/javascript">			
validacao({
	formId: 'addDisciplina',
	corCampo: ['#FFFFFF', '#FFFFFF'],
	corErro: ['#FFFFD7', '#CF3339'],
	requerido: ['codigo=código da disciplina','disciplina=Nome da disciplina']					
});
</script>
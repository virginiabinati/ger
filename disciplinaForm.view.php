<script type="text/javascript">
function salvar(){
	document.forms.addDisciplina.submit();
}
</script>
<form name="addDisciplina" id="addDisciplina" method="POST" action="?class=Disciplinas&method=onAddDisciplina">
<input type="hidden" name="popup" value="<?=$params ?>">
<div class="geral_form" style="width:350px">
<table class="tabela_form">
<caption>Cadastrar disciplina</caption>
<tr><td>Código:</td><td><input type="text" name="codigo" id="codigo" /></td></tr>
<tr><td>Disciplina:</td><td><input type="text" name="disciplina" id="disciplina" /></td></tr>
<tr>
	<td>Exporádica:</td>
	<td><input type="checkbox" name="exporadica" value="1" <? if($params == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
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
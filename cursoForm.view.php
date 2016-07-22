<script type="text/javascript">
function salvar(){
	document.forms.addCurso.submit();
}
</script>
<form name="addCurso" method="POST" action="?class=Cursos&method=onAddCurso">
<input type="hidden" name="popup" value="<?=$params ?>">
<div class="geral_form" style="width:350px">
<table class="tabela_form">
<caption>Cadastrar curso</caption>
<tr><td>Código:</td><td><input type="text" name="codigo" size="14" /></td></tr>
<tr><td>Curso:</td><td><input type="text" name="curso" size="40" /></td></tr>
<tr>
	<td>Exporádico:</td>
	<td><input type="checkbox" name="exporadico" value="1" <? if($params == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
</table>
</div>	
</form>
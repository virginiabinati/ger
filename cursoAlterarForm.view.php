<script type="text/javascript">
function alterar(){
	document.forms.altCurso.submit();
}
</script>
<?
	$curso = $params[0];
?>
<form name="altCurso" id="altCurso" method="POST" action="?class=Cursos&method=onAlterarCurso">
<input type="hidden" name="id" id="id" value="<?=$curso['id']?>">
<div class="geral_form" style="width:35%">
<table class="tabela_form">
<caption>Alterar curso</caption>
<tr><td>Código:</td><td><input type="text" name="codigo" id="codigo" value="<?=$curso['codigo'] ?>" size="14" /></td></tr>
<tr><td>Curso:</td><td><input type="text" name="curso" id="curso" value="<?=$curso['curso'] ?>" size="40" /></td></tr>
<tr>
	<td>Exporádico:</td>
	<td><input type="checkbox" name="exporadico" value="1" <? if($curso['exporadico'] == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="alterar()">Salvar</div></td></tr>
</table>	
</div>
</form>
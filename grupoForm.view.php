<script type="text/javascript">
function salvar(){
	document.forms.addGrupo.submit();
}
</script>
<div class="geral_form" style="width:40%">
<form id="addGrupo" enctype="multipart/form-data" name="addGrupo" method="post" action="?class=Grupos&method=onAddGrupo">
<table class="tabela_form">
<caption>Cadastro de Grupo</caption>
	<tr>
		<td>Nome</td>
		<td><input name="nome" id="nome" type="text" size="40" /></td>
		<td class="salvar" style="padding-bottom: 10px"><div class="botao" onclick="salvar()">Salvar</div></td>
	</tr>
  </table>
  </form>
</div>
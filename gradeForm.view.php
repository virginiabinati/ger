<script type="text/javascript">
function salvar(){
	document.forms.addGrade.submit();
}
</script>
<div class="geral_form" style="width:40%">
<form id="addGrade" enctype="multipart/form-data" name="addGrade" method="post" action="?class=Grade&method=onAddGrade">
<table class="tabela_form">
<caption>Cadastro de Grade</caption>
	<tr>
		<td>Semestre</td>
		<td>
			<select name="semestre">
				<option value="1">1</option>
				<option value="2">2</option>
			</select>
		</td>
		<td>Ano</td>
		<td>
			<select name="ano">
			<?
				for($x=2009; $x<2051; $x++)
				{
					echo "<option value='{$x}'>{$x}</option>";
				}
			?>
			</select>
		</td>
		<td class="salvar" style="padding-bottom:10px; padding-left: 10px"><div class="botao" onclick="salvar()">Salvar</div></td>
	</tr>
  </table>
  </form>
</div>
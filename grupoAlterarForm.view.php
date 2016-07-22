<?
$grupo = $params[grupo];
$classes = $params[classes];
$permissoes = $params[permissoes];
?>
<script type="text/javascript">
function alterar(){
	document.forms.altGrupo.submit();
}
</script>
<div class="geral_form" style="width:40%">
<form id="altGrupo" name="altGrupo" method="post" action="?class=Grupos&method=onAlterarGrupo">
<input type="hidden" id="id" name="id" value="<?=$grupo[id] ?>">
<table class="tabela_form">
<caption>Alterar Grupo</caption>
	<tr>
		<th>Nome</th>
		<td><input disabled="disabled" name="nome" value="<?=$grupo[nome] ?>" id="nome" type="text" size="40" /></td>
	</tr>
	<tr>
	<th>Permissões</th> 
	<td>
	<?
		foreach ($classes as $classe)
		{
			$checado = '';
			foreach($permissoes as $permissao)
			{
				if ($permissao[classe] == $classe[nome])
				{
					$checado = 'checked="checked"';
					break;
				}
			}
			if (($classe[nome] == 'Main') || ($classe[nome] == 'Log'))
			$checado = 'checked="checked" readonly="readonly"';
			
			echo '<input type="checkbox" '.$checado.' name="classe[]" id="classe'.$classe[id].'" value="'.$classe[nome].'" /> ';
			echo $classe[nome].'<br><br>';
		}
	?>
	</td>
	</tr>
	<tr>
		<td class="salvar">
			<div class="botao" onclick="alterar()">Salvar</div>
		</td>
		<td class="salvar">
			<div class="botao" onclick="var resposta=confirm('Tem certeza que deseja apagar o registro?');if (resposta==true) {location.href='?class=Grupos&method=onDeletarGrupo&id=<?=$grupo[id]?>';} ">Apagar</div>
		</td>
     </tr>   
  </table>
  </form>
</div>
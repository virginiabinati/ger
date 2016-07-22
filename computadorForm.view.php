<script type="text/javascript">
function salvar(){
	document.forms.addComputador.submit();
}
</script>
<div class="geral_form" style="width:40%">
<form id="addComputador" enctype="multipart/form-data" name="addComputador" method="post" action="?class=Computadores&method=onAddComputador">
<table class="tabela_form">
<caption>Cadastro de Computador</caption>
	<tr>
		<td>Sala:</td>
		<td>
		<?
			$htmlOption = '<select name="idsala" id="idsala" style="width: 90%;">';
			foreach ($params as $sala)
			{
				$htmlOption.='<option value="'.$sala['id'].'" >'.$sala['numero']. ' ' .$sala['bloco'].'</option>';
			}
			$htmlOption.='</select>';
			echo $htmlOption;
		?>	
		</td>
		<td>Nome</td>
		<td>
			<input type="text" name="nome" />
		</td>
		<td class="salvar" style="padding-bottom: 10px; padding-left: 10px"><div class="botao" onclick="salvar()">Salvar</div></td>
	</tr>
  </table>
  </form>
</div>
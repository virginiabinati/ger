<script type="text/javascript">
function salvar(){
	document.forms.addEquipamento.submit();
}
</script>
<form name="addEquipamento" method="POST" action="?class=Equipamentos&method=onAddEquipamento">
<input type="hidden" name="idsala" value="<?=$params["idsala"] ?>">
<div class="geral_form" style="width:70%">
<table class="tabela_form">
<caption>Equipamento de rede</caption>
<tr>
	<td>Tipo:</td>
	<td>
		<select name="tipo">
			<option value="hub" <? if($params["equipamento"][0]["tipo"] == "hub") echo "selected" ?>>Hub</option>
			<option value="roteador" <? if($params["equipamento"][0]["tipo"] == "roteador") echo "selected" ?>>Roteador</option>
			<option value="switch" <? if($params["equipamento"][0]["tipo"] == "switch") echo "selected" ?>>Switch</option>
		</select>
	</td>
</tr>
<tr>
	<td>Marca:</td>
	<td>
		<input type="text" name="marca" value="<?=$params["equipamento"][0]["marca"] ?>" size="50">
	</td>	
</tr>
<tr>
	<td>Código:</td>
	<td>
		<input type="text" name="codigo" value="<?=$params["equipamento"][0]["codigo"] ?>" size="50">
	</td>	
</tr>
<tr>
	<td>Modelo:</td>
	<td>
		<input type="text" name="modelo" value="<?=$params["equipamento"][0]["modelo"] ?>" size="50">
	</td>	
</tr>
<tr>
	<td>Velocidade:</td>
	<td>
		<select name="velocidade">
			<option value="10" <? if($params["equipamento"][0]["velocidade"] == "10") echo "selected" ?>>10</option>
			<option value="11" <? if($params["equipamento"][0]["velocidade"] == "11") echo "selected" ?>>11</option>
			<option value="54" <? if($params["equipamento"][0]["velocidade"] == "54") echo "selected" ?>>54</option>
			<option value="10/100" <? if($params["equipamento"][0]["velocidade"] == "10/100") echo "selected" ?>>10/100</option>
			<option value="100" <? if($params["equipamento"][0]["velocidade"] == "100") echo "selected" ?>>100</option>
			<option value="108" <? if($params["equipamento"][0]["velocidade"] == "108") echo "selected" ?>>108</option>
			<option value="100/1000" <? if($params["equipamento"][0]["velocidade"] == "100/1000") echo "selected" ?>>100/1000</option>
			<option value="1000" <? if($params["equipamento"][0]["velocidade"] == "1000") echo "selected" ?>>1000</option>
		</select>
	</td>
</tr>
<tr>
	<td>Portas:</td>
	<td>
		<input type="text" name="portas" value="<?=$params["equipamento"][0]["portas"] ?>" size="50">
	</td>	
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
</table>
</div>	
</form>
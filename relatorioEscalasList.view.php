<div class="geral_form">
<table class="tabela_comum" style="width:60%">
<caption>Relatório de Facilitadores</caption>
<thead>
	<tr>
		<th class="center">Usuário</th>
		<th class="center">Facilitador no mês</th>
	</tr>
</thead>
<tbody>
	<?	 $usuario = "";
		 foreach($params as $escala){
		 	if($usuario != $escala["nome"] && $usuario != "") {
		 		?>
		 			</td></tr>
		 		<?
		 	}
		 	if($usuario != $escala["nome"]){ 
	?>
		<tr>
			<td><?=$escala["nome"]." ".$escala["sobrenome"] ?></td>
			<td><?=$escala["mes_ano"] ?>
	<? 
			$usuario = $escala["nome"];
			} else { 
				?>
					e <?=$escala["mes_ano"]?> 
				<?
			}	
		} 
	?>
</tbody>
</table>
</div>
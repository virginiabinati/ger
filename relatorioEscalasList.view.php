<div class="geral_form">
<table class="tabela_comum" style="width:60%">
<caption>Relat�rio de Facilitadores</caption>
<thead>
	<tr>
		<th class="center">Usu�rio</th>
		<th class="center">Facilitador no m�s</th>
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
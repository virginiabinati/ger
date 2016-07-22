<?
	$aniversariantes = $params["aniversariantes"];
	$pendencias_usu = $params["pendencias"]["usuario"];
	$pendencias_recen = $params["pendencias"]["recentes"];
	$aulas = $params["gradeAgora"];
?>
<div class="geral_form">
	<div id="content_esq">
		<div id="grade_agora">
			<table class="tabela_comum" style="width:100%">
			<caption>Grade de aulas agora</caption>
			<? 
				if($aulas) {
			?>
				<tr>
					<th class="center">Horário</th>
					<th class="center">Sala</th>
					<th class="center" colspan="2">Professor</th>
				</tr>
			<?
					foreach($aulas as $aula){
					?>
						<tr <? if($aula["exporadica"] == 1) echo "style='background-color: #ffdd71'"; ?>>
							<td align="center"><?=$aula["inicio"] ?> às <?=$aula["fim"] ?></td>
							<td align="center"><?=$aula["numero"].$aula["bloco"] ?></td>
							<td align="left" class="nome"><?=$aula["nome"]. " " .$aula["sobrenome"] ?></td>
							<td align="center"><? if($aula["projetor"] == 1) { ?><img style='float:right; margin:6px' src='images/projetor.png' border='0' /><? } ?></td>
						</tr>
					<?	
					} 
				} else echo "<tr><th class='center'>Sem aulas programadas</th></tr>";				
			?>
			</table>
		</div>
		<div id="feliz_aniversario">
			<table class="tabela_comum" style="width:100%">
			<caption>Aniversariantes do mês</caption>
			<? 
				if($aniversariantes) {
			?>
				<tr style="border-top: 1px solid #84a0c4;">
					<td><img src="images/baloes.png" /></td>
					<td>
						<table>
						<? foreach($aniversariantes as $niver){ ?>
							<tr>
								<td><?=$niver["nome"] . " " . $niver["sobrenome"] ?></td>
								<td><?=$niver["aniversario"] ?></td>
							</tr>
						<? } ?>
						</table>
					</td>
				</tr>
				<?					 
				} else echo "<tr><th class='center'>Nenhum aniversariante no mês</th></tr>";				
			?>
			</table>
		</div>
	</div>
	<div id="content_dir">
		<div id="pendencias">
				<table class="tabela_comum">
				<caption>Suas pendencias</caption>
				<? if($pendencias_usu) { 
						foreach($pendencias_usu as $usu){ 
							if($aux_1 != $usu["tipo"]){ ?>
								<tr>
									<th class="center" colspan="2"><?=$usu["tipo"] ?></th>
								</tr>
							<? 
								$aux_1 = $usu["tipo"]; 
							} 
							?>
							<tr>
								<td><?=$usu["computador"] ?></td>
								<td><?=$usu["problema"] ?></td>
							</tr>
				<? 		} 
				} else echo "<tr><th class='center'>Nenhuma pendencia designada a você</th></tr>"; ?>
				</table>
			  
				<table class="tabela_comum">
				<caption>Últimas pendencias</caption>
				<? if($pendencias_recen) { 
						foreach($pendencias_recen as $recen){ 
							if($aux_2 != $recen["tipo"]){ ?>
								<tr>
									<th class="center" colspan="2"><?=$recen["tipo"] ?></th>
								</tr>
							<? 
								$aux_2 = $recen["tipo"]; 
							} 
							?>
							<tr>
								<td><?=$recen["computador"] ?></td>
								<td><?=$recen["problema"] ?></td>
							</tr>
				<? 		} 
				} else echo "<tr><th class='center'>Nenhuma pendencia recente</th></tr>"; ?>
				</table>
		</div>
	</div>
	<div style="clear:both"></div>
</div>
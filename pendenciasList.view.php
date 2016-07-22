<script type="text/javascript">
function pesquisar(){
	document.forms.frmPesquisar.submit();
}
function recado(idpendencia)
{
	if($("#addrecado").val()==0)
	{
		$("#recado_" + idpendencia).show();
		$("#addrecado").val("1");
	}
	else
	{
		$("#recado_" + idpendencia).hide();
		$("#addrecado").val("0");
	}
}
function ver_recado(id){
	$('#small_'+id).fancyZoom();
}
</script>
<div class="geral_form">
	<form name="frmPesquisar" id="frmPesquisar" action="?class=Pendencias&method=onListarPendencias" method="post">
		<table>
			<tr>
				<td class="titulo">Pesquisar:</td>
				<td><input type="text" name="busca" /></td>
				<td><div class="botao" onclick="pesquisar()">Ok</div></td>
			</tr>
		</table>
	</form>
</div>
<div class="geral_form">
<? if($params) { ?>
<table class="tabela_comum">
<tbody>
		<? 	$aux_tipo = "";
			foreach($params as $pendencia){
		   		if($aux_tipo != $pendencia["pendencia"]["tipo"]){
		?>
				<tr>
					<th class="header_blue" colspan="3"><span style="color:#fff; text-transform: capitalize"><?=$pendencia["pendencia"]["tipo"]; ?></th>
				</tr>
				<tr>
					<th class="center" width="30%">Autor</th>
					<th class="center" width="70%" colspan="2">Descrição</th>
				</tr>
			<?	 $aux_tipo = $pendencia["pendencia"]["tipo"];
				} 
			?>
			<tr style="border:none">
				<td rowspan="1" <? if($pendencia["pendencia"]["atendido"] == 1) echo "class='pendInativa'"; ?> style="border:none">
					<img <?=(($pendencia["pendencia"]["atendido"] == 1)) ? "src='images/icon_pendencia_inativa.gif'" : "src='images/icon_pendencia.gif'"; ?> />Pendência nº <?=$pendencia["pendencia"]["id"]?><br /><br />
					<?=$pendencia["pendencia"]["nome_cadastro"]. " ".$pendencia["pendencia"]["sobrenome_cadastro"]; ?><br /><br />
				</td>
				<td <? if($pendencia["pendencia"]["atendido"] == 1) echo "class='pendInativa'"; ?> style="border:none">
					<?="<b>" .$pendencia["pendencia"]["sala_micro"] . "</b><br />" . nl2br($pendencia["pendencia"]["problema"]) ?><br /><br />
					<? if(count($pendencia["pendencia"]["usuarios_designados"]) > 0) { ?>
						<div <?=($pendencia["pendencia"]["atendido"] == 1) ? "class='pendInativa'" : "class='pendAtiva'"; ?>>
						<b>Pendência designada para</b> 
						<? foreach($pendencia["pendencia"]["usuarios_designados"] as $usu){ 
						   		echo $usu["nome"]. " ".$usu["sobrenome"] . ", "; 
						   } ?>
						   </div>
					    <? } ?>
			    </td>
			    <td <? if($pendencia["pendencia"]["atendido"] == 1) echo "class='pendInativa'"; ?> width="40" style="border:none">
			    	<? if($pendencia["pendencia"]["tipo"] != "recado" && $pendencia["pendencia"]["tipo"] != "escala")  { 
							if( $pendencia["pendencia"]["atendido"] == 0 && $pendencia["pendencia"]["qtdHoras"] > 12 ) {					
					?>
			    				<img src="images/warning.png" alt="Resolver pendência com urgência" title="Resolver pendência com urgência" />
		    		<? 		}
					   } if(count($pendencia["pendencia"]["recados"]) > 0) { ?>
						<a href="#small_box_<?=$pendencia["pendencia"]["id"]?>" id="small_<?=$pendencia["pendencia"]["id"]?>" onclick="ver_recado('<?=$pendencia["pendencia"]["id"] ?>')">
							<img src='<?=($pendencia["pendencia"]["atendido"] == 0) ? "images/recado.png" : "images/recado_inativo.png"?>' style='float:right; padding-right:15px; padding-top:5px' border="0" alt="Recados da pendência" title="Recados da pendência">
						</a>
						<div id="small_box_<?=$pendencia["pendencia"]["id"]?>" style="width:80%; display:none">
						  <table>
						  	<? foreach($pendencia["pendencia"]["recados"] as $rec){ ?>
									<tr>
						  				<td><b><?=$rec["nome"]. " ".$rec["sobrenome"] ?> escreveu:</b></td>  
										<td><?=$rec["recado"] ?></td>
									</tr>
					   		<? } ?>
						  </table>
						</div>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td <? if($pendencia["pendencia"]["atendido"] == 1) echo "class='pendInativa'"; ?>>Registrado em <?=$pendencia["pendencia"]["data_hora"]?></td>
				<td <? if($pendencia["pendencia"]["atendido"] == 1) echo "class='pendInativa'"; ?> colspan="2">
					<? if($pendencia["pendencia"]["atendido"] == 0) { ?>
					<div style='float:left; padding-right:6px; padding-top:5px'>
						<img class="link" src='images/excluir.gif' border='0' onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Pendencias&method=onDeletarPendencia&id=<?=$pendencia["pendencia"]["id"]?>'; }" />
					</div>
					<div style='float:left; padding-right:6px; padding-top:3px'>
						<img class="link" src='images/edit.gif' border='0' onclick="location.href='?class=Pendencias&method=altPendencia&id=<?=$pendencia["pendencia"]["id"]?>'" />
					</div>
					<div style='float:left'>
						<img class="link" value='0' id='addrecado' src='images/addplus.gif' border='0' onclick="recado('<?=$pendencia["pendencia"]["id"]?>')" />
					</div>
					<div style='float:right; font-weight:bold' <?=($pendencia["pendencia"]["atendido"] == 1) ? "class='pendInativa'" : "class='pendAtiva'"; ?>>Atendido: 
				  		<input type='radio' name='<?=$pendencia["pendencia"]["id"]?>_atendido' value='1' onclick="location.href='?class=Pendencias&method=onAtendido&id=<?=$pendencia["pendencia"]["id"]?>&atendido=1'" <? if($pendencia["pendencia"]["atendido"] == 1) echo "checked='checked'"; ?> /> Sim 
					    <input type='radio' name='<?=$pendencia["pendencia"]["id"]?>_atendido' value='0' onclick="location.href='?class=Pendencias&method=onAtendido&id=<?=$pendencia["pendencia"]["id"]?>&atendido=0'" <? if($pendencia["pendencia"]["atendido"] == 0) echo "checked='checked'"; ?> /> Não 
					</div>
					<div id='recado_<?=$pendencia["pendencia"]["id"]?>' style='display:none; clear:both; padding-top:5px'>
						<form name='addRecado_<?=$pendencia["pendencia"]["id"]?>' id='addRecado_<?=$pendencia["pendencia"]["id"]?>' method='POST' action='?class=Pendencias&method=onAddRecado'>
							Recado<br />
							<input type='hidden' name='id_pendencia' value='<?=$pendencia["pendencia"]["id"]?>' />
							<textarea id="recado" name="recado"></textarea>
							<br />
							<img class="link" src='images/add.gif' alt='Enviar' title='Enviar' onclick="if(confirm('Tem certeza que deseja cadastrar este recado? Somente o administrador irá poder deleta-lo')) { $('#addRecado_<?=$pendencia["pendencia"]["id"]?>').submit(); }" />
						</form>
					</div>
					<? } ?>

				</td>
			</tr>
<? } ?>	
</tbody>
<? } else { echo "<h3 align='center'>A consulta retornou vazia!</h3>"; } ?>
</table>
</div>
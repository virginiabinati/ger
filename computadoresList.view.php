<div class="geral_form">
<?
if ($params){
	foreach ($params as $computador){
		if($aux != $computador["numero"].$computador["bloco"]){
		?>
			</table><table class="tabela_comum" style="width:190px; float:left"><tr><td class="center" colspan="2"><?=$computador["numero"]." ".$computador["bloco"] ?></td></tr>
		<?
			$aux = $computador["numero"].$computador["bloco"];
		}	
?>
<tr>
<td align="center"><?=$computador["nome"] ?></td>
<td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Computadores&method=onDeletarComputador&id=<?=$computador["idcomputador"] ?>' } "></td>
</tr>
<?
	}
}
?>
</table>
<div style="clear:both"></div>
</div>
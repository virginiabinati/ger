<div class="geral_form" style="width:350px">
<table class="tabela_comum" style="width:300px">
<caption>Grupos</caption>
<thead>
	<tr>
		<th class="center">Nº.</th>
		<th class="center">Nome</th>
		<th class="center" colspan="2">Ações</th>
	</tr>
</thead>
<tbody>
<?
if ($params){
	foreach ($params as $grupo){	
?>
<tr>
<td><?=$grupo["id"] ?></td>
<td><?=$grupo["nome"] ?></td>
<td><img class="link" src="images/edit.png" onclick="location.href='?class=Grupos&method=alterarGrupo&id=<?=$grupo["id"] ?>#Grupos'" /></td>
<td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Grupos&method=onDeletarGrupo&id=<?=$grupo["id"] ?>' } "></td>
</tr>
<?
	}
}
?>
</tbody>
</table>
</div>
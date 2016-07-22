<div class="geral_form" style="width:350px">
<table class="tabela_comum" style="width:200px">
<caption>Grade</caption>
<thead>
	<tr>
		<th class="center">Id</th>
		<th class="center">Semestre</th>
		<th class="center">Ano</th>
		<th class="center"></th>
	</tr>
</thead>
<tbody>
<?
if ($params){
	foreach ($params as $grade){	
?>
<tr>
<td align="center"><?=$grade["id"] ?></td>
<td align="center"><?=$grade["semestre"] ?>º</td>
<td align="center"><?=$grade["ano"] ?></td>
<td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Grade&method=onDeletarGrade&id=<?=$grade["id"] ?>' } "></td>
</tr>
<?
	}
}
?>
</tbody>
</table>
</div>
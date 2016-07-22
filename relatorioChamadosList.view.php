<div class="geral_form">
<table class="tabela_comum" style="width:60%">
<caption>Relatório de chamados</caption>
<thead>
	<tr>
		<th class="center">Computador</th>
		<th class="center">Qtd de chamados abertos</th>
	</tr>
</thead>
<tbody>
	<? foreach($params as $chamado){ ?>
		<tr>
			<td><?=$chamado["sala_computador"] ?></td>
			<td align="center"><?=$chamado["qtd"] ?></td>
		</tr>
	<? } ?>
</tbody>
</table>
</div>
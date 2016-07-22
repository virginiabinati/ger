<script type="text/javascript">
	function pesquisar(){
		document.forms.frmPesquisar.submit();
	}
</script>
<div class="geral_form">
	<form name="frmPesquisar" id="frmPesquisar" action="?class=Disciplinas&method=onBuscarDisciplina" method="post">
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
<thead>
<tr>
	<th class="center">Código da disciplina</th>
	<th class="center">Nome da disciplina</th>
	<th class="center" colspan="2">Ações</th>
</tr>
</thead>
<tbody>
<?php
	foreach($params as $disciplina)
	{ ?>
	<tr>
	    <td><?=$disciplina['codigo'] ?></td>
	    <td class="nome">
	    	<?=str_replace("ii","II",$disciplina['disciplina']); ?>
	    	<? if($disciplina["exporadica"] == 1) echo "<span class='exporadico'><img src='images/exporadico.png' alt='Disciplina exporádica' title='Disciplina exporádica' border='0' /></span>"; ?>	
    	</td>
	    <td><img class="link" src="images/edit.png" onclick="location.href='?class=Disciplinas&method=altDisciplina&id=<?=$disciplina["id"] ?>'"></td>
	    <td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Disciplinas&method=onDeletarDisciplina&id=<?=$disciplina["id"] ?>' } "></td>
	</tr>
</tbody>
<?
	}
} else { echo "<h3 align='center'>A consulta retornou vazia!</h3>"; }
?>
</table>
</div>
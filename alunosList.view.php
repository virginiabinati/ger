<script type="text/javascript">
	function pesquisar(){
		document.forms.frmPesquisar.submit();
	}
</script>
<div class="geral_form">
	<form name="frmPesquisar" id="frmPesquisar" action="?class=Alunos&method=onBuscarAluno" method="post">
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
	<th class="center">Codigo</th>
	<th class="center">Nome</th>
	<th class="center">Sobrenome</th>
	<th class="center">Curso</th>
	<th class="center">Ativo</th>
	<th class="center" colspan="2">Ações</th>
</tr>
</thead>
<tbody>
<?php
	foreach($params as $aluno)
	{ ?>
	<tr>
		<td><?=$aluno['codigo'] ?></td>
	    <td><?=$aluno['nome'] ?></td>
	    <td><?=$aluno['sobrenome'] ?></td>
	    <td><?=$aluno['curso'] ?></td>
	    <td><?=($aluno['ativo'] == 1) ? "Sim" : "Não"; ?></td>
	    <td><img src="images/edit.png" onclick="javascript:location.href='?class=Alunos&method=altAluno&id=<?=$aluno["id"] ?>'" class="link"></td>
	    <td><img src="images/deletar.jpg" onclick="javascript:if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Alunos&method=onDeletarAluno&id=<?=$aluno["id"] ?>&codigo=<?=$aluno['codigo'] ?>' } " class="link"></td>
	</tr>
</tbody>
<?
	}
} else { echo "<h2 align='center'>A consulta retornou vazia!</h2>"; }
?>
</table>
</div>
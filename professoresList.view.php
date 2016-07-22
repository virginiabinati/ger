<script type="text/javascript">
	function pesquisar(){
		document.forms.frmPesquisar.submit();
	}
</script>
<div class="geral_form">
	<form name="frmPesquisar" id="frmPesquisar" action="?class=Professores&method=onBuscarProfessor" method="post">
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
	<th class="center">Login</th>
	<th class="center">Nome</th>
	<th class="center">Sobrenome</th>
	<th class="center">Ativo</th>
	<th class="center" colspan="2">Ações</th>
</tr>
</thead>
<tbody>
<?php
	foreach($params as $professor)
	{ ?>
	<tr>
		<td><?=$professor['login'] ?></td>
	    <td class="nome"><?=$professor['nome'] ?></td>
	    <td class="nome"><?=$professor['sobrenome'] ?></td>
	    <td>
	    	<?=($professor['ativo'] == 1) ? "Sim" : "Não"; ?>
	    	<? if($professor["convidado"] == 1) echo "<span class='exporadico'><img src='images/convidado.png' alt='Professor convidado' title='Professor convidado' border='0' /></span>"; ?>
    	</td>
	    <td><img class="link" src="images/edit.png" onclick="location.href='?class=Professores&method=altProfessor&id=<?=$professor["id"] ?>'"></td>
	    <td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Professores&method=onDeletarProfessor&id=<?=$professor["id"] ?>&login=<?=$professor['login'] ?>' } "></td>
	</tr>
</tbody>
<?
	}
} else { echo "<h1 align='center'>A consulta retornou vazia!</h1>"; }
?>
</table>
</div>
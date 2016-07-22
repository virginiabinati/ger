<script type="text/javascript">
	function pesquisar(){
		document.forms.frmPesquisar.submit();
	}
</script>
<div class="geral_form">
	<form name="frmPesquisar" id="frmPesquisar" action="?class=Cursos&method=onBuscarCurso" method="post">
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
	<th class="center">Código</th>
	<th class="center">Curso</th>
	<th class="center" colspan="2">Ações</th>
</tr>
</thead>
<tbody>
<?php
	foreach($params as $curso)
	{ ?>
	<tr>
		<td><?=$curso['codigo'] ?></td>
	    <td>
	    	<?=$curso['curso'] ?>
	    	<? if($curso["exporadico"] == 1) echo "<span class='exporadico'><img src='images/exporadico.png' alt='Curso exporádico' title='Curso exporádico' border='0' /></span>"; ?>
    	</td>
	    <td><img class="link" src="images/edit.png" onclick="location.href='?class=Cursos&method=altCurso&id=<?=$curso["id"] ?>'  "></td>
	    <td><img class="link" src="images/deletar.jpg" onclick="if(confirm('Tem certeza que deseja deletar esse registro?')) { location.href='?class=Cursos&method=onDeletarCurso&id=<?=$curso["id"] ?>' } "></td>
	</tr>
</tbody>
<?
	}
} else { echo "<h1 align='center'>A consulta retornou vazia!</h1>"; }
?>
</table>
</div>
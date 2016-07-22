<script type="text/javascript">
jQuery(function($){
   $("#horario_inicio").mask("99:99");
   $("#horario_fim").mask("99:99");
   $("#tr_data").hide();
});
$(document).ready(function() {
	$('#data_aula').focus(function(){
	     $(this).calendario({  
			target:'#data_aula',  
			top:0,  
			left:80  
	     });  
	});
});
function tipo_aula(exporadica){
	if(exporadica == 1){
		$("#tr_dia_semana").hide();
		$("#tr_data").show();
	} else {
		$("#tr_dia_semana").show();
		$("#data_aula").val('');
		$("#tr_data").hide();
	}
}
function valida(){
	if($("#exporadica").val() == 1 && $("#data_aula").val() == "")
		alert("O campo data é obrigatório quando a aula é exporádica!");
	else {
		var idgrade = $("#idgrade").val();
		var idcurso = $("#idcurso").val();
		var iddisciplina = $("#iddisciplina").val();
		var idprofessor = $("#idprofessor").val();
		var idsala = $("#idsala").val();
		var exporadica = $("#exporadica").val();
		var data_aula = $("#data_aula").val();
		var dia_semana = $("#dia_semana").val();
		var horario_inicio = $("#horario_inicio").val();		
		var horario_fim = $("#horario_fim").val();
		var projetor = $("#projetor").val();
		var detalhes = $("#detalhes").val();
		
		$.ajax({
		   type: "POST",
		   url: "controle.php?class=Aulas&method=onAulaExists",
		   data: "noincludes=1&idgrade="+idgrade+"&idcurso="+idcurso+"&iddisciplina="+iddisciplina+"&idprofessor="+idprofessor+"&idsala="+idsala+"&exporadica="+exporadica+"&data_aula="+data_aula+"&dia_semana="+dia_semana+"&horario_inicio="+horario_inicio+"&horario_fim="+horario_fim+"&projetor="+projetor+"&detalhes="+detalhes,
		   success: function(result){
		     	if(result == 1)
		     		alert("Já existe uma aula cadastrada nesse mesmo dia e horário!");
	     		else
		     		document.forms.addAula.submit();
		   }
		});	
	}
}

</script>
<form name="addAula" id="addAula" method="POST" action="?class=Aulas&method=onAddAula">
<div class="geral_form" style="width:60%">
<table class="tabela_form">
<caption>Cadastro de aula</caption>
<tr>
	<td>Grade:</td>
	<td>
		<select name="idgrade" id="idgrade">
			<?
				foreach($params["grade"] as $grade)
				{
					echo "<option value='{$grade["id"]}'>{$grade["semestre"]}º semestre de {$grade["ano"]}</option>";
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Curso:</td>
	<td>
		<select name="idcurso" id="idcurso" onchange="disciplinas_curso(this.value)">
			<?
				foreach($params["curso"] as $curso)
				{
					echo "<option value='{$curso["id"]}'>{$curso["curso"]}</option>";
				}
			?>
		</select>
		<a href="#" onClick="window.open('controle.php?class=Cursos&method=addCurso&popup=1', 'Curso', 'menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no, width=350, height=185'); ">
			<img src='images/exporadico.png' alt='Curso exporádico' title='Curso exporádico' border='0' />
		</a>
	</td>
</tr>
<tr>
	<td>Disciplina:</td>
	<td>
		<select name="iddisciplina" id="iddisciplina">
			<?
				foreach($params["disciplina"] as $disciplina)
				{
					echo "<option value='{$disciplina["id"]}'>{$disciplina["disciplina"]}</option>";
				}
			?>
		</select>
		<a href="#" onClick="window.open('controle.php?class=Disciplinas&method=addDisciplina&popup=1', 'Disciplina', 'menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no, width=350, height=190'); ">
			<img src='images/exporadico.png' alt='Disciplina exporádica' title='Disciplina exporádica' border='0' />
		</a>
	</td>	
</tr>
<tr>
	<td>Professor:</td>
	<td>
		<select name="idprofessor" id="idprofessor">
			<?
				foreach($params["professor"] as $professor)
				{
					echo "<option value='{$professor["id"]}'>{$professor["nome"]} {$professor["sobrenome"]}</option>";
				}
			?>
		</select>
		<a href="#" onClick="window.open('controle.php?class=Professores&method=addProfessor&popup=1', 'Professor', 'menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no, width=350, height=350'); ">
			<img src='images/convidado.png' alt='Professor convidado' title='Professor convidado' border='0' />
		</a>
	</td>
</tr>
<tr>
	<td>Sala:</td>
	<td>
		<select name="idsala" id="idsala">
			<?
				foreach($params["sala"] as $sala)
				{
					echo "<option value='{$sala["id"]}'>{$sala["numero"]} {$sala["bloco"]}</option>";
				}
			?>
		</select>
	</td>	
</tr>
<tr>
	<td>Exporádica:</td><td>
		<select id="exporadica" name="exporadica" onchange="tipo_aula(this.value)">
			<option value="0" selected="selected">Não</option>
			<option value="1">Sim</option>
		</select>
	</td>
</tr>
<tr id="tr_data">
	<td>Data:</td>
	<td><input type="text" name="data_aula" id="data_aula" size="8" <?=date("d/m/Y") ?> /></td>
</tr>
<tr id="tr_dia_semana">
	<td>Dia da semana:</td>
	<td>
		<select name="dia_semana" id="dia_semana">
			<option value="Mon" <? if(date('D') == "Mon") echo "selected" ?>>Segunda-feira</option>
			<option value="Tue" <? if(date('D') == "Tue") echo "selected" ?>>Terça-feira</option>
			<option value="Wed" <? if(date('D') == "Wed") echo "selected" ?>>Quarta-feira</option>
			<option value="Thu" <? if(date('D') == "Thu") echo "selected" ?>>Quinta-feira</option>
			<option value="Fri" <? if(date('D') == "Fri") echo "selected" ?>>Sexta-feira</option>
			<option value="Sat" <? if(date('D') == "Sat") echo "selected" ?>>Sábado</option>
		</select>
	</td>
</tr>
<tr>
	<td>Horario inicio:</td>
	<td><input type="text" name="horario_inicio" id="horario_inicio" /></td>
</tr>
<tr>
	<td>Horario término:</td>
	<td><input type="text" name="horario_fim" id="horario_fim" /></td>
</tr>
<tr>
	<td>Projetor:</td>
	<td>
		<select id="projetor" name="projetor">
			<option value="0" selected="selected">Não</option>
			<option value="1">Sim</option>
		</select>
	</td>
</tr>
<tr>
	<td>Detalhes:</td>
	<td><textarea id="detalhes" name="detalhes" rows="5" cols="30"></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<div class="botao" onclick="valida()">Salvar</div>
	</td>
</tr>
</table>	
</div>
</form>
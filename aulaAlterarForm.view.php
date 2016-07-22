<script type="text/javascript">
jQuery(function($){
   $("#data_aula").mask("99/99/9999");
   $("#horario_inicio").mask("99:99");
   $("#horario_fim").mask("99:99");
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
function alterar(){
	$("#altAula").attr("action","?class=Aulas&method=onAlterarAula");
	document.forms.altAula.submit();
}
function salvarComo(){
	
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
	     		alert("Já existe uma aula cadastrada nesse mesmo dia e horário!")
	     	else {
	     		$("#altAula").attr("action","?class=Aulas&method=onAddAula");
				document.forms.altAula.submit();
	     	}
	   }
	});	
}
</script>
<? foreach($params["aula"] as $aula) { ?>
<script type="text/javascript">
	jQuery(function($){
	<? if($aula["exporadica"] == 1) : ?>
		$("#tr_dia_semana").hide();
		$("#tr_data").show();
	<? else : ?>
		$("#tr_dia_semana").show();
		$("#data_aula").val('');
		$("#tr_data").hide();
	<? endif; ?>
	});
</script>
<form name="altAula" id="altAula" method="POST" action="">
<input type="hidden" name="id" value="<?=$aula["id_aula"] ?>">
<div class="geral_form" style="width:60%">
<table class="tabela_form">
<caption>Alterar aula</caption>
<tr>
	<td>Grade:</td>
	<td>
		<select name="idgrade" id="idgrade">
			<?
				foreach($params["grade"] as $grade)
				{
					echo "<option value='{$grade["id"]}' ";
					if($grade["id"] == $aula["idgrade"]) echo "selected";
					echo ">{$grade["semestre"]}º semestre de {$grade["ano"]}</option>";
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
					echo "<option value='{$curso["id"]}' ";
					if($curso["id"] == $aula["idcurso"]) echo "selected";
					echo ">{$curso["curso"]}</option>";
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Disciplina:</td>
	<td>
		<select name="iddisciplina" id="iddisciplina">
			<?
				foreach($params["disciplina"] as $disciplina)
				{
					echo "<option value='{$disciplina["id"]}' ";
					if($disciplina["id"] == $aula["iddisciplina"]) echo "selected";
					echo ">{$disciplina["disciplina"]}</option>";
				}
			?>
		</select>
	</td>	
</tr>
<tr>
	<td>Professor:</td>
	<td>
		<select name="idprofessor" id="idprofessor">
			<?
				foreach($params["professor"] as $professor)
				{
					echo "<option value='{$professor["id"]}' ";
					if($professor["id"] == $aula["idprofessor"]) echo "selected";
					echo ">{$professor["nome"]} {$professor["sobrenome"]}</option>";
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Sala:</td>
	<td>
		<select name="idsala" id="idsala">
			<?
				foreach($params["sala"] as $sala)
				{
					echo "<option value='{$sala["id"]}' ";
					if($sala["id"] == $aula["idsala"]) echo "selected";
					echo ">{$sala["numero"]} {$sala["bloco"]}</option>";
				}
			?>
		</select>
	</td>	
</tr>
<tr>
	<td>Exporádica:</td>
	<td>
		<select id="exporadica" id="exporadica" name="exporadica" onchange="tipo_aula(this.value)">
			<option value="0" <? if($aula["exporadica"] == 0) echo "selected"; ?>>Não</option>
			<option value="1" <? if($aula["exporadica"] == 1) echo "selected"; ?>>Sim</option>
		</select>
	</td>
</tr>
<tr id="tr_data">
	<td>Data:</td>
	<td><input type="text" name="data_aula" id="data_aula" value="<?=$aula["dia"] ?>" /></td>
</tr>
<tr id="tr_dia_semana">
	<td>Dia da semana:</td>
	<td>
		<select name="dia_semana" id="dia_semana">
			<option value="Mon" <? if($aula["dia_semana"] == "Mon") echo "selected" ?>>Segunda-feira</option>
			<option value="Tue" <? if($aula["dia_semana"] == "Tue") echo "selected" ?>>Terça-feira</option>
			<option value="Wed" <? if($aula["dia_semana"] == "Wed") echo "selected" ?>>Quarta-feira</option>
			<option value="Thu" <? if($aula["dia_semana"] == "Thu") echo "selected" ?>>Quinta-feira</option>
			<option value="Fri" <? if($aula["dia_semana"] == "Fri") echo "selected" ?>>Sexta-feira</option>
			<option value="Sat" <? if($aula["dia_semana"] == "Sat") echo "selected" ?>>Sábado</option>
		</select>
	</td>
</tr>
<tr><td>Horario inicio:</td><td><input type="text" name="horario_inicio" id="horario_inicio" value="<?=$aula["inicio"] ?>" /></td></tr>
<tr><td>Horario término:</td><td><input type="text" name="horario_fim" id="horario_fim" value="<?=$aula["fim"] ?>" /></td></tr>
<tr><td>Projetor:</td><td>
		<select id="projetor" name="projetor">
			<option value="0" <? if($aula["projetor"] == 0) echo "selected"; ?>>Não</option>
			<option value="1" <? if($aula["projetor"] == 1) echo "selected"; ?>>Sim</option>
		</select>
</td></tr>
<tr>
	<td>Detalhes:</td>
	<td>
		<textarea id="detalhes" name="detalhes" rows="5" cols="30"><?=$aula["detalhes"] ?></textarea>
	</td>
</tr>

<tr>
	<td align="center">
		<div class="botao" onclick="alterar()">Salvar</div>
	</td>
	<td>
		<div class="botao" onclick="salvarComo()" style="width:100px">Salvar como</div>
	</td>
</tr>
</table>
</div>	
</form>
<? } ?>
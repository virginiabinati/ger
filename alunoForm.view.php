<script type="text/javascript">
function salvar(){
	document.forms.addAluno.submit();
}

function checarSenha(senhaCheck){
	var senha = document.getElementById('senha');
	if ( senhaCheck.name == 'senhacheck' ){
		if ( senha.value != senhaCheck.value){
			senha.value = '';
			senhaCheck.value = '';
			alert('Senhas não conferem,\nPor Favor, digite novamente!');
   		}
   }else{
		if (senha.value == '') senha.focus();
   }
}

jQuery(function($){
   $("#data_nascimento").mask("99/99/9999");
   $("#telefone").mask("(99)9999-9999");
});


validacao({
	formId: 'addAluno',
	corCampo: ['#000', '#000'],
	corErro: ['#FFFFD7', '#CF3339'],
	requerido: ['codigo=Código do Aluno','nome=Nome do Aluno', 'senha=Senha', 'sobrenome=Sobrenome do Aluno', 'sexo=Sexo', 'data_nascimento=Data de Nascimento','curso=Curso','etapa=Etapa']					
});
</script>
<form name="addAluno" id="addAluno" method="POST" action="?class=Alunos&method=onAddAluno">
<div class="geral_form" style="width:60%">
<table class="tabela_form">
<caption>Cadastrar Aluno</caption>
<tr><td>Código:</td><td><input type="text" id="codigo" name="codigo" /></td></tr>
<tr><td>Senha:</td><td><input type="password" id="senha" name="senha" /></td></tr>
<tr><td>Confirmar:</td><td><input type="password" id="senhacheck" name="senhacheck" onblur="checarSenha(this);"/></td></tr>
<tr><td>Nome:</td><td><input type="text" id="nome" name="nome" onfocus="checarSenha(this);"/></td></tr>
<tr><td>Sobrenome:</td><td><input type="text" id="sobrenome" name="sobrenome" /></td></tr>
<tr><td>E-mail:</td><td><input type="text" id="email" name="email" /></td></tr>
<tr><td>Sexo:</td><td>
		<select id="sexo" name="sexo">
			<option value="">Selecione</option>
			<option value="m">Masculino</option>
			<option value="f">Feminino</option>
		</select>
</td></tr>
<tr><td>Data nascimento:</td><td><input type="text" name="data_nascimento" id="data_nascimento" /></td></tr>
<tr><td>Telefone:</td><td><input type="text" name="telefone" id="telefone" /></td></tr>
<tr>
	<td>Curso:</td>
	<td>
		<select name="curso" id="curso">
		<option value="">Selecione...</option>
		<?
			foreach($params as $curso)
			{		
				echo "<option value='{$curso["id"]}'>{$curso["curso"]}</option>";	
			}
		?>
		</select>
	</td>
</tr>
<tr><td>Etapa:</td><td><input type="text" id="etapa" name="etapa" size="4" /></td></tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
</table>	
</div>
</form>

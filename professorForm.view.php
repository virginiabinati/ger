<script>
function salvar(){
	document.forms.addProfessor.submit();
}
jQuery(function($){
   $("#data_nascimento").mask("99/99/9999");
   $("#telefone").mask("(99)9999-9999");
});
</script>
<form name="addProfessor" id="addProfessor" method="POST" action="?class=Professores&method=onAddProfessor">
<input type="hidden" name="popup" value="<?=$params ?>">
<div class="geral_form" style="width:350px">
<table class="tabela_form">
<caption>Cadastrar professor</caption>
<tr><td>Login:</td><td><input type="text" id="login" name="login" /></td></tr>
<tr><td>Senha:</td><td><input type="password" id="senha" name="senha" /></td></tr>
<tr><td>Nome:</td><td><input type="text" id="nome" name="nome" /></td></tr>
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
<tr><td>Área de atuação:</td><td><input type="text" id="area_atuacao" name="area_atuacao" /></td></tr>
<tr>
	<td>Convidado:</td>
	<td><input type="checkbox" name="convidado" value="1" <? if($params == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="salvar()">Salvar</div></td></tr>
</table>
</div>
</form>	
<script type="text/javascript">
validacao({
	formId: 'addProfessor',
	corCampo: ['#FFFFFF', '#FFFFFFF'],
	corErro: ['#FFFFD7', '#CF3339'],
	requerido: ['login=Login do Professor','nome=Nome do Professor', 'sobrenome=Sobrenome do Professor', 'senha=Senha', 'sexo=Sexo', 'data_nascimento=Data de Nascimento','telefone=Telefone','area_atuacao=Área de Atuação']					
});
</script>

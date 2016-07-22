<script type="text/javascript">
function alterar(){
	document.forms.altAluno.submit();
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
	formId: 'altAluno',
	corCampo: ['#FFFFFF', '#FFFFFF'],
	corErro: ['#FFFFD7', '#CF3339'],
	requerido: ['nome=Nome do Aluno', 'senha=Senha', 'sobrenome=Sobrenome do Aluno', 'sexo=Sexo', 'data_nascimento=Data de Nascimento','curso=Curso','etapa=Etapa']					
});
</script>
<?
if($params) 
{ 
	foreach($params["alunos"] as $aluno)
	{	
		$aux = explode("-",$aluno["data_nascimento"]);
		$data = $aux[2]."/".$aux[1]."/".$aux[0];
?>
<form name="altAluno" id="altAluno" method="POST" action="?class=Alunos&method=onAlterarAluno">
<input type="hidden" name="id" value="<?=$aluno["id"] ?>" />
<div class="geral_form" style="width:60%">
<table class="tabela_form">
<caption>Alterar Aluno</caption>
<tr><td>Código:</td><td><?=$aluno["codigo"] ?><input type="hidden" name="codigo" value="<?=$aluno["codigo"] ?>" /></td></tr>
<tr><td>Senha:</td><td><input id="senha" type="password" name="senha" value="<?=$aluno["senha"] ?>" /></td></tr>
<tr><td>Confirmar:</td><td><input id="senhacheck" type="password" name="senhacheck" value="<?=$aluno["senha"] ?>" onblur="checarSenha(this);"/></td></tr>
<tr><td>Nome:</td><td><input id="nome" type="text" name="nome" value="<?=$aluno["nome"]?>" onfocus="checarSenha(this)"/></td></tr>
<tr><td>Sobrenome:</td><td><input id="sobrenome" type="text" name="sobrenome" value="<?=$aluno["sobrenome"] ?>" /></td></tr>
<tr><td>E-mail:</td><td><input id="email" type="text" name="email" value="<?=$aluno["email"] ?>"/></td></tr>
<tr><td>Sexo:</td><td>
		<select id="sexo" name="sexo">
			<option value="m" <?= $aluno['sexo']=='m'?" selected=\"selected\"":"" ?>>Masculino</option>
			<option value="f" <?= $aluno['sexo']=='f'?" selected=\"selected\"":"" ?>>Feminino</option>

		</select>
</td></tr>
<tr><td>Data nascimento:</td><td><input id="data_nascimento" type="text" name="data_nascimento" id="data_nascimento" value="<?=$data ?>" /></td></tr>
<tr><td>Telefone:</td><td><input id="telefone" type="text" name="telefone" id="telefone" value="<?=$aluno["telefone"] ?>" /></td></tr>
<tr>
	<td>Curso:</td>
	<td>
		<select id="curso" name="curso">
		<option value="<?=$aluno["idcurso"] ?>"><?=$aluno["curso"] ?></option>
		<?
			foreach($params["cursos"] as $curso)
			{		
				echo "<option value='{$curso["id"]}'>{$curso["curso"]}</option>";	
			}
		?>
		</select>
	</td>
</tr>
<tr><td>Etapa:</td><td><input type="text" id="etapa" name="etapa" size="4" value="<?=$aluno["etapa_corrente"] ?>" /></td></tr>
<tr>
	<td>Ativo:</td>
	<td>
    	<select name="ativo">
    		<option value='0' <? if($aluno["ativo"]==0) echo "selected"; ?>>Não</option>
    		<option value='1' <? if($aluno["ativo"]==1) echo "selected"; ?>>Sim</option>
    	</select>
    </td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="alterar()">Salvar</div></td></tr>
</table>
</div>	
</form>
<?
	}
} 
?>

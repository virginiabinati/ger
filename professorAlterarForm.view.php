<script>
function alterar(){
	document.forms.altProfessor.submit();
}
jQuery(function($){
   $("#data_nascimento").mask("99/99/9999");
   $("#telefone").mask("(99)9999-9999");
});
</script>
<? 
if($params) 
{ 
	foreach($params as $professor)
	{	
		$aux = explode("-",$professor["data_nascimento"]);
		$data = $aux[2]."/".$aux[1]."/".$aux[0];
?>
<form name="altProfessor" id="altProfessor" method="POST" action="?class=Professores&method=onAlterarProfessor">
<input type="hidden" name="id" value="<?=$professor["id"] ?>" />
<div class="geral_form" style="width:35%">
<table class="tabela_form">
<caption>Alterar professor</caption>
<tr><td>Login:</td><td><?=$professor["login"] ?></td></tr>
<tr><td>Senha:</td><td><input type="password" id="senha" name="senha" value="<?=$professor["senha"] ?>" /></td></tr>
<tr><td>Nome:</td><td><input type="text" id="nome" name="nome" class="nome" value="<?=$professor["nome"] ?>" /></td></tr>
<tr><td>Sobrenome:</td><td><input type="text" id="sobrenome" name="sobrenome" class="nome" value="<?=$professor["sobrenome"] ?>" /></td></tr>
<tr><td>E-mail:</td><td><input type="text" id="email" name="email" value="<?=$professor["email"] ?>" /></td></tr>
<tr><td>Sexo:</td><td>
		<select id="sexo" name="sexo">
			<option value="m" <?= $professor['sexo']=='m'?" selected=\"selected\"":"" ?>>Masculino</option>
			<option value="f" <?= $professor['sexo']=='f'?" selected=\"selected\"":"" ?>>Feminino</option>

		</select>
</td></tr>
<tr><td>Data nascimento:</td><td><input type="text" name="data_nascimento" id="data_nascimento" value="<?=$data ?>" /></td></tr>
<tr><td>Telefone:</td><td><input type="text" name="telefone" id="telefone" value="<?=$professor["telefone"] ?>" /></td></tr>
<tr><td>Área de atuação:</td><td><input type="text" id="area_atuacao" name="area_atuacao" value="<?=$professor["area_atuacao"] ?>" /></td></tr>
<tr>
	<td>Ativo:</td>
	<td>
    	<select name="ativo">
    		<option value='0' <? if($professor["ativo"]==0) echo "selected"; ?>>Não</option>
    		<option value='1' <? if($professor["ativo"]==1) echo "selected"; ?>>Sim</option>
    	</select>
    </td>
</tr>
<tr>
	<td>Convidado:</td>
	<td><input type="checkbox" name="convidado" value="1" <? if($professor['convidado'] == 1) echo "checked"; ?> /></td>
</tr>
<tr><td colspan="2" class="salvar"><div class="botao" onclick="alterar()">Salvar</div></td></tr>
</table>	
</div>
</form>
<?
	}
} 
?>
<script type="text/javascript">
validacao({
	formId: 'altProfessor',
	corCampo: ['#FFFFFF', '#FFFFFFF'],
	corErro: ['#FFFFD7', '#CF3339'],
	requerido: ['nome=Nome do Professor', 'sobrenome=Sobrenome do Professor', 'senha=Senha', 'sexo=Sexo', 'data_nascimento=Data de Nascimento','telefone=Telefone','area_atuacao=Área de Atuação']					
});
</script>
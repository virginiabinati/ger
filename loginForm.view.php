<script type="text/javascript">
function salvar(){
	document.forms.frmLogon.submit();
}

function pressEnter(e) {
	var keynum;
	keynum = e.charCode ? e.charCode : 
						e.keyCode ? e.keyCode : 
								e.which ? e.which : 0;
	if (keynum == 13 || keynum == 40) {
		salvar();
		return false;
	}
} 

$(document).ready(function(){
  $("#login").focus();
});

</script>
<div class="geral_form" style="margin-top: 10%">
<form name="frmLogon" action="?class=Login&method=onLogarUsuario" method="post">
<table class="tabela_form">
<tr>
	<td></td>
	<td class="titulo" colspan="3" id="aviso">Acesso permitido somente aos monitores do LIAPE</td>
</tr>
<tr>
	<td rowspan="3"><img src="images/chave.png" /></td>
</tr>
<tr>
	<td class="titulo">Login:</td>
	<td><input type="text" id="login" name="login" style="width: 225px;"></td>
</tr>

<tr>
	<td class="titulo">Senha:</td>
	<td><input type="password" name="senha" style="width: 225px;" onkeypress="javascript: return pressEnter(event);" ></td>
	<td class="salvar" style="padding-bottom: 10px"><div class="botao" onclick="salvar()">Entrar</div></td>
</tr>
</table>
</form>
</div>
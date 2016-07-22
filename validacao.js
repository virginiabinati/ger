function validacao(configs)
{
	form = document.getElementById(configs["formId"]);
	if (form){ 
		formInputs = form.getElementsByTagName("input");
			
			// change color of inputs on focus
			for(i=0;i<formInputs.length;i++)
			{
				if(formInputs[i].getAttribute("type") != "submit") {
					input = formInputs[i];
					input.style.background = configs["corCampo"][0];
					input.onblur = function () {
						this.style.background = configs["corCampo"][0];
					}
					input.onfocus = function () {
						this.style.background = configs["corCampo"][1];
					}
				}
			};
		
		document.getElementById(configs["formId"]).onsubmit = function () {
			msgErro = valida();
			if(msgErro.length < 1) {
					return true;
				} else {
					alert(msgErro);
					return false;
				}
		};
		
		function valida()
		{
			requeridos = configs["requerido"];
			var msgErro = "";
			
			for(i=0; i<requeridos.length; i++) 
			{
			campo = requeridos[i].split('=');
				if (!$('#'+campo[0]).val())
				{
					$('#'+campo[0]).css('background-color', configs["corErro"][0]);
					$('#'+campo[0]).css('border', "1px solid "+configs["corErro"][1]);
					msgErro = msgErro + campo[1] + " é um campo obrigatório\n";
					
				}
				else
				{
					$('#'+campo[0]).css('background-color', configs["corCampo"][0]);
					$('#'+campo[0]).css('border', "1px solid");
				}
			}
			
			senhas = configs["senhas"];
			if (senhas)
			{
				senha1 = senhas[0];
				senha2 = senhas[1];
				if ($('#'+senha1).val() != $('#'+senha2).val())
				{
					$('#'+senha1).css('background-color', configs["corErro"][0]);
					$('#'+senha1).css('border', "1px solid "+configs["corErro"][1]);
					$('#'+senha2).css('background-color', configs["corErro"][0]);
					$('#'+senha2).css('border', "1px solid "+configs["corErro"][1]);
					msgErro = msgErro + senhas[2] + "\n";
				}
				else
				{
					$('#'+senha1).css('background-color', configs["corCampo"][0]);
					$('#'+senha1).css('border', "1px solid");
					$('#'+senha2).css('background-color', configs["corCampo"][0]);
					$('#'+senha2).css('border', "1px solid");
				}
	
			}
			
			return msgErro;
		}
	}
}
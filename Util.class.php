<?php
	class Util {
		
		function __construct() 
		{
			if(!$_SESSION["dados_usuario"])
				Login::logarUsuario();
		}
		
		function formataData($data)
		{
			$data = explode("/",$data);
			$data = $data[2]."-".$data[1]."-".$data[0];
			return $data;
		}
		function dataExtenso($data = null){
			$dia = date("d",$data);
			$ano = date("Y",$data);
			$s = date("D",$data); /* Mostra 3 primeiras letras do dia da semana em ingles */
			$m = date("n",$data); /* Mostra o Mês em números */
			
			$semana = array("Sun" => "Domingo", "Mon" => "Segunda-feira", "Tue" => "Terça-feira", "Wed" => "Quarta-feira", "Thu" => "Quinta-feira", "Fri" => "Sexta-feira", "Sat" => "Sábado"); /* Dias da Semana. */
			$mes = array(1 =>"Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"); /* Meses */
			
			$extensa = $semana[$s] . ", " . $dia . " de " . $mes[$m] . " de " . $ano;

			return $extensa;
		}
	}
?>
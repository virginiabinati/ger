<?php
class CompararDatas
{
	public function __construct()
	{
		if(!$_SESSION["dados_usuario"])
			Login::logarUsuario();
	}
	function diferenca($data1, $data2="",$tipo="")
	{
		if($data2==""){
			$data2 = date("d/m/Y H:i");
		}
		
		if($tipo==""){
			$tipo = "h";
		}
		
		for($i=1;$i<=2;$i++){
			${"dia".$i} = substr(${"data".$i},0,2);
			${"mes".$i} = substr(${"data".$i},3,2);
			${"ano".$i} = substr(${"data".$i},6,4);
			${"horas".$i} = substr(${"data".$i},11,2);
			${"minutos".$i} = substr(${"data".$i},14,2);
		}
		
		$segundos = mktime($horas2,$minutos2,0,$mes2,$dia2,$ano2)-mktime($horas1,$minutos1,0,$mes1,$dia1,$ano1);
		
		switch($tipo){
			case "m": $difere = $segundos/60; break;
			case "H": $difere = $segundos/3600; break;
			case "h": $difere = round($segundos/3600); break;
			case "D": $difere = $segundos/86400; break;
			case "d": $difere = round($segundos/86400); break;
		}
		return $difere;
	}
	
	function converte($data)
	{
		$aux= explode("/",$data);
		$data = $aux[2]."-".$aux[1]."-".$aux[0];
		return $data;
	}
}
?>
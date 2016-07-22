<style>
a {
	text-decoration: none;
	color: #000;
	font-weight: bold;
}

#tooltip {
	position: absolute;
	border: 1px solid #000;
	background-color: #ffffe1;
	padding: 2px 5px;
	color: #000;
	display: none;
}

#esq {
	width: 75%;
	float: left;
}

#dir {
	width: 20%;
	float: left;
}

.tableGrade {
	border-collapse: collapse;
	width: 95%;
}

.tableGrade tr td{
	text-align:left;
	margin: 0px;
	padding: 0px;
}

.tableColunaDestaque{
	background-color: #EEF6FC;
	height: 20px !important;
}

.min30 {
	border-bottom: solid #15428B 2px;
	height: 20px;
	vertical-align: top;
}

.min00 {
	border-bottom: solid #666 1px;
	height: 20px;
	vertical-align: top;
}

.minSize{
	height: 20px;
	width: 20px;
}

.hora {
	border-bottom: solid #15428B 2px;
	font-weight: bold;
	height: 40px;
	text-align: center;
	width: 20px !important ;
}

.box {
	background-color: #efefef;
	border-color: #777;
	border-style: solid;
	border-top-width: 1px;
	border-left-width: 1px;
	border-right-width: 2px;
	border-bottom-width: 3px;
	position:absolute;
	float:none;
	width: 7%;
}

.box table {
	font-size: 9px;
	font-weight: normal;
}

.box img {
	border: none;
}

</style>
 
<link type="text/css" href="library/jquery-ui-1.7.2.custom/css/cupertino/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="library/jquery-ui-1.7.2.custom/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="library/jquery-ui-1.7.2.custom/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript">
function getStyleClass (className) {
	for (var s = 0; s < document.styleSheets.length; s++)
	{
		if(document.styleSheets[s].rules)
		{
			for (var r = 0; r < document.styleSheets[s].rules.length; r++)
			{
				if (document.styleSheets[s].rules[r].selectorText == '.' + className)
				{
					return document.styleSheets[s].rules[r];
				}
			}
		}
		else if(document.styleSheets[s].cssRules)
		{
			for (var r = 0; r < document.styleSheets[s].cssRules.length; r++)
			{
				if (document.styleSheets[s].cssRules[r].selectorText == '.' + className)
					return document.styleSheets[s].cssRules[r];
			}
		}
	}
	
	return null;
}

$(document).ready(function() {
	$("#datepicker").datepicker();
	
	
	xOffset=10; yOffset=20;
    $(".tooltip").hover(function(e){
        this.t=this.title;
        this.title="";
        $("body").append("<p id='tooltip'>"+ this.t +"</p>");
        $("#tooltip")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px")
            .fadeIn("normal");
    },function(){
        this.title = this.t;
        $("#tooltip").remove();
    });
    $(".tooltip").mousemove(function(e){
        $("#tooltip")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px");
    });
    
    //Redimensiona o tamanho das divs com as aulas em função do tamanho de uma coluna referencial
   getStyleClass("box").style.width = ($("#referenciaTamanho").width()-2)+"px";
	
});
</script>
<div class="geral_form" style="width:100%">
<div id="esq">
<?
if($params["salas"])
{
	$salas = $params["salas"];
}
$horarioAbertura = $params["horarioAbertura"];
$horarioEncerramento = $params["horarioEncerramento"];
?>
<table class="tableGrade" id="tabelaGrade">
<tr>
	<th style="height: 20px;"></th>
	<th style="height: 20px;">
	<?
		$contColuna = 0;
	foreach ($salas as $sala)
	{
	?>
		<th <?php if ($contColuna%2 == 0 ) echo 'class="tableColunaDestaque"';?>  <?php if ($contColuna == 1 ) echo ' id="referenciaTamanho"';?>><?php echo $sala["SALA"]?></th>
	<?
		$contColuna++;
	}
	?>
</tr>
<?
if($params["conteudo"]) { 
	$aux = "";

for($i = $horarioAbertura; $i < $horarioEncerramento; $i++)
{
	?>
	<tr>
		<td rowspan="2" class="hora" style="width: 20px;"><?php echo str_pad($i, 2, "0", STR_PAD_LEFT)?></td>
		<?php
			gerarBox($params, $i,'00');
		?>
	</tr>
	<tr>
		<?php
			gerarBox($params, $i,'30');
		?>
	</tr>
<?php
}
?>					

</table>
<? } else { ?>
	<h3>Sem aulas programadas</h3>
<? } ?>
</div>
<div id="dir">
	<div id="datepicker"></div>
	<? if($params["exporadicas"]) { ?>
		<div id="exporadicas">
			<table class="tabela_comum" style="width:220px; margin-right: 10px">
				<caption>Reservas esporádicas</caption>
				<tr>
					<th class="center">Dia</th>
					<th class="center">Disciplina</th>
				</tr>
				<? foreach($params["exporadicas"] as $expo) { 
						$title = "<span class='capitalize'>" . $expo["nome"]." ".$expo["sobrenome"] . "</span><br />" .$expo["curso"] . "<br /><span class='capitalize'>" . $expo["disciplina"] . "</span><br /> Aula das " . $expo["inicio"] . " às " . $expo["fim"];
				?>
				<tr class="tooltip" title="<?=$title ?>">
					<td><?=$expo["dia"] ?></td>
					<td class="nome"><?=str_replace("ii","II" , $expo["disciplina"]) ?></td>
				</tr>
				<? } ?>
			</table>
		</div>
	<? } else echo "<h4>Sem reservas exporádicas</h4>"; ?>
</div>
<div style="clear:both" ></div>
</div>


<?php
function gerarBox($params, $i,$min)
{
	?>
		<td class="min<?php echo $min?> minSize"><?php echo $min?></td> 
	<?php
	$salas = $params["salas"];
	$contColuna = 0;
	foreach ($salas as $sala)
	{
		$htm = '<td class="min'.$min;
		if ($contColuna%2 == 0 )
			$htm.= ' tableColunaDestaque';
		$htm.= '" ';
		$contColuna++;
		$div='';
		foreach($params["conteudo"] as $aulas) {
				$arrayInicio  = explode(':',$aulas["inicio"]);
				$arrayFim  = explode(':',$aulas["fim"]);
				if ($aulas["numero"].$aulas["bloco"] == $sala["SALA"] && $i==$arrayInicio[0] && $i<=$arrayFim[0] && ( ($min == '00')? $arrayInicio[1]<30 : $arrayInicio[1]>=30) ){
					$title = '<span class=\'capitalize\'>'.$aulas["numero"].' '.$aulas["bloco"] . '</span><br /><span class=\'capitalize\'>'.$aulas["nome"].' '.$aulas["sobrenome"] .'</span><br />'.$aulas["curso"].'<br /><span class=\'capitalize\'>'.$aulas["disciplina"]. '</span><br /> Aula das '. $aulas["inicio"].' às '. $aulas["fim"];
					if ($aulas["projetor"] == 1){
						$title.= '<img style=\'margin:6px\' src=\'images/projetor.png\' border=\'0\' />'; 
					}
					$sizeBox = intval( (($arrayFim[0]-$arrayInicio[0])*40) + ( ($arrayFim[1]-$arrayInicio[1])*0.7) ) ;
					$minInicio = $arrayInicio[1] >= 30 ? $arrayInicio[1]-30 : $arrayInicio[1];
					$borderBox = intval($minInicio*0.7);
					
					$div = '<div id="idAula'.$aulas["id_aula"].'" class="box tooltip" style="height:'.$sizeBox.'px;';
					
					if ($borderBox>0)
						$div.= ' margin-top: '.$borderBox.'px; ';
					
					if ($aulas["exporadica"] == 1)
						$div.= ' background-color: #FFF4BC; ';
					
					$div.='"  title="'.$title.'">';
					
					$div.='<table>
							<tr>
								<td ';
					$div.='>';
					
					if($_SESSION["dados_usuario"]["idgrupo"] == 1) {
						$div.='<a href="?class=Aulas&method=altAula&id='.$aulas["id_aula"].'">';
						$div.=$aulas["inicio"].'-'.$aulas["fim"];
						$div.='</a>';
					}
					else
					{
						$div.=$aulas["numero"].' '.$aulas["bloco"].'<BR>'.$aulas["inicio"].'-'.$aulas["fim"].'<BR>';
					}
					if($_SESSION["dados_usuario"]["idgrupo"] == 1) {
						$div.='<img src="images/deletar.jpg" onclick="javascript:if(confirm(\'Tem certeza que deseja deletar esse registro?\')) { location.href=\'?class=Aulas&method=onDeletarAula&id='.$aulas["id_aula"].'\' } " class="link" style="padding-left:5px">';
					}
					$div.='</td>
						</tr>
					</table>';
					$div.='</div>';
				}	
		}
		$htm.='>'.$div.'</td>';
		echo $htm; 
	}
}
?>
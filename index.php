<?php
function __autoload($class)
{
	$folders = array('app','app/ado','app/control','app/model','app/template');
	foreach ($folders as $folder) {
		$path = $folder."/".$class.".class.php";
		if(file_exists($path)) {
			include_once($path);
			continue;
		}
	}
}
$app = new TApplication();
$app->run();

/*
 * @todo Gerar classes model, aprimorar TRecord
 * @todo Criar template default e montar tela principal
 * @todo bolar menu
 * @todo Criar padronização para telas de cadastros
 * @todo criar controllers principais 
 * @todo tratar exceptions nos controllers
 * @todo funcionamento do log
 * @todo HasOne, BelongsTo, HasMany association
 * 
 */
?>
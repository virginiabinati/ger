<select name='idgrupo'>
<?php
if($params)
	{
	foreach($params['grupos'] as $grupo){ ?>
	<option <? if ($grupo["id"]==$params[selected]) echo 'selected="selected"'; ?> value="<?=$grupo["id"] ?>"><?=$grupo["nome"] ?></option>
<?php
	}
}
?>
</select>
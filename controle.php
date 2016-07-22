<? 
	session_start(); 
	if(!isset($_POST["noincludes"]) && $_POST["noincludes"] != 1) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
<script src="js/jquery.jeditable.js" type="text/javascript"></script>
<script src="js/jquery.corner.js" type="text/javascript"></script>
<script src="js/validacao.js" type="text/javascript"></script>
<script type="text/javascript" src="library/jquery.calendario/_scripts/jquery.click-calendario-1.0.js"></script>
<script type="text/javascript" src="library/jnunemaker-fancy-zoom/jquery/js/fancyzoom.js"></script>  
<title>GER</title>
<link rel="stylesheet" type="text/css" href="css/main.style.css" />
<link href="library/jquery.calendario/_style/jquery.click-calendario-1.0.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="library/fisheye-menu/fisheye-menu.css" />
<script src="library/fisheye-menu/fisheye.js" type='text/javascript'></script>
<script type="text/javascript">
$(document).ready(function() {
	$("body").find("input[type=checkbox]")
            .css("border","none")
            .css("background-color","transparent");
    $("body").find("input[type=radio]")
            .css("border","none")
            .css("background-color","transparent");	
});
</script>
<body>
<?
}
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

$class= $_GET['class'];
$method= $_GET['method'];

if (class_exists($class))
{
	$obj= new $class;
	$obj->$method();
}
if(!isset($_POST["noincludes"]) && $_POST["noincludes"] != 1) {
?>
</body>
</html>
<? } ?>
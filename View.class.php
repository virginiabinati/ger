<?php
class View {
	
	public function __construct($viewName = null){
		if($viewName)
			$this->loadView($viewName);
	}
	
	public function loadView($viewName, $params = null){		
		$pathView = "app/view/".$viewName.".view.php";
		if(file_exists($pathView))
			require($pathView);
		else
			echo "<div class=\"erro\">Erro : Não foi possivel carregar a tela especificada.</div>";
	}
}
?>
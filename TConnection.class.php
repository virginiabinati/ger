<?php
/*
 * classe TConnection
 *  gerencia conexões com bancos de dados,
 *  através de arquivos de configuração.
 */
final class TConnection
{
	/*
	 * método __construct()
	 *  Não existirão instâncias de TConnection
	 *  por isto, estamos marcando-o como private
	 */
	private function __construct() {}

	/*
	 * método open()
	 *  recebe o nome do banco de dados,
	 *  verifica se existe arquivo de configuração
	 *  para ele, e instancia o objeto PDO correspondente
	 */
	public static function open()
	{

		// lê as informações contidas no arquivo
		$user  	= 'root';
		$pass  	= 'wovy49r';
		$db 	= 'ger';
		$host 	= '192.168.100.190';

		try {
			$conn = new PDO("mysql:host=$host;dbname=$db", $user,$pass);
		}
		catch(Exception $e){
			echo "Erro de conexão com o Banco de Dados";
			break;
		}

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// define para que o PDO lance exceções na ocorrência de erros
		// retorna o objeto instanciado.
		return $conn;
	}
}
?>
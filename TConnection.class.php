<?php
/*
 * classe TConnection
 *  gerencia conex�es com bancos de dados,
 *  atrav�s de arquivos de configura��o.
 */
final class TConnection
{
	/*
	 * m�todo __construct()
	 *  N�o existir�o inst�ncias de TConnection
	 *  por isto, estamos marcando-o como private
	 */
	private function __construct() {}

	/*
	 * m�todo open()
	 *  recebe o nome do banco de dados,
	 *  verifica se existe arquivo de configura��o
	 *  para ele, e instancia o objeto PDO correspondente
	 */
	public static function open()
	{

		// l� as informa��es contidas no arquivo
		$user  	= 'root';
		$pass  	= 'wovy49r';
		$db 	= 'ger';
		$host 	= '192.168.100.190';

		try {
			$conn = new PDO("mysql:host=$host;dbname=$db", $user,$pass);
		}
		catch(Exception $e){
			echo "Erro de conex�o com o Banco de Dados";
			break;
		}

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// define para que o PDO lance exce��es na ocorr�ncia de erros
		// retorna o objeto instanciado.
		return $conn;
	}
}
?>
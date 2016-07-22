<?php
class AD 
{
	const dominio 		= 'DC=liape,DC=unaerp,DC=br';
	const primeiraOU 	= 'OU=All Users,';
	const grupoOU		= 'OU=All Groups,';
	const scriptLogon	= 'script3.bat';
	
	function __construct() 
	{
	}
	
	 /*
     * m�todo obterDN()
     *  Monta o nome distinto para a inser��o de um usuario novo
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $tipo = tipo do usu�rio Monitor, Student ou Teacher
     * @param $curso = = c�digo do curso no cadastro CIT
     *  o curso n�o � necess�rio para Monitor e Teachers 
     */
	function obterDN($idUsuario, $tipo, $curso = NULL)
	{
		$segundaOU	= 'OU='.$tipo.',';		//Segundo n�vel de OU presentes em todos os usuario
		$dn = 'CN='.$idUsuario.',';			//Ex CN=Filipe,OU=Students,...
		
		if ( $tipo == 'Students' )			//Os Students tem que ser precedidos do seu curso
		{
			$terceiraOU	= 'OU='.$curso.',';	//Concatenando a variavel do Curso
			$dn	.= $terceiraOU;				//CN=Filipe,OU=2M,OU=Students,OU=All Users,
		}
		$dn .= $segundaOU.self::primeiraOU.self::dominio;
		return $dn;							//Ex CN=Filipe,OU=2M,OU=Students,OU=All Users,DC=liape,DC=unaerp,DC=br
	}
	
	/*
     * m�todo onAddUser()
     *  Adiciona novos usuarios ao Dominio
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $idUsuario = identificador do Usuario,
     * @param $tipo = Monitor, Student ou Teacher
     * @param $curso = c�digo do curso no cadastro CIT
     *  o curso n�o � necess�rio para Monitor e Teachers
     */
	function onAddUser($idUsuario, $nomeCompleto, $tipo, $curso = NULL)
	{
		$dn = self::obterDN($idUsuario, $tipo, $curso);
		
		$user["cn"] 		 		= $idUsuario;		//Canonical Name, do Usuario.
		$user["uid"]		 		= $idUsuario; 		//Id do usuario, atributo do AD.
		$user["samaccountname"] 	= $idUsuario;		//Atributo obrigatorio do AD, para gerar o ID o SAM.
		$user["objectClass"] 		= 'User';  			//Classe do objeto, Usuario.
		$user["description"]		= $nomeCompleto;	//Descricao para facilitar a identifica��o do Usuario.
		$user["useraccountcontrol"]	= '544';			//Codigo para manter a conta ativa.
		$user["scriptpath"]			= self::scriptLogon;//Script executado no Login do Usu�rio.
		$user["accountexpires"]		= '0';				//Para que a conta criada esteja ativada.
		
		$ad = self::conectar();
		$result = ldap_add($ad, $dn, $user);
		self::desconectar($ad);
		if ($result)
		{
			self::onAddUserGroup($idUsuario,$tipo,$curso);
		}
	}
	
	
	/*
     * m�todo onChangePassword()
     *  Permite fazer a altea��o do Password do Usuario
     *  vale reparar que estamos usando um m�todo externo
     *  dsmod � aplica��o do WINDOWS que precisa estar dispon�vel
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $dn = distinguishedName do usuario no AD
     */
	function onChangeUserPassword($idUsuario, $password)
	{
		$user = self::searchUser($idUsuario);
		$dn = $user[0]['dn'];
		if ( $dn )
		{	
			exec('dsmod user "'.$dn.'" -pwd '.$password , $saida, $resultado);
			$result = ($resultado == 0) ? TRUE : FALSE;
			return $result; 
		}
		return FALSE;
	}
	
	/*
     * m�todo onChangeUserCurso()
     * 	M�todo que altera o usuario de CURSO  
     * 
     * @param $idUsuario = identificado do usu�rio
     * @param $idCurso = c�digo do curso no cadastro CIT
     *  o curso n�o � necess�rio para Monitor e Teachers
     */	
	function onChangeUserCurso($idUsuario, $idCurso)
	{
		$user = self::searchUser($idUsuario);
		$curso = self::searchCursoById($idCurso);
		if ( $user[0]['dn']  && $curso[0]['dn'])
		{	
			$dnArray = explode(',',$user[0]['dn']);		//Explodindo o DN do usuario 	
			$cn = $dnArray[0];							//Armazenando o CN do Usuario Ex. CN=778941
			unset($dnArray[0]);							//Removendo o atributo CN
			$dnArray[1] = 'OU='.$idCurso;				//Inserindo no Array o novo Curso
			$cursoDN = implode(',',$dnArray);			//Contruindo a nova 
			$ad = self::conectar();
			$result = ldap_rename($ad, $user[0]['dn'], $cn, $cursoDN, TRUE);	//Mudando o usario de OU, que representa os Cursos
			self::desconectar($ad);
		}
		return $result;
	}
	
	/*
     * m�todo onInactivateUser()
     *  Permite insativar um usuario
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     */
	function onInactivateUser($idUsuario)
	{
		$user = self::searchUser($idUsuario);
		if ( $user[0]['dn'])
		{	
			$users['useraccountcontrol']= 546;
			$ad = self::conectar();
			$result = ldap_modify($ad, $user[0]['dn'],$users);	//Mudando o usario de OU, que representa os Cursos
			self::desconectar($ad);
		}
		return $result;
	}
	
	/*
     * m�todo onActivateUser()
     *  Prov� os meios para ativar a conta do Usu�rio
     * 
     * @param $idUsuario = identificador do Usuario, 
2     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     */
	function onActivateUser($idUsuario)
	{
		$user = self::searchUser($idUsuario);
		if ( $user[0]['dn'])
		{	
			$users['useraccountcontrol']= 544;
			$ad = self::conectar();
			$result = ldap_modify($ad, $user[0]['dn'],$users);	//Mudando o usario de OU, que representa os Cursos
			self::desconectar($ad);
		}
		return $result;
	}

	
	/*
     * m�todo onAddNameToUser()
     *  Adiciona o nome do Usu�rio no campo Description do AD
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $nomeCompleto = Nome completo do Usu�rio
     */
	function onAddNameToUser($idUsuario, $nomeCompleto)
	{
		$user = self::searchUser($idUsuario);
		$dn = $user[0]['dn'];
		if ( $dn )
		{	
			$users["description"] = $nomeCompleto;	//Descricao para facilitar a identifica��o do Usuario.
			$ad = self::conectar();
			$result = ldap_modify($ad, $dn,$users);	//Mudando o usario de OU, que representa os Cursos
			self::desconectar($ad);
		}
		return $result;
	}
	
	
	/*
     * m�todo onChangePasswordNextLogonUser()
     *  Reseta as senha do usuario para que fique igual ao seu login
     * 	assim o usuario pode no proximo Logon colocar a senha que 
     *  desejar
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $on = TRUE habilita altera senha no Proximo LOGON (default)
     * 		  $on = FALSE desabilita altera��o da senha 	
     */
	function onChangePasswordNextLogonUser($idUsuario, $on = TRUE)
	{
		$user = self::searchUser($idUsuario);
		$dn = $user[0]['dn'];
		if ( $dn)
		{	
			if ( $on )
			{
				$result = self::onChangeUserPassword($idUsuario,$idUsuario);	//Trocando as senha para o usuario poder Logar
			}
			else
			{
				$result = TRUE; //Caso n�o vamos mudar a senha do usuario, pois estamos apenas removendo a marca��o de trocar senha no proximo Logon 
			}
			
			if ($result)
			{
				$status = $on ? 0 : -1;
				$users['pwdlastset']= $status;
				$ad = self::conectar();
				$result = ldap_modify($ad, $dn,$users);		//Mudando o usario de OU, que representa os Cursos
				self::desconectar($ad);
			}
			else
			{
				print_r('Erro ao resetar senha do Usu�rio !!!!');
				break;
			}
			
			
		}
		return $result;
	}
	
	/*
     * m�todo onDelUser()
     *  Permite deletar Usuarios do dominio
     *  bastando passar seu nome distinto
     * 
     * @param $dnUsuario = distinguishedName do usuario no AD
     */	
	function onDelUser($dnUsuario)
	{
		$ad = self::conectar();
		$result = ldap_delete($ad, $dnUsuario);
		
		self::desconectar($ad);
		return $result;
	}
	
	/*
     * m�todo onAddUserGroup()
     *  Sempre que um usu�rio � criado ele precisa ser
     *  vinculado a um grupo, para aplicar as GPO�s
     *  do dominio, assim temos apenas tr�s grupos
     *  que s�o representados pelo par�metro $tipo
     * 	Monitor, Student ou Teacher
     * 
     * @param $idUsuario = identificado do usu�rio
     * @param $tipo = Monitor, Student ou Teacher
     * @param $curso = c�digo do curso no cadastro CIT
     *  o curso n�o � necess�rio para Monitor e Teachers
     */	
	function onAddUserGroup($idUsuario, $tipo, $curso = NULL)
	{
		$dn = self::obterDN($idUsuario, $tipo, $curso);		//Precisamos saber o nomeDistinto do usuario.
		
		$grupo["member"] = $dn;					
		$gn = 'CN='.$tipo.','.self::grupoOU.self::dominio;	//Segundo o tipo, encontramos o grupo.
		
		$ad = self::conectar();
		$result = ldap_mod_add($ad, $gn, $grupo);			//Usamos o m�todo para adicionar o Usuario ao GRUPO.
		
		self::desconectar($ad);
		return $result;
	}
	
	/*
     * m�todo onAddCurso()
     *  Esta fun��o crias as OU�s que representam
     *  os cursos diponibilizados na universidade
     * 
     * @param $codigo = id do curso, v�lido no CIT
     * @param $nome = nome do curso tamb�m fornecido pelo CIT
     */	
	function onAddCurso($codigo, $nome)
	{
		$dn = 'OU='.$codigo.',OU=Students, '.self::primeiraOU.self::dominio;
		$ad = self::conectar();
		$curso["ou"] 			= $codigo;					//Principal Atributo de uma OU
		$curso["objectClass"] 	= 'organizationalUnit';		//Classe do bojeto 
		$curso["objectCategory"]= 'CN=Organizational-Unit,CN=Schema,CN=Configuration,DC=liape,DC=unaerp,DC=br';	//String que identifica a categoria do objeto
		$curso["instanceType"] 	= '4';						//
		$curso["description"] 	= $nome;					//Descri��o que aparece com o nome do curso
		$result = ldap_add($ad, $dn, $curso);
		
		self::desconectar($ad);
		return $result;
	}
	
	/*
     * m�todo searchUser()
     *  Realiza buscas por um determinado usuario no AD.
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o c�digo de aluno, nome do monitor 
     * 	ou o nome do professor.
     */
	function searchUser($idUsuario)
	{
		$ad = self::conectar();
		$dn = self::primeiraOU.self::dominio;
		$filtro 	= '(cn='.$idUsuario.')';
		$resultado	= ldap_search($ad, $dn, $filtro);
		$entradas 	= ldap_get_entries($ad, $resultado);

		self::desconectar($ad);
		return $entradas;
	}
	
	/*
     * m�todo searchCursoById()
     *  Busca por um determinado curso com base no c�digo.
     * 
     * @param $idCurso = identificador do Curso. 
     * 	Ex.  2M - Engenharia da Computa��o 
     */
	function searchCursoById($idCurso)
	{
		$ad = self::conectar();
		$dn = 	'OU=Students, '.self::primeiraOU.self::dominio;
		$filtro 	= '(OU='.$idCurso.')';
		$resultado	= ldap_search($ad, $dn, $filtro);
		$entradas 	= ldap_get_entries($ad, $resultado);

		self::desconectar($ad);
		return $entradas;
	}
	
	/*
     * m�todo onDelCuro()
     *  Remove uma OU que representa um curso
     * 
     * @param $dnCurso = nome distinto do Curso.  
     */
	function onDelCurso($dnCurso)
	{
		$ad = self::conectar();
		$result = ldap_delete($ad, $dnCurso);
		self::desconectar($ad);
	}
	
	/*
     * m�todo conectar()
     *  Realiza a conex�o do o AD.
     * 	Primeiro l�-se o arquivo config/ad.ini
     *  que tem na sua primeira linha o usuario de conex�o
     *  seguido dos servers que est�o dipon�veis
     * Ex. do arquivo
     *  admin = SENHA
     *  uranus = 192.168.100.6
     *  mercury = 192.168.100.5
     *  jupiter = 192.168.100.4
     *  nome_do_SERVER = IP_do_SERVER
     */
	function conectar()
	{
		$servers = parse_ini_file('app/config/ad.ini');
//		$servers = parse_ini_file('../config/ad.ini'); //Usar apenas com o arquivo de teste
		$user = key($servers);
		$password = $servers[$user];
		unset($servers[$user]);
		foreach ($servers as $server=>$nome)
		{
			$conn = ldap_connect($server);
			if ($conn) 
				break;
		}
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("N�o foi possivel configurar o Protocolo LDAP");
		ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
		$connect = ldap_bind($conn, "liape\\$user", "$password") or die ("N�o foi possivel se ligar ao Domin�o");
		return $conn;
	}
	
	/*
     * m�todo desconectar()
     *  Deconectar do LDAP
     * 
     * 	@param $ad = conex�o com o banco de DADOS.
     */
	function desconectar($conn)
	{
		ldap_unbind($conn);	//M�todo de desconectar do DOMINIO
	}
}
?>
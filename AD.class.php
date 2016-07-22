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
     * mйtodo obterDN()
     *  Monta o nome distinto para a inserзгo de um usuario novo
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $tipo = tipo do usuбrio Monitor, Student ou Teacher
     * @param $curso = = cуdigo do curso no cadastro CIT
     *  o curso nгo й necessбrio para Monitor e Teachers 
     */
	function obterDN($idUsuario, $tipo, $curso = NULL)
	{
		$segundaOU	= 'OU='.$tipo.',';		//Segundo nнvel de OU presentes em todos os usuario
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
     * mйtodo onAddUser()
     *  Adiciona novos usuarios ao Dominio
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $idUsuario = identificador do Usuario,
     * @param $tipo = Monitor, Student ou Teacher
     * @param $curso = cуdigo do curso no cadastro CIT
     *  o curso nгo й necessбrio para Monitor e Teachers
     */
	function onAddUser($idUsuario, $nomeCompleto, $tipo, $curso = NULL)
	{
		$dn = self::obterDN($idUsuario, $tipo, $curso);
		
		$user["cn"] 		 		= $idUsuario;		//Canonical Name, do Usuario.
		$user["uid"]		 		= $idUsuario; 		//Id do usuario, atributo do AD.
		$user["samaccountname"] 	= $idUsuario;		//Atributo obrigatorio do AD, para gerar o ID o SAM.
		$user["objectClass"] 		= 'User';  			//Classe do objeto, Usuario.
		$user["description"]		= $nomeCompleto;	//Descricao para facilitar a identificaзгo do Usuario.
		$user["useraccountcontrol"]	= '544';			//Codigo para manter a conta ativa.
		$user["scriptpath"]			= self::scriptLogon;//Script executado no Login do Usuбrio.
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
     * mйtodo onChangePassword()
     *  Permite fazer a alteaзгo do Password do Usuario
     *  vale reparar que estamos usando um mйtodo externo
     *  dsmod й aplicaзгo do WINDOWS que precisa estar disponнvel
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
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
     * mйtodo onChangeUserCurso()
     * 	Mйtodo que altera o usuario de CURSO  
     * 
     * @param $idUsuario = identificado do usuбrio
     * @param $idCurso = cуdigo do curso no cadastro CIT
     *  o curso nгo й necessбrio para Monitor e Teachers
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
     * mйtodo onInactivateUser()
     *  Permite insativar um usuario
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
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
     * mйtodo onActivateUser()
     *  Provк os meios para ativar a conta do Usuбrio
     * 
     * @param $idUsuario = identificador do Usuario, 
2     * 	podendo este ser o cуdigo de aluno, nome do monitor 
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
     * mйtodo onAddNameToUser()
     *  Adiciona o nome do Usuбrio no campo Description do AD
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $nomeCompleto = Nome completo do Usuбrio
     */
	function onAddNameToUser($idUsuario, $nomeCompleto)
	{
		$user = self::searchUser($idUsuario);
		$dn = $user[0]['dn'];
		if ( $dn )
		{	
			$users["description"] = $nomeCompleto;	//Descricao para facilitar a identificaзгo do Usuario.
			$ad = self::conectar();
			$result = ldap_modify($ad, $dn,$users);	//Mudando o usario de OU, que representa os Cursos
			self::desconectar($ad);
		}
		return $result;
	}
	
	
	/*
     * mйtodo onChangePasswordNextLogonUser()
     *  Reseta as senha do usuario para que fique igual ao seu login
     * 	assim o usuario pode no proximo Logon colocar a senha que 
     *  desejar
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
     * 	ou o nome do professor.
     * @param $on = TRUE habilita altera senha no Proximo LOGON (default)
     * 		  $on = FALSE desabilita alteraзгo da senha 	
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
				$result = TRUE; //Caso nгo vamos mudar a senha do usuario, pois estamos apenas removendo a marcaзгo de trocar senha no proximo Logon 
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
				print_r('Erro ao resetar senha do Usuбrio !!!!');
				break;
			}
			
			
		}
		return $result;
	}
	
	/*
     * mйtodo onDelUser()
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
     * mйtodo onAddUserGroup()
     *  Sempre que um usuбrio й criado ele precisa ser
     *  vinculado a um grupo, para aplicar as GPOґs
     *  do dominio, assim temos apenas trкs grupos
     *  que sгo representados pelo parвmetro $tipo
     * 	Monitor, Student ou Teacher
     * 
     * @param $idUsuario = identificado do usuбrio
     * @param $tipo = Monitor, Student ou Teacher
     * @param $curso = cуdigo do curso no cadastro CIT
     *  o curso nгo й necessбrio para Monitor e Teachers
     */	
	function onAddUserGroup($idUsuario, $tipo, $curso = NULL)
	{
		$dn = self::obterDN($idUsuario, $tipo, $curso);		//Precisamos saber o nomeDistinto do usuario.
		
		$grupo["member"] = $dn;					
		$gn = 'CN='.$tipo.','.self::grupoOU.self::dominio;	//Segundo o tipo, encontramos o grupo.
		
		$ad = self::conectar();
		$result = ldap_mod_add($ad, $gn, $grupo);			//Usamos o mйtodo para adicionar o Usuario ao GRUPO.
		
		self::desconectar($ad);
		return $result;
	}
	
	/*
     * mйtodo onAddCurso()
     *  Esta funзгo crias as OUґs que representam
     *  os cursos diponibilizados na universidade
     * 
     * @param $codigo = id do curso, vбlido no CIT
     * @param $nome = nome do curso tambйm fornecido pelo CIT
     */	
	function onAddCurso($codigo, $nome)
	{
		$dn = 'OU='.$codigo.',OU=Students, '.self::primeiraOU.self::dominio;
		$ad = self::conectar();
		$curso["ou"] 			= $codigo;					//Principal Atributo de uma OU
		$curso["objectClass"] 	= 'organizationalUnit';		//Classe do bojeto 
		$curso["objectCategory"]= 'CN=Organizational-Unit,CN=Schema,CN=Configuration,DC=liape,DC=unaerp,DC=br';	//String que identifica a categoria do objeto
		$curso["instanceType"] 	= '4';						//
		$curso["description"] 	= $nome;					//Descriзгo que aparece com o nome do curso
		$result = ldap_add($ad, $dn, $curso);
		
		self::desconectar($ad);
		return $result;
	}
	
	/*
     * mйtodo searchUser()
     *  Realiza buscas por um determinado usuario no AD.
     * 
     * @param $idUsuario = identificador do Usuario, 
     * 	podendo este ser o cуdigo de aluno, nome do monitor 
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
     * mйtodo searchCursoById()
     *  Busca por um determinado curso com base no cуdigo.
     * 
     * @param $idCurso = identificador do Curso. 
     * 	Ex.  2M - Engenharia da Computaзгo 
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
     * mйtodo onDelCuro()
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
     * mйtodo conectar()
     *  Realiza a conexгo do o AD.
     * 	Primeiro lк-se o arquivo config/ad.ini
     *  que tem na sua primeira linha o usuario de conexгo
     *  seguido dos servers que estгo diponнveis
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
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Nгo foi possivel configurar o Protocolo LDAP");
		ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
		$connect = ldap_bind($conn, "liape\\$user", "$password") or die ("Nгo foi possivel se ligar ao Dominнo");
		return $conn;
	}
	
	/*
     * mйtodo desconectar()
     *  Deconectar do LDAP
     * 
     * 	@param $ad = conexгo com o banco de DADOS.
     */
	function desconectar($conn)
	{
		ldap_unbind($conn);	//Mйtodo de desconectar do DOMINIO
	}
}
?>
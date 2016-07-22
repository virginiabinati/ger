<?php
if(Login::estaLogado()) 
{
?>
	<table id="base" cellpadding="0" cellspacing="0">
	<tr>
		<td id="base_esq"></td>
		<td id="base_meio">
			<div id="fisheye_menu">    
				<ul>
					<li class="sair"><a href="?class=Login&method=principal"><img src="images/menu/home.png" border="0" title="Home"><span>Principal</span></a></li>
				<?
					(Permissoes::checaPermissao('Usuarios','','')? $acesso.='<li><a href="?class=Usuarios&method=onListarUsuarios"><img src="images/menu/usuario.png" border="0" /><span>Monitores</span></a></li>':'');
					(Permissoes::checaPermissao('Alunos','','')? $acesso.='<li><a href="?class=Alunos&method=onListarAlunos"><img src="images/menu/aluno.png" border="0" /><span>Alunos</span></a></li>':'');
					(Permissoes::checaPermissao('Professores','','')? $acesso.='<li><a href="?class=Professores&method=onListarProfessores"><img src="images/menu/professor.png" border="0" /><span>Professores</span></a></li>':'');
					(Permissoes::checaPermissao('Cursos','','')? $acesso.='<li><a href="?class=Cursos&method=onListarCursos"><img src="images/menu/curso.png" border="0" /><span>Cursos</span></a></li>':'');
					(Permissoes::checaPermissao('Disciplinas','','')? $acesso.='<li><a href="?class=Disciplinas&method=onListarDisciplinas"><img src="images/menu/disciplina.png" border="0" /><span>Disciplinas</span></a></li>':'');
					(Permissoes::checaPermissao('Salas','','')? $acesso.='<li><a href="?class=Salas&method=onListarSalas"><img src="images/menu/sala.png" border="0" /><span>Salas</span></a></li>':'');
					(Permissoes::checaPermissao('Computadores','','')? $acesso.='<li><a href="?class=Computadores&method=onListarComputadores"><img src="images/menu/computadores.png" border="0" /><span style="width:125%">Computadores</span></a></li>':'');
					(Permissoes::checaPermissao('Aulas','','')? $acesso.='<li><a href="?class=Aulas&method=onBuscarAula"><img src="images/menu/aulas.png" border="0" /><span>Aulas</span></a></li>':'');
					(Permissoes::checaPermissao('Pendencias','','')? $acesso.='<li><a href="?class=Pendencias&method=onListarPendencias"><img src="images/menu/pendencia.png" border="0" /><span>Pendências</span></a></li>':'');
					(Permissoes::checaPermissao('Grupos','','')? $acesso.='<li><a href="?class=Grupos&method=onListarGrupos"><img src="images/menu/grupo.png" border="0" /><span>Grupos</span></a></li>':'');
					(Permissoes::checaPermissao('Grade','','')? $acesso.='<li><a href="?class=Grade&method=onListarGrade"><img src="images/menu/grade.png" border="0" /><span>Grade</span></a></li>':'');
					(Permissoes::checaPermissao('Relatorios','','')? $acesso.='<li><a href="?class=Relatorios&method=onListarRelatorios"><img src="images/menu/relatorio.png" border="0" /><span>Relatórios</span></a></li>':'');
					echo $acesso;
				?>
					<li class="sair"><a href="?class=Salas&method=onListarSalasLivres"><img src="images/menu/display.png" border="0"><span>Display</span></a></li>
					<li class="sair"><a href="?class=Login&method=onLogoff"><img src="images/menu/sair.png" border="0"><span>Sair</span></a></li> 
				</ul>
			</div>
		</td>
		<td id="base_dir"></td>
	</tr>
	</table>
	
	<div id="submenu">
		<ul>
		<?php
		switch($params)
		{
			case 'Alunos':
				echo'   <li>
							<a style="text-align:right" href="?class=Alunos&method=addAluno">
								<img src="images/add_button.png" border="0" title="Cadastrar aluno" />
							</a>
						</li>
						<li>
							<a href="?class=Alunos&method=xmlAlunos" onclick="consumindoXml()">
								<img src="images/add_xml.png" border="0" title="Cadastrar aluno por xml" />
							</a>
						</li>';
			break;
			case 'Usuarios':
				echo'	<li>
							<a href="?class=Usuarios&method=addUsuario">
								<img src="images/add_button.png" border="0" title="Cadastrar usuário" />
							</a>
						</li>';
			break;
			case 'Professores':
				echo'	<li>
							<a href="?class=Professores&method=addProfessor">
								<img src="images/add_button.png" border="0" title="Cadastrar professor" />
							</a>
						</li>
						<li>
							<a href="?class=Professores&method=onXmlProfessores" onclick="consumindoXml()">
								<img src="images/add_xml.png" border="0" title="Cadastrar professores por xml" />
							</a>
						</li>';
			break;		
			case 'Cursos':
				echo'	<li>
							<a href="?class=Cursos&method=addCurso">
								<img src="images/add_button.png" border="0" title="Cadastrar curso" />
							</a>
						</li>
						<li>
							<a href="?class=Cursos&method=onXmlCursos" onclick="consumindoXml()">
								<img src="images/add_xml.png" border="0" title="Cadastrar cursos por xml" />
							</a>
						</li>';
			break;	
			case 'Pendencias':
				echo'	<li>
							<a href="?class=Pendencias&method=addPendencia">
								<img src="images/add_button.png" border="0" title="Cadastrar pendencia" />
							</a>
						</li>';
				break;
			case 'Grupos':
				echo'	<li>
							<a href="?class=Grupos&method=addGrupo">
								<img src="images/add_button.png" border="0" title="Cadastrar grupo" />
							</a>
						</li>';
				break;
			case 'Salas':
				echo'	<li>
							<a href="?class=Salas&method=addSala">
								<img src="images/add_button.png" border="0" title="Cadastrar sala" />
							</a>
						</li>';
				break;
			case 'Computadores':
				echo'	<li>
							<a href="?class=Computadores&method=addComputador">
								<img src="images/add_button.png" border="0" title="Cadastrar computador" />
							</a>
						</li>';
				break;
			case 'Disciplinas':
				echo'	<li>
							<a href="?class=Disciplinas&method=addDisciplina">
								<img src="images/add_button.png" border="0" title="Cadastrar disciplina" />
							</a>
						</li>
						<li>
							<a href="?class=Disciplinas&method=onXmlDisciplinas" onclick="consumindoXml()">
								<img src="images/add_xml.png" border="0" title="Cadastrar disciplinas por xml" />
							</a>
						</li>';
				break;
			case 'Aulas':
				echo'	<li>
							<a href="?class=Aulas&method=addAula">
								<img src="images/add_button.png" border="0" title="Cadastrar aula" />
							</a>
						</li>';
				break;
			case 'Grade':
				echo'	<li>
							<a href="?class=Grade&method=addGrade">
								<img src="images/add_button.png" border="0" title="Cadastrar grade" />
							</a>
						</li>';
				break;
			case 'Usuarios':
				echo'	<li>
							<a href="?class=Usuarios&method=addUsuario">
								<img src="images/add_button.png" border="0" title="Cadastrar usuário" />
							</a>
						</li>';
				break;
			default:
				echo "";
		}
		?>
		</ul>
	</div>
<?
}
?>
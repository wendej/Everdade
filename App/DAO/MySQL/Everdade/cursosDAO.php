<?php

namespace App\DAO\MySQL\Everdade;

class CursosDAO extends Conexao
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function selecionaCursos(): array
	{
		
		$cursos = $this->pdo
			->query('SELECT * FROM curso;')
			->fetchAll(\PDO::FETCH_ASSOC);

		return $cursos;
	}

	public function selecionaAlunosCurso($idCurso): array
	{
		
		$alunos = $this->pdo
			->query('
				SELECT nome 
				FROM usuario 
				INNER JOIN aluno ON aluno.usuario_id_usuario1 = usuario.id_usuario 
				WHERE aluno.curso_id_curso1 =  '.$idCurso.';')
			->fetchAll(\PDO::FETCH_ASSOC);

		return $alunos;
	}
}
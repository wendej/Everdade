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
}
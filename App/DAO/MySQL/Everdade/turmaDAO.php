<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\TurmaModel;

class TurmaDAO extends Conexao
{

	public function __construct()
	{
		parent::__construct();
	}

    public function selecionaTurma($idTurma): array
    {
        
        $turma = $this->pdo
            ->query("SELECT * FROM turma WHERE id_turma = ".$idTurma.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $turma;
    }

    public function selecionaTodasTurmas(): array
    {  
        $turma = $this->pdo
            ->query("SELECT * FROM turma;")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $turma;
    }

	public function insereTurma(TurmaModel $turma, $idProfessor): void
	{
        $statement = $this->pdo
            ->prepare("INSERT INTO turma VALUES(
                null,
                :nome,
                :disciplina,
                :professor_id_professor 
            );");

        $statement->execute([
            'nome' => $turma->getNome(),
            'disciplina' => $turma->getDisciplina(),
            'professor_id_professor' => $idProfessor
        ]);
	}

    public function atualizaTurma(TurmaModel $turma, $idTurma): void
    {
        $statement = $this->pdo
            ->prepare("UPDATE turma SET
                nome = ?,
                disciplina = ?,
            WHERE id_turma = ".$idTurma.";");

        $statement->execute([
            $turma->getNome(),
            $turma->getDisciplina(),
        ]);
    }
}
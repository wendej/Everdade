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

	public function insereTurma(TurmaModel $turma, $data): void
	{
        $statement = $this->pdo
            ->prepare("INSERT INTO turma VALUES(
                null,
                :nome,
                :disciplina,
                :professor_id_professor,
                :unidade_id_unidade,
                :curso_id_curso  
            );");

        $statement->execute([
            'nome' => $turma->getNome(),
            'disciplina' => $turma->getDisciplina(),
            'professor_id_professor' => $data['idProfessor'],
            'unidade_id_unidade' => $data['idUnidade'],
            'curso_id_curso' => $data['idCurso']
        ]);
	}

    public function atualizaTurma(TurmaModel $turma, $data): void
    {
        $statement = $this->pdo
            ->prepare("UPDATE turma SET
                nome = ?,
                disciplina = ?,
                unidade_id_unidade = ?,
                curso_id_curso = ?
            WHERE id_turma = ".$data['idTurma'].";");

        $statement->execute([
            $turma->getNome(),
            $turma->getDisciplina(),
            $data['idUnidade'],
            $data['idCurso']
        ]);
    }

    public function selecionaMaiorId()
    {
        $queryId = "SELECT MAX(id_turma) AS id FROM turma";
        $id = $this->pdo->query($queryId);
        return $id->fetch(\PDO::FETCH_ASSOC);
    }

    public function insereAlunoTurma($idAluno, $idTurma): void
    {
        $statement = $this->pdo
            ->prepare("INSERT INTO aluno_has_turma VALUES(
                :aluno_id_aluno,
                :turma_id_turma 
            );");

        $statement->execute([
            'aluno_id_aluno' => $idAluno,
            'turma_id_turma' => $idTurma
        ]);
    }
}
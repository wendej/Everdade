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
            ->query("SELECT * 
                    FROM turma 
                    WHERE id_turma = ".$idTurma.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $turma;
    }

    public function selecionaAlunosTurma($idTurma)
    {
        $alunosTurma = $this->pdo
            ->query("SELECT aluno.id_aluno, usuario.nome
                    FROM  aluno_has_turma
                    INNER JOIN aluno ON aluno.id_aluno = aluno_has_turma.aluno_id_aluno
                    INNER JOIN usuario ON aluno.usuario_id_usuario1 = usuario.id_usuario
                    WHERE aluno_has_turma.turma_id_turma = ".$idTurma.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $alunosTurma;
    }

    public function selecionaTurmasAluno($idAluno) 
    {
        $turmas = $this->pdo
            ->query("SELECT turma.* 
                    FROM turma 
                    INNER JOIN aluno_has_turma ON turma.id_turma = aluno_has_turma.turma_id_turma
                    WHERE aluno_id_aluno = ".$idAluno.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $turmas;
    }

    public function selecionaTurmasProfessor($idProfessor)
    {
        $turmas = $this->pdo
            ->query("SELECT * 
                    FROM turma 
                    WHERE professor_id_professor = ".$idProfessor.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $turmas;
    }

    public function selecionaTodasTurmas($idUsuario)
    {  
        $turma = new TurmaDAO();
        $tipoUsuario = $this->pdo
            ->query("SELECT tipo FROM usuario WHERE id_usuario = ".$idUsuario.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        if ($tipoUsuario[0]['tipo'] == 'aluno') {
            return $turma->selecionaTurmasAluno($idUsuario);
        } elseif ($tipoUsuario[0]['tipo'] == 'professor') {
            return $turma->selecionaTurmasProfessor($idUsuario);
        } else {
            return "";
        }
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
<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\EquipeModel;

class EquipeDAO extends Conexao
{

	public function __construct()
	{
		parent::__construct();
	}

    public function selecionaTodasEquipesPorJf($idJf)
    {
        $equipes = $this->pdo
            ->query("SELECT equipe.tamanho,equipe.id_lider,equipe.id_equipe
                    FROM equipe
                    INNER JOIN equipe_has_julgamento_de_fatos ON equipe.id_equipe = equipe_has_julgamento_de_fatos.equipe_id_equipe
                    WHERE equipe_has_julgamento_de_fatos.julgamento_de_fatos_id_jf = ".$idJf.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $equipes;
    }
    public function selecionaEquipePorAlunoEJf($idJf, $idAluno)
    {
        $equipes = $this->pdo
            ->query("SELECT equipe.tamanho,equipe.id_lider,equipe.id_equipe
                    FROM equipe
                    INNER JOIN equipe_has_julgamento_de_fatos ON equipe.id_equipe = equipe_has_julgamento_de_fatos.equipe_id_equipe
                    WHERE equipe_has_julgamento_de_fatos.julgamento_de_fatos_id_jf = ".$idJf.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $equipes;
    }
    public function selecionaTodosAlunosPorEquipes($idEquipe)
    {
        $alunos = $this->pdo
            ->query("SELECT aluno_id_aluno
                    FROM aluno_has_equipe
                    WHERE equipe_id_equipe = ".$idEquipe.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $alunos;
    }
    public function selecionaTodosAlunosSemEquipeDoJf($idTurma, $idJf)
    {
        $alunos = $this->pdo
            ->query("SELECT aluno.id_aluno, usuario.nome
                    FROM aluno
                    INNER JOIN usuario ON usuario.id_usuario = aluno.usuario_id_usuario1
                    INNER JOIN aluno_has_turma ON aluno_has_turma.aluno_id_aluno = aluno.id_aluno
                    WHERE aluno_has_turma.turma_id_turma = ".$idTurma."
                    AND aluno.id_aluno NOT IN (
                        SELECT aluno_has_equipe.aluno_id_aluno
                        FROM aluno_has_equipe
                        INNER JOIN equipe_has_julgamento_de_fatos ON equipe_has_julgamento_de_fatos.equipe_id_equipe = aluno_has_equipe.equipe_id_equipe
                        WHERE equipe_has_julgamento_de_fatos.julgamento_de_fatos_id_jf = ".$idJf.");")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $alunos;
    }
    public function selecionaLiderEquipe($idEquipe)
    {
        $lider = $this->pdo
            ->query("SELECT id_lider
                    FROM equipe
                    WHERE id_equipe = ".$idEquipe)
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $lider[0];
    }
    public function deletaEquipe($idEquipe): void
    {
        $equipeFato = $this->pdo
            ->prepare("DELETE FROM equipe_has_fato WHERE equipe_id_equipe = :equipe_id_equipe;");
        $equipeFato->execute([
            'equipe_id_equipe' => $idEquipe
        ]);

        $equipeJf = $this->pdo
            ->prepare("DELETE FROM equipe_has_julgamento_de_fatos WHERE equipe_id_equipe = :equipe_id_equipe;");
        $equipeJf->execute([
            'equipe_id_equipe' => $idEquipe
        ]);

        $equipeAluno = $this->pdo
            ->prepare("DELETE FROM aluno_has_equipe WHERE equipe_id_equipe = :equipe_id_equipe;");
        $equipeAluno->execute([
            'equipe_id_equipe' => $idEquipe
        ]);

        $equipe = $this->pdo
            ->prepare("DELETE FROM equipe WHERE id_equipe = :id_equipe;");
        $equipe->execute([
            'id_equipe' => $idEquipe
        ]);
    }
    public function deletaAlunoEquipe($idEquipe, $idAluno): void
    {
        $equipeAluno = $this->pdo
            ->prepare("DELETE FROM aluno_has_equipe WHERE equipe_id_equipe = :equipe_id_equipe
                AND aluno_id_aluno = :aluno_id_aluno;");
        $equipeAluno->execute([
            'equipe_id_equipe' => $idEquipe,
            'aluno_id_aluno' => $idAluno,
        ]);
    }
}
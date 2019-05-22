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
                    INNER JOIN aluno_has_equipe ON aluno_has_equipe.equipe_id_equipe = equipe.id_equipe
                    WHERE aluno_has_equipe.aluno_id_aluno = ".$idAluno." 
                    AND equipe_has_julgamento_de_fatos.julgamento_de_fatos_id_jf = ".$idJf.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $equipes;
    }
    public function selecionaEquipePorJf($idJf)
    {
        $equipes = $this->pdo
            ->query("SELECT equipe.tamanho,equipe.id_lider,equipe.id_equipe
                    FROM equipe
                    INNER JOIN equipe_has_julgamento_de_fatos ON equipe.id_equipe = equipe_has_julgamento_de_fatos.equipe_id_equipe
                    WHERE equipe_has_julgamento_de_fatos.julgamento_de_fatos_id_jf = ".$idJf.";")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $equipes;
    }
    public function selecionaTodosAlunosPorEquipe($idEquipe)
    {
        $alunos = $this->pdo
            ->query("SELECT aluno.id_aluno, usuario.nome
                    FROM aluno_has_equipe
                    INNER JOIN aluno ON aluno_has_equipe.aluno_id_aluno = aluno.id_aluno
                    INNER JOIN usuario ON aluno.usuario_id_usuario1 = usuario.id_usuario
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
    public function selecionaMaiorIdEquipe()
    {
        $queryId = "SELECT MAX(id_equipe) AS id FROM equipe";
        $id = $this->pdo->query($queryId);
        return $id->fetch(\PDO::FETCH_ASSOC);
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
    public function insereEquipe($data, $idLider): void
    {
        $equipe = $this->pdo
            ->prepare("INSERT INTO equipe VALUES(
                null,
                :nome,
                :tamanho,
                :id_lider,
                :julgamento_de_fatos_turma_id_turma
            );");

        $equipe->execute([
            'nome' => $data['nome'],
            'tamanho' => $data['tamanho'],
            'id_lider' => $idLider,
            'julgamento_de_fatos_turma_id_turma' => $data['idTurma'],
        ]);

        $idEquipe = $this->selecionaMaiorIdEquipe();

        $equipeJf = $this->pdo
            ->prepare("INSERT INTO equipe_has_julgamento_de_fatos VALUES(
                :equipe_id_equipe,
                :equipe_id_lider,
                :julgamento_de_fatos_id_jf
            );");

        $equipeJf->execute([
            'equipe_id_equipe' => $idEquipe['id'],
            'equipe_id_lider' => $idLider,
            'julgamento_de_fatos_id_jf' => $data['idJf']
        ]);

        $equipeAluno = $this->pdo
            ->prepare("INSERT INTO aluno_has_equipe VALUES(
                :aluno_id_aluno,
                :aluno_usuario_id_usuario,
                :aluno_id_lider,
                :equipe_id_equipe
            );");

        $equipeAluno->execute([
            'aluno_id_aluno' => $idLider,
            'aluno_usuario_id_usuario' => $data['idLider'],
            'aluno_id_lider' => $idLider,
            'equipe_id_equipe' => $idEquipe['id']
        ]);
    }
    public function insereAlunoEquipe($idAluno, $idUsuario, $idLider, $idEquipe)
    {
        $equipeAluno = $this->pdo
            ->prepare("INSERT INTO aluno_has_equipe VALUES(
                :aluno_id_aluno,
                :aluno_usuario_id_usuario,
                :aluno_id_lider,
                :equipe_id_equipe
            );");

        $equipeAluno->execute([
            'aluno_id_aluno' => $idAluno,
            'aluno_usuario_id_usuario' => $idUsuario,
            'aluno_id_lider' => $idLider,
            'equipe_id_equipe' => $idEquipe
        ]);
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
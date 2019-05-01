<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\JfModel;

class JfDAO extends Conexao
{

	public function __construct()
	{
		parent::__construct();
	}

    public function selecionaJf($idJf): array
    {
        
        $Jf = $this->pdo
            ->query("SELECT * FROM julgamento_de_fatos WHERE id_jf = ".$idJf.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $Jf;
    }

    public function selecionaTodosJfs($idTurma): array
    {  
        $Jf = $this->pdo
            ->query("SELECT * FROM julgamento_de_fatos WHERE turma_id_turma1 = ".$idTurma.";")
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $Jf;
    }

	public function insereJf(JfModel $Jf, $data): void
	{
        $statement = $this->pdo
            ->prepare("INSERT INTO julgamento_de_fatos VALUES(
                null,
                :nome,
                :tempo_max_exib,
                :status,
                :quantidade_alunos_equipe,
                :turma_id_turma1 
            );");

        $statement->execute([
            'nome' => $Jf->getNome(),
            'tempo_max_exib' => $Jf->getTempoMaxExib(),
            'quantidade_alunos_equipe' => $Jf->getQuantidadeMaxAlunosEquipe(),
            'status' => $Jf->getStatus(),
            'turma_id_turma1' => $data['idTurma']
        ]);
	}

    public function atualizaJf(JfModel $Jf, $data): void
    {
        $statement = $this->pdo
            ->prepare("UPDATE julgamento_de_fatos SET
                nome = ?,
                tempo_max_exib = ?,
                quantidade_alunos_equipe = ?,
                status = ?
            WHERE id_jf = ".$data['idJf'].";");

        $statement->execute([
            $Jf->getNome(),
            $Jf->getTempoMaxExib(),
            $Jf->getQuantidadeAlunosEquipe(),
            $Jf->getStatus()
        ]);
    }

    public function deletaJf($idJf): void
    {
        $equipeJf = $this->pdo
            ->prepare("DELETE FROM equipe_has_julgamento_de_fatos WHERE julgamento_de_fatos_id_jf = :julgamento_de_fatos_id_jf;");
        $equipeJf->execute([
            'julgamento_de_fatos_id_jf' => $idJf
        ]);

        $equipeJf = $this->pdo
            ->prepare("DELETE FROM fato WHERE julgamento_de_fatos_id_jf = :julgamento_de_fatos_id_jf;");
        $equipeJf->execute([
            'julgamento_de_fatos_id_jf' => $idJf
        ]);

        $jf = $this->pdo
            ->prepare("DELETE FROM julgamento_de_fatos WHERE id_jf = :id_jf;");
        $jf->execute([
            'id_jf' => $idJf
        ]);
    }

    public function selecionaMaiorId()
    {
        $queryId = "SELECT MAX(id_jf) AS id FROM julgamento_de_fatos";
        $id = $this->pdo->query($queryId);
        return $id->fetch(\PDO::FETCH_ASSOC);
    }

    public function insereFato($idJf, $fato, $idTurma): void
    {
        $statement = $this->pdo
            ->prepare("INSERT INTO fato VALUES(
                null,
                :ordem_jf,
                :texto_fato, 
                :topico,
                :resposta_correta,
                :julgamento_de_fatos_id_jf,
                :julgamento_de_fatos_turma_id_turma
            );");

        $statement->execute([
            'ordem_jf' => $fato['ordem'],
            'texto_fato' => $fato['texto'], 
            'topico' => $fato['topico'],
            'resposta_correta' => $fato['respostaCorreta'],
            'julgamento_de_fatos_id_jf' => $idJf,
            'julgamento_de_fatos_turma_id_turma' => $idTurma
        ]);
    }
}
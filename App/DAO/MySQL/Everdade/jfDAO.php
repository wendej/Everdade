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
                :turma_id_turma1  
            );");

        $statement->execute([
            'nome' => $Jf->getNome(),
            'tempo_max_exib' => $Jf->getTempoMaxExib(),
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
                status = ?
            WHERE id_jf = ".$data['idJf'].";");

        $statement->execute([
            $Jf->getNome(),
            $Jf->getTempoMaxExib(),
            $Jf->getStatus()
        ]);
    }
}
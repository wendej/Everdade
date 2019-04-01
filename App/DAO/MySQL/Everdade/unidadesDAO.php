<?php

namespace App\DAO\MySQL\Everdade;

class UnidadesDAO extends Conexao
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function selecionaUnidades(): array
    {
        
        $unidades = $this->pdo
            ->query('SELECT * FROM unidade;')
            ->fetchAll(\PDO::FETCH_ASSOC);

        return $unidades;
    }
}
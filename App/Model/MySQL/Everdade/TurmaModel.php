<?php

namespace App\Model\MySQL\Everdade;

final class TurmaModel
{
    
    private $idTurma;
    private $nome;
    private $disciplina;

    public function getIdTurma(): int
    {
        return $this->idTurma;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getDisciplina(): string
    {
        return $this->disciplina;
    }
    public function setDisciplina(string $disciplina): void
    {
        $this->disciplina = $disciplina;
    }

}

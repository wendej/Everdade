<?php

namespace App\Model\MySQL\Everdade;

final class UsuarioModel
{
    
    private $idUsuario;
    private $login;
    private $senha;
    private $email;
    private $nome;
    private $tipo;

    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    public function getLogin(): string
    {
        return $this->login;
    }
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }
}

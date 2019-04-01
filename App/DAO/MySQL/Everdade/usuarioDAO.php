<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\UsuarioModel;

class UsuarioDAO extends Conexao
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function insereUsuario(UsuarioModel $usuario): void
	{
        $statement = $this->pdo
            ->prepare("INSERT INTO usuario VALUES(
                null,
                :login,
                :senha,
                :email,
                :nome,
                :tipo
            );");

        $statement->execute([
            'login' => $usuario->getLogin(),
            'senha' => $usuario->getSenha(),
            'email' => $usuario->getEmail(),
            'nome' => $usuario->getNome(),
            'tipo' => $usuario->getTipo()
        ]);
	}

    public function logaUsuario(UsuarioModel $usuario)
    {
        $sql = "SELECT * 
                FROM usuario 
                WHERE login = '". $usuario->getLogin() ."' 
                AND senha = '". $usuario->getSenha() ."'";

        $res = $this->pdo->query($sql);

        return $res->fetch();
    }
}
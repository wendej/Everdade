<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\UsuarioModel;

class CadastroDAO extends Conexao
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function insereUsuario(UsuarioModel $usuario): void
	{
        $statement = $this->pdo
            ->prepare('INSERT INTO usuario VALUES(
                null,
                :login,
                :senha,
                :email,
                :nome,
                :tipo
            );');

        $statement->execute([
            'login' => $usuario->getLogin(),
            'senha' => $usuario->getSenha(),
            'email' => $usuario->getEmail(),
            'nome' => $usuario->getNome(),
            'tipo' => $usuario->getTipo()
        ]);
	}
}
<?php

namespace App\DAO\MySQL\Everdade;

use App\Model\MySQL\Everdade\UsuarioModel;

class UsuarioDAO extends Conexao
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function insereUsuario(UsuarioModel $usuario, $idCurso): void
	{
        $statement = $this->pdo
            ->prepare("INSERT INTO usuario VALUES(
                null,
                :login,
                :senha,
                :tipo,
                :nome,
                :email                
            );");

        $statement->execute([
            'login' => $usuario->getLogin(),
            'senha' => $usuario->getSenha(),
            'tipo' => $usuario->getTipo(),
            'nome' => $usuario->getNome(),
            'email' => $usuario->getEmail()
        ]);

        $id = selecionaMaiorId();

        if ($usuario->getTipo() === 'aluno') {
            $sql = $this->pdo
            ->prepare("INSERT INTO aluno VALUES(
                null,
                :id_curso,
                :id_usuario             
            );");

            $sql->execute([
                null,
                $idCurso,
                $id
            ]);

        } else {
            $sql = $this->pdo
            ->prepare("INSERT INTO professor VALUES(
                null,
                :id_usuario             
            );");

            $sql->execute([
                null,
                $id
            ]);
        }

	}

    public function logaUsuario(UsuarioModel $usuario)
    {
        $sql = "SELECT * 
                FROM usuario 
                WHERE login = '". $usuario->getLogin() ."' 
                AND senha = '". $usuario->getSenha() ."'";

        $res = $this->pdo->query($sql);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    public function selecionaMaiorId():int
    {
        $sql = "SELECT MAX(id_usuario) AS ultimo_id
                FROM usuario";

        $res = $this->pdo->query($sql);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }
}
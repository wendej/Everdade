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

        $queryId = "SELECT MAX(id_usuario) AS id FROM usuario";
        $id = $this->pdo->query($queryId);
        $id = $id->fetch(\PDO::FETCH_ASSOC);

        if ($usuario->getTipo() == 'aluno') {
            $sql = $this->pdo
            ->prepare("INSERT INTO aluno VALUES(
                null,
                :curso_id_curso1,
                :usuario_id_usuario1             
            );");

            $sql->execute([
                'curso_id_curso1' => $idCurso,
                'usuario_id_usuario1' => $id['id']
            ]);

        } else {
            $sql = $this->pdo
            ->prepare("INSERT INTO professor VALUES(
                null,
                :usuario_id_usuario             
            );");

            $sql->execute([
                'usuario_id_usuario' => $id['id']
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
        
    }
}
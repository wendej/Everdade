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

    public function selecionaMaiorId()
    {
        $queryId = "SELECT MAX(id_usuario) AS id FROM usuario";
        $id = $this->pdo->query($queryId);
        return $id->fetch(\PDO::FETCH_ASSOC);
    }

    public function insereAluno($idCurso, $idUsuario)
    {
       $statement = $this->pdo
            ->prepare("INSERT INTO aluno VALUES(
                null,
                :curso_id_curso1,
                :usuario_id_usuario1             
            );");

        $statement->execute([
            'curso_id_curso1' => $idCurso,
            'usuario_id_usuario1' => $idUsuario
        ]);
    }

    public function insereProfessor($idUsuario)
    {
         $statement = $this->pdo
            ->prepare("INSERT INTO professor VALUES(
                null,
                :usuario_id_usuario             
            );");

        $statement->execute([
            'usuario_id_usuario' => $idUsuario
        ]);
    }

    public function validaLoginUsuario(UsuarioModel $usuario)
    {
        $sql = "SELECT * 
                FROM usuario 
                WHERE login = '". $usuario->getLogin() ."';";

        $res = $this->pdo->query($sql);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }
}
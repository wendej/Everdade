<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\cadastroDAO;
use App\Model\MySQL\Everdade\UsuarioModel;

final class CadastroController
{
	public function getUsuario(Request $request, Response $response, array $args): Response
	{

		return $response;
	}
	
	public function insertUsuario(Request $request, Response $response, array $args): Response
	{
		$data = $request->getParsedBody();

		$usuarioDAO = new CadastroDAO();
		$usuario = new UsuarioModel();
		$usuario->setLogin($data['login']);
		$usuario->setSenha($data['senha']);
		$usuario->setEmail($data['email']);
		$usuario->setNome($data['nome']);
		$usuario->setTipo($data['tipo']);

		$usuarioDAO->insereUsuario($usuario);
		$response = $response->withJson([
			'message' => 'USUÁRIO CADASTRADO COM SUCESSO!'
		]);

		return $response;
	}

	public function updateUsuario(Request $request, Response $response, array $args): Response
	{

		return $response;
	}

	public function deleteUsuario(Request $request, Response $response, array $args): Response
	{
		
		return $response;
	}

}
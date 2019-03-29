<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\usuarioDAO;
use App\Model\MySQL\Everdade\UsuarioModel;

final class UsuarioController
{
	
	public function getUsuario(Request $request, Response $response, array $args): Response
	{

		return $response;
	}
	
	public function insertUsuario(Request $request, Response $response, array $args): Response
	{
		$usuarioDAO = new UsuarioDAO();
		$usuario = new UsuarioModel();

		$data = $request->getParsedBody();

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

	public function loginUsuario(Request $request, Response $response, array $args): Response
	{
		$usuarioDAO = new UsuarioDAO();
		$usuario = new UsuarioModel();

		$data = $request->getParsedBody();

		$usuario->setLogin($data['login']);
		$usuario->setSenha($data['senha']);

		$retorno = $usuarioDAO->logaUsuario($usuario);

		$response = $response->withJson([
				'message' => $retorno
			]);

		return $response;
	}

}
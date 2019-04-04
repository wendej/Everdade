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
		$usuario->setTipo($data['tipo']);
		$usuario->setNome($data['nome']);
		$usuario->setEmail($data['email']);

		$usuarioDAO->insereUsuario($usuario, $data['idCurso']);
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

		if (empty($retorno)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'usuário não encontrado'
			]);
		} else {
			$response = $response->withJson([
				'userData' => [
					$retorno
				]
			]);
		}

		return $response;
	}

}
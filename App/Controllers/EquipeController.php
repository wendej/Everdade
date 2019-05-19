<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\equipeDAO;
use App\DAO\MySQL\Everdade\turmaDAO;
use App\DAO\MySQL\Everdade\usuarioDAO;
use App\Model\MySQL\Everdade\EquipeModel;

final class equipeController
{
	
	public function getAlunosSemEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = New equipeDAO();

		$data = $request->getQueryParams();

		$alunos = $equipeDAO->selecionaTodosAlunosSemEquipeDoJf($data['idTurma'], $data['idJf']);
		if (empty($alunos)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Sem resultados'
			]);
		} else {
			$response = $response->withJson(['alunos' => $alunos]);
		}
		
		return $response;
	}
	
	public function insertEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = new EquipeDAO();
		$equipe = new EquipeModel();

		$data = $request->getParsedBody();

		$equipe->setNome($data['nome']);
		$equipe->setDisciplina($data['disciplina']);

		$equipeDAO->insereEquipe($equipe, $data);

		$idEquipe = $equipeDAO->selecionaMaiorId();

		foreach ($data['alunos'] as $idAluno) {
			$equipeDAO->insereAlunoEquipe($idAluno, $idEquipe['id']);
		}

		$response = $response->withJson([
			'message' => 'Equipe cadastrada com sucesso'
		]);
 
		return $response;
	}

	public function updateEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = new EquipeDAO();
		$equipe = new EquipeModel();

		$data = $request->getParsedBody();
		
		$equipe->setNome($data['nome']);
		$equipe->setDisciplina($data['disciplina']);

		$equipeDAO->atualizaEquipe($equipe, $data);

		$response = $response->withJson([
			'message' => 'Equipe atualizada com sucesso'
		]);

		return $response;
	}

	public function deleteEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = new EquipeDAO();
		$data = $request->getQueryParams();
		$equipe = $equipeDAO->selecionaEquipe($data['idEquipe']);

		if (!empty($equipe)) {
			$equipeDAO->deletaEquipe($data['idEquipe']);
			$response = $response->withJson([
				'message' => 'Equipe deletada com sucesso'
			]);
		} else {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Equipe não encontrada.'
			]);
		}
		
		return $response;
	}

	public function getAllEquipes(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = New equipeDAO();
		$data = $request->getQueryParams();
		$equipes = $equipeDAO->selecionaTodasEquipes($data["idUsuario"]);

		if (empty($equipes)) {
			$response = $response->withStatus(200);
			$response = $response->withJson([
				'message' => 'Nenhuma equipe encontrada'
			]);
		} else {
			$response = $response->withJson($equipes);
		}

		return $response;
	}

	public function getOutEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = New equipeDAO();

		$data = $request->getParsedBody();
		$idLider = $equipeDAO->selecionaLiderEquipe($data['idEquipe']);
		
		if ($idLider['id_lider'] == $data['idAluno']) {
			$equipeDAO->deletaEquipe($data['idEquipe']);
			$response = $response->withStatus(200);
			$response = $response->withJson([
				'message' => 'Equipe Deletada com sucesso'
			]);
		} else {
			$equipeDAO->deletaAlunoEquipe($data['idEquipe'], $data['idAluno']);
			$response = $response->withStatus(200);
			$response = $response->withJson([
				'message' => 'O aluno não faz mais parte dessa equipe'
			]);
		}
		return $response;
	}
	public function getEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = New equipeDAO();
		$usuarioDAO = New usuarioDAO();
		$data = $request->getQueryParams();

		$tipoUsuario = $usuarioDAO->selecionaTipoUsuario($data['idUsuario']);
		if ($tipoUsuario['tipo'] == 'aluno') {
			
			
		} elseif ($tipoUsuario['tipo'] == 'professor'){

		} else {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Usuário não encontrado'
			]);
		}

		return $response;
	}
}
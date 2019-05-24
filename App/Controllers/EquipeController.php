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
		$usuarioDAO = new usuarioDAO();
		$data = $request->getQueryParams();
		$idAluno= $usuarioDAO->selecionaAluno($data['idUsuario']);
		$alunosResult = $equipeDAO->selecionaTodosAlunosSemEquipeDoJf($data['idTurma'], $data['idJf']);
		$alunos = array();
		foreach ($alunosResult as $value) {
			if ($value['id_aluno'] != $idAluno['id_aluno']) {
				array_push($alunos, $value);
			}
		}
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
		$equipeDAO = new equipeDAO();
		$usuarioDAO = new usuarioDAO();

		$data = $request->getParsedBody();

		$idLider = $usuarioDAO->selecionaAluno($data['idLider']);

		$equipeDAO->insereEquipe($data, $idLider['id_aluno']);
		$idEquipe = $equipeDAO->selecionaMaiorIdEquipe();
		
		foreach ($data['alunos'] as $usuario) {
			$idAluno = $usuarioDAO->selecionaAluno($usuario);
			$equipeDAO->insereAlunoEquipe($idAluno['id_aluno'], $usuario, $idLider['id_aluno'], $idEquipe['id']);
		}
		$response = $response->withJson([
			'message' => 'Equipe gravada com sucesso!'
		]);

		return $response;
	}

	public function updateEquipe(Request $request, Response $response, array $args): Response
	{
	}

	public function deleteEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = new equipeDAO();
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

	public function getOutEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = New equipeDAO();

		$data = $request->getQueryParams();
		$idLider = $equipeDAO->selecionaLiderEquipe($data['idEquipe']);
		
		if ($idLider['id_lider'] == $data['idUsuario']) {
			$equipeDAO->deletaEquipe($data['idEquipe']);
			$response = $response->withStatus(200);
			$response = $response->withJson([
				'message' => 'Equipe Deletada com sucesso'
			]);
		} else {
			$equipeDAO->deletaAlunoEquipe($data['idEquipe'], $data['idUsuario']);
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
			$idAluno = $usuarioDAO->selecionaAluno($data['idUsuario']);
			$equipeAluno = $equipeDAO->selecionaEquipePorAlunoEJf($data['idJf'], $idAluno['id_aluno']);
			$alunosEquipe = $equipeDAO->selecionaTodosAlunosPorEquipe($equipeAluno[0]['id_equipe']);
			$response = $response->withJson([
				'equipe' => $equipeAluno, 'alunos' => $alunosEquipe
			]);
		} elseif ($tipoUsuario['tipo'] == 'professor'){
			$equipes = $equipeDAO->selecionaEquipePorJf($data['idJf']);
			$equipesAlunos = array();
			$contador = 0;
			foreach ($equipes as $equipe) {
				$equipesAlunos[$contador] = array(
					'equipe' => $equipe,
					'alunos' => $equipeDAO->selecionaTodosAlunosPorEquipe($equipe['id_equipe'])
				);
				$contador++;
			}
			$response = $response->withJson([
				$equipesAlunos
			]);
		} else {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Usuário não encontrado'
			]);
		}

		return $response;
	}

	public function insertFatoEquipe(Request $request, Response $response, array $args): Response
	{
		$equipeDAO = new equipeDAO();
		$usuarioDAO = new usuarioDAO();

		$data = $request->getParsedBody();
		$idLider = $usuarioDAO->selecionaAluno($data['idUsuario']);
		$idEquipe = $equipeDAO->selecionaEquipePorAlunoEJf($data['idJf'], $data['idUsuario']);
		if (!empty($idEquipe)) {
			foreach ($data['respostas'] as $resposta) {
				$equipeDAO->insereFatoEquipe($idEquipe[0]['id_equipe'], $resposta, $idLider['id_aluno']);
			}
			$response = $response->withJson([
		 		'message' => 'Respostas gravadas com sucesso!'
		 	]);
		} else {
			$response = $response->withJson([
		 		'message' => 'Equipe não encontrada!'
		 	]);
		}

		return $response;
	}
}
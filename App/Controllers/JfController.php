<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\jfDAO;
use App\DAO\MySQL\Everdade\equipeDAO;
use App\DAO\MySQL\Everdade\turmaDAO;
use App\DAO\MySQL\Everdade\usuarioDAO;
use App\Model\MySQL\Everdade\JfModel;

final class jfController
{
	
	public function getJf(Request $request, Response $response, array $args): Response
	{
		$jfDAO = New jfDAO();
		$equipeDAO = New equipeDAO();
		$usuarioDAO = New usuarioDAO();

		$data = $request->getQueryParams();

		$jf = $jfDAO->selecionaJf($data['idJf']);

		if (empty($jf)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'JF não encontrado'
			]);
		} else {

			$fatosJf = $jfDAO->selecionaFatosJf($data['idJf']);

			$tipoUsuario = $usuarioDAO->selecionaTipoUsuario($data['idUsuario']);

			if ($tipoUsuario['tipo'] == 'aluno') {
				$idAluno = $usuarioDAO->selecionaAluno($data['idUsuario']);

				$result = array();
				$contador = 0;

				$equipeAluno = $equipeDAO->selecionaEquipePorAlunoEJf($data['idJf'], $idAluno['id_aluno']);
				if (!empty($equipeAluno)) {
					$alunosEquipe = $equipeDAO->selecionaTodosAlunosPorEquipe($equipeAluno[0]['id_equipe']);
					$result[$contador] = array(
						'equipe' => $equipeAluno,
						'alunos' => $alunosEquipe
					);
				}
				$response = $response->withJson([
					'jf' => $jf, 'fatos' => $fatosJf, 'equipes' => $result
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
					'jf' => $jf, 'fatos' => $fatosJf, 'equipes' => $equipesAlunos
				]);
			} else {
				$response = $response->withStatus(403);
				$response = $response->withJson([
					'message' => 'Usuário não encontrado'
				]);
			}
		}

		return $response;
	}
	
	public function insertJf(Request $request, Response $response, array $args): Response
	{
		$jfDAO = new jfDAO();
		$jf = new JfModel();

		$data = $request->getParsedBody();

		$jf->setNome($data['nome']);
		$jf->setTempoMaxExib($data['tempoMaxExib']);
		$jf->setStatus($data['status']);
		$jf->setQuantidadeMaxAlunosEquipe($data['qntMaxAlunosEquipe']);

		$jfDAO->insereJf($jf, $data);

		$idJf = $jfDAO->selecionaMaiorId();

		foreach ($data['fatos'] as $fato) {
			$jfDAO->insereFato($idJf['id'], $fato, $data['idTurma']);
		}

		$response = $response->withJson([
			'message' => 'JF cadastrado com sucesso'
		]);

		return $response;
	}

	public function updateJf(Request $request, Response $response, array $args): Response
	{
		$jfDAO = new jfDAO();
		$jf = new JfModel();

		$data = $request->getParsedBody();

		$jf->setNome($data['nome']);
		$jf->setTempoMaxExib($data['tempoMaxExib']);
		$jf->setStatus($data['status']);
		$jf->setQuantidadeMaxAlunosEquipe($data['qntMaxAlunosEquipe']);

		$jfDAO->atualizaJf($jf, $data);
		$jfDAO->deletaFatosJf($data['idJf']);

		foreach ($data['fatos'] as $fato) {
			$jfDAO->insereFato($data['idJf'], $fato, $data['idTurma']);
		}
		
		$response = $response->withJson([
			'message' => 'JF atualizado com sucesso'
		]);

		return $response;
	}

	public function deleteJf(Request $request, Response $response, array $args): Response
	{
		$jfDAO = new JfDAO();
		$data = $request->getQueryParams();
		$jf = $jfDAO->selecionaJf($data['idJf']);

		if (!empty($jf)) {
			$jfDAO->deletaJf($data['idJf']);
			$response = $response->withJson([
				'message' => 'Jf deletado com sucesso.'
			]);
		} else {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Jf não encontrado.'
			]);
		}

		return $response;
	}

	public function getAllJfs(Request $request, Response $response, array $args): Response
	{
		$jfDAO = New jfDAO();
		$data = $request->getQueryParams();
		$jfs = $jfDAO->selecionaTodosJfs($data['idTurma']);

		if (empty($jfs)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Nenhum JF encontrado para está turma'
			]);
		} else {
			$response = $response->withJson($jfs);
		}

		return $response;
	}
}
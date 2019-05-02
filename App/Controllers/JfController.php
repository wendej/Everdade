<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\jfDAO;
use App\Model\MySQL\Everdade\JfModel;

final class jfController
{
	
	public function getJf(Request $request, Response $response, array $args): Response
	{
		$jfDAO = New jfDAO();
		$data = $request->getQueryParams();
		$jf = $jfDAO->selecionaJf($data['idJf']);
		$fatosJf = $jfDAO->selecionaFatosJf($data['idJf']);

		if (empty($jf)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'JF não encontrado'
			]);
		} else {
			$response = $response->withJson(['jf' => $jf, 'fatos' => $fatosJf]);
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
			$jfDAO->insereFato($idJf['id'], $fato, $data['idTurma']);
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
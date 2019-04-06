<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\cursosDAO;

final class CursoController
{
	public function getCursos(Request $request, Response $response, array $args): Response
	{
		$cursosDAO = New cursosDAO();
		$cursos = $cursosDAO->selecionaCursos();
		$response = $response->withJson($cursos);
		
		return $response;
	}

	public function getAlunosCursos(Request $request, Response $response, array $args): Response
	{
		$cursosDAO = New cursosDAO();
		$data = $request->getQueryParams();
		$alunosCurso = $cursosDAO->selecionaAlunosCurso($data['idCurso']);

		if (empty($alunosCurso)) {
			$response = $response->withStatus(403);
			$response = $response->withJson([
				'message' => 'Não há alunos cadastrados para este curso!'
			]);
		} else {
			$response = $response->withJson($alunosCurso);
		}
		
		return $response;
	}

}
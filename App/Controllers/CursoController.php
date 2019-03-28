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

}
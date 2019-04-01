<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Everdade\unidadesDAO;

final class UnidadeController
{
    public function getUnidades(Request $request, Response $response, array $args): Response
    {
        $unidadesDAO = New unidadesDAO();
        $unidades = $unidadesDAO->selecionaUnidades();
        $response = $response->withJson($unidades);
        
        return $response;
    }

}
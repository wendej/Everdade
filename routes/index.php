<?php 

use function src\{
    slimConfiguration
};

use App\Controllers\CursoController;
use App\Controllers\CadastroController;

$app = new \Slim\App(slimConfiguration());

// ==================================================
$app->get('/cursos', CursoController::class . ':getCursos');

$app->get('/cadastro', CadastroController::class . ':getUsuario');
$app->post('/cadastro', CadastroController::class . ':insertUsuario');
$app->put('/cadastro', CadastroController::class . ':updateUsuario');
$app->delete('/cadastro', CadastroController::class . ':deleteUsuario');
// ==================================================

$app->run();
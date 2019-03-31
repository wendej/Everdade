<?php 

use function src\{
    slimConfiguration,
    basicAuth
};
use Tuupola\Middleware\CorsMiddleware;
use App\Controllers\CursoController;
use App\Controllers\UsuarioController;

$app = new \Slim\App(slimConfiguration());

$app
->group('', function() use ($app) {
	$app->get('/cursos', CursoController::class . ':getCursos');
	$app->get('/usuario/seleciona', UsuarioController::class . ':getUsuario');
	$app->post('/usuario/cadastro', UsuarioController::class . ':insertUsuario');
	$app->put('/usuario/atualizar', UsuarioController::class . ':updateUsuario');
	$app->delete('/usuario/apagar', UsuarioController::class . ':deleteUsuario');
	$app->post('/usuario/login', UsuarioController::class . ':loginUsuario');
})
->add(new Tuupola\Middleware\CorsMiddleware([
  "origin" => ["*"],
  "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTION"],
  "headers.allow" => ["Origin", "Content-Type", "Authorization", "Accept", "ignoreLoadingBar", "X-Requested-With", "Access-Control-Allow-Origin"],
  "headers.expose" => [],
  "credentials" => false,
  "cache" => 0,
  ]));

$app->run();
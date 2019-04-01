<?php 

use function src\{
    slimConfiguration,
    basicAuth
};
use Tuupola\Middleware\CorsMiddleware;
use App\Controllers\CursoController;
use App\Controllers\UsuarioController;

$app = new \Slim\App(slimConfiguration());

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
    
$app->group('', function() use ($app) {
	$app->get('/cursos', CursoController::class . ':getCursos');
	$app->get('/usuario/seleciona', UsuarioController::class . ':getUsuario');
	$app->post('/usuario/cadastro', UsuarioController::class . ':insertUsuario');
	$app->put('/usuario/atualizar', UsuarioController::class . ':updateUsuario');
	$app->delete('/usuario/apagar', UsuarioController::class . ':deleteUsuario');
	$app->post('/usuario/login', UsuarioController::class . ':loginUsuario');
});

$app->run();
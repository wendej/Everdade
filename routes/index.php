<?php 

use function src\{
    slimConfiguration,
    basicAuth
};

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
->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
})
->add(basicAuth());

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();
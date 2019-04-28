<?php 

use function src\{
    slimConfiguration,
    basicAuth
};
use Tuupola\Middleware\CorsMiddleware;
use App\Controllers\CursoController;
use App\Controllers\UnidadeController;
use App\Controllers\UsuarioController;
use App\Controllers\TurmaController;
use App\Controllers\JfController;

$app = new \Slim\App(slimConfiguration());

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->group('', function() use ($app) {

    /* Curso */
	$app->get('/cursos', CursoController::class . ':getCursos');
    $app->get('/cursos/alunos', CursoController::class . ':getAlunosCursos');

    /* Unidade */
    $app->get('/unidades', UnidadeController::class . ':getUnidades');

    /* Usuário */
	$app->get('/usuario/seleciona', UsuarioController::class . ':getUsuario');
	$app->post('/usuario/cadastro', UsuarioController::class . ':insertUsuario');
	$app->put('/usuario/atualizar', UsuarioController::class . ':updateUsuario');
	$app->delete('/usuario/apagar', UsuarioController::class . ':deleteUsuario');
    $app->post('/usuario/login', UsuarioController::class . ':loginUsuario');

    /* Turma */
    $app->get('/turma', TurmaController::class . ':getAllTurmas');
    $app->get('/turma/seleciona', TurmaController::class . ':getTurma');
	$app->post('/turma/cadastro', TurmaController::class . ':insertTurma');
    $app->put('/turma/editar', TurmaController::class . ':updateTurma');
    $app->delete('/turma/apagar', TurmaController::class . ':deleteTurma');

    /* JF */
    $app->get('/jf', JfController::class . ':getAllJfs');
    $app->get('/jf/seleciona', JfController::class . ':getJf');
    $app->post('/jf/cadastro', JfController::class . ':insertJf');
    $app->put('/jf/editar', JfController::class . ':updateJf');
    $app->delete('/jf/apagar', JfController::class . ':deleteJf');
});

$app->run();
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/teste', 'Home::teste');

// Shield Auth routes
service('auth')->routes($routes);
// service('auth')->routes($routes, ['except' => ['login', 'register']]); // Esta linha está duplicada e não faz sentido com a de cima. Mantenha apenas a de cima.


// =================================================================================
// GRUPO PRINCIPAL: Rotas protegidas. Exige que o usuário esteja logado.
// O filtro 'session' do Shield garante que o usuário esteja autenticado.
// =================================================================================
$routes->group('sys', ['filter' => 'session'], static function ($routes) {

    //==============================================================
    // Rotas de Cursos - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('cursos', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'CursoController::index');
        $routes->post('create', 'CursoController::create');
        $routes->post('update', 'CursoController::update');
        $routes->post('delete', 'CursoController::delete');
    });

    //==============================================================
    // Rotas de Turmas - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('turmas', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'TurmaController::index');
        $routes->post('create', 'TurmaController::create');
        $routes->post('update', 'TurmaController::update');
        $routes->post('delete', 'TurmaController::delete');
        $routes->post('import', 'TurmaController::import');
        $routes->post('importProcess', 'TurmaController::importProcess'); 
    });

    //==============================================================
    // Rotas de Controle de Refeições - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('controle-refeicoes', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'ControleRefeicoesController::index');
        $routes->post('salvar', 'ControleRefeicoesController::salvar');
        $routes->post('atualizar', 'ControleRefeicoesController::atualizar');
        $routes->post('deletar', 'ControleRefeicoesController::deletar');
    });

    //==============================================================
    // Rotas de Alunos - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('alunos', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'AlunoController::index');
        $routes->post('create', 'AlunoController::create');
        $routes->get('edit/(:any)', 'AlunoController::edit/$1'); 
        $routes->put('update', 'AlunoController::update');
        $routes->delete('delete', 'AlunoController::delete');
        $routes->post('import', 'AlunoController::import');
        $routes->post('importProcess', 'AlunoController::importProcess');
        
        //provisorio
        $routes->get('sendEmail', 'AlunoController::enviarEmail'); 
    });

    //==============================================================
    // Rotas de Usuários - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('usuarios', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'UsuarioController::index');
        $routes->post('criar', 'UsuarioController::store');
        $routes->put('atualizar/(:num)', 'UsuarioController::update/$1');
        $routes->delete('deletar/(:num)', 'UsuarioController::delete/$1');
    });

    //==============================================================
    // Rotas de Agendamento - Acesso para 'admin' E/OU 'developer'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer' (Operador OR)
    //==============================================================
    $routes->group('agendamento', ['filter' => 'group:admin,developer'], static function ($routes) {
        $routes->get('', 'AgendamentoController::index');
        $routes->post('admin/create', 'AgendamentoController::create');
        $routes->get('admin/getAlunosByTurma/(:num)', 'AgendamentoController::getAlunosByTurma/$1');
        $routes->post('admin/update', 'AgendamentoController::update');
        $routes->post('admin/delete', 'AgendamentoController::delete');
    });

    //==============================================================
    // Rotas de Solicitação de Refeições - Acesso para 'aluno' E/OU 'solicitante'
    // CORREÇÃO: Usando a sintaxe 'group:aluno,solicitante' (Operador OR)
    //==============================================================
    $routes->group('solicitacoes', ['filter' => 'group:aluno,solicitante'], static function ($routes) {
        $routes->get('', 'SolicitacaoRefeicoesController::index');
        $routes->post('create', 'SolicitacaoRefeicoesController::create');
        $routes->post('update', 'SolicitacaoRefeicoesController::update');
        $routes->post('delete', 'SolicitacaoRefeicoesController::delete');
    });

    //==============================================================
    // Rotas de Restaurante - Acesso para 'restaurante'
    // CORREÇÃO: Uso correto de filtro único.
    //==============================================================
    $routes->group('restaurante', ['filter' => 'group:restaurante'], static function ($routes) {
        $routes->post('registrar-servida', 'RefeicaoController::registrarServida');
    });

    //==============================================================
    // Rotas de Relatórios - Acesso para 'admin' E/OU 'developer' E/OU 'restaurante'
    // CORREÇÃO: Usando a sintaxe 'group:admin,developer,restaurante' (Operador OR)
    //==============================================================
    $routes->group('relatorios', ['filter' => 'group:admin,developer,restaurante'], static function ($routes) {
        $routes->get('', 'RelatorioController::index');
        $routes->get('previstos', 'RelatorioController::refeicoesPrevistas');
        $routes->get('servidos', 'RelatorioController::refeicoesServidas');
        $routes->get('nao-servidos', 'RelatorioController::refeicoesNaoServidas');
        $routes->get('confirmados', 'RelatorioController::confirmados');
    });

    //==============================================================
    // Rotas do Admin para o gerenciamento de usuários - Acesso para 'admin'
    // CORREÇÃO: Uso correto de filtro único.
    //==============================================================
    $routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'AdminController::index'); // Página inicial da admin
        $routes->post('alterar-grupo', 'AdminController::alterarGrupoUsuario'); // Atribuir a um grupo de usuários
        $routes->post('atualizar-usuario', 'AdminController::atualizarUsuario'); 
        $routes->post('resetar-senha', 'AdminController::resetarSenha'); // Atualizar senha
        $routes->post('desativar-usuario', 'AdminController::desativarUsuario');
        $routes->post('registrar-usuario', 'AdminController::registrarUsuario');
        $routes->get('usuarios-inativos', 'AdminController::usuariosInativos');
        $routes->post('reativar-usuario', 'AdminController::reativarUsuario');
        $routes->post('excluir-permanentemente', 'AdminController::excluirPermanentemente');
    });
});

//Rota do Webhook (Fora do grupo protegido)
$routes->post('webhook/response', 'WebhookController::response');
<?php

namespace Config;

use Config\Services;
use CodeIgniter\Router\RouteCollection;

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes = Services::routes();

// --------------------------------------------------------------------
// Rotas Públicas (livres para não logados)
// --------------------------------------------------------------------
$routes->get('/',                              'LojaController::publico');
$routes->get('produtos/detalhes/(:num)',       'LojaController::detalhes/$1');
$routes->get('publico/lojaVendedor/(:num)',    'LojaController::lojaVendedor/$1');

// Home e página estática
$routes->get('sobre',           'HomeController::sobre');
$routes->get('contato',         'HomeController::contato');
$routes->post('contato/submit', 'HomeController::submitContact');

// Autenticação e cadastro
$routes->get('cadastrar',      'AuthController::cadastrar');
$routes->post('salvarUsuario', 'AuthController::salvarUsuario');
$routes->get('login',          'AuthController::login');
$routes->post('autenticar',    'AuthController::autenticar');
$routes->get('logout',         'AuthController::logout');

// Esqueci senha / Redefinir senha
$routes->get('esqueciasenha',          'AuthController::esqueciASenha');
$routes->post('esqueciasenha',         'AuthController::enviarTokenSenha');
$routes->get('redefinirsenha/(:any)',  'AuthController::alterarSenha/$1');
$routes->post('redefinirsenha/(:any)', 'AuthController::salvarSenha/$1');

// --------------------------------------------------------------------
// Rotas Protegidas (aplicam globalmente o filtro AuthAdmin via Filters.php)
// --------------------------------------------------------------------

// Perfil do usuário
$routes->get('perfil',              'PerfilController::index');
$routes->post('perfil/update',      'PerfilController::update');
$routes->post('perfil/updateSenha', 'PerfilController::updateSenha');
$routes->post('perfil/updateFoto',  'PerfilController::updateFoto');

// Gerenciamento de Fotos de Produto
$routes->get('fotosproduto/(:num)',         'FotoProdutoController::index/$1');
$routes->post('fotosproduto/upload/(:num)',  'FotoProdutoController::upload/$1');
$routes->post('fotosproduto/uploadAjax/(:num)', 'FotoProdutoController::uploadAjax/$1');
$routes->get('fotosproduto/definircapa/(:num)', 'FotoProdutoController::definircapa/$1');
$routes->get('fotosproduto/delete/(:num)',     'FotoProdutoController::delete/$1');

// Carrinho (somente usuários logados)
$routes->match(['get','post'], 'loja/adicionarAoCarrinho/(:num)', 'LojaController::adicionarAoCarrinho/$1');
$routes->get('loja/carrinho',                   'LojaController::carrinho');
$routes->get('loja/removerDoCarrinho/(:num)',   'LojaController::removerDoCarrinho/$1');
$routes->post('loja/removerSelecionados',       'LojaController::removerSelecionados');
$routes->post('loja/finalizarCompra',           'LojaController::finalizarCompra');

// --------------------------------------------------------------------
// Administração de Produtos (só admin/admin_geral)
// --------------------------------------------------------------------
$routes->group('produtos', ['filter' => 'authAdmin'], function(RouteCollection $routes) {
    $routes->get('/',             'ProdutoController::index');
    $routes->get('create',        'ProdutoController::create');
    $routes->post('store',        'ProdutoController::store');
    $routes->get('edit/(:num)',   'ProdutoController::edit/$1');
    $routes->post('update/(:num)','ProdutoController::update/$1');
    $routes->get('delete/(:num)', 'ProdutoController::delete/$1');
});

// --------------------------------------------------------------------
// Administração de Categorias (só admin/admin_geral)
// --------------------------------------------------------------------
$routes->group('categorias', ['filter' => 'authAdmin'], function(RouteCollection $routes) {
    $routes->get('/',             'CategoriasController::index');
    $routes->get('create',        'CategoriasController::create');
    $routes->post('store',        'CategoriasController::store');
    $routes->get('edit/(:num)',   'CategoriasController::edit/$1');
    $routes->post('update/(:num)','CategoriasController::update/$1');
    $routes->get('delete/(:num)', 'CategoriasController::delete/$1');
});

// --------------------------------------------------------------------
// Dashboard Super Admin (só admin_geral)
// --------------------------------------------------------------------
$routes->get('dashboard',                'DashboardController::index', ['filter' => 'authAdmin']);
$routes->get('dashboard/approve/(:num)', 'DashboardController::approve/$1', ['filter' => 'authAdmin']);
$routes->get('dashboard/reject/(:num)',  'DashboardController::reject/$1', ['filter' => 'authAdmin']);
$routes->post('dashboard/deleteUser/(:num)', 'DashboardController::deleteUser/$1', ['filter' => 'authAdmin']);

// --------------------------------------------------------------------
// Relatório de Vendas (admin, admin_geral e vendedores logados)
// --------------------------------------------------------------------
$routes->get('produtos/relatorio', 'ProdutoController::relatorioVendas', ['filter' => 'authAdmin']);

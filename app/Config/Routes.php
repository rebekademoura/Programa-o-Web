<?php

use CodeIgniter\Router\RouteCollection;

// método -> url -> Controller -> Função
$routes->get('/', 'HomeController::index');

// Criar uma página sobre/contato
$routes->get('/sobre', 'HomeController::sobre');
$routes->get('/contato', 'HomeController::contato');
$routes->post('/contato/submit', 'HomeController::submitContact');

$routes->get('/testebanco', 'BancoTesteController::index');
$routes->get('/listar', 'HomeController::Listar');
$routes->get('buscar', 'BancoTesteController::buscar');
$routes->get('contatomodel', 'HomeController::submitContact');

$routes->group('produtos', ['filter'=>'authAdmin'], function($routes){
    $routes->get('/', 'ProdutoController::index');
    $routes->get('create', 'ProdutoController::create');
    $routes->post('store', 'ProdutoController::store');
    $routes->get('edit/(:num)', 'ProdutoController::edit/$1');
    $routes->post('update/(:num)', 'ProdutoController::update/$1');
    $routes->get('delete/(:num)', 'ProdutoController::delete/$1');
}); // ← Correção aqui

$routes->group('erros', ['filter'=>'AuthMiddleware'], function($routes){
    $routes->get('erros', 'AuthController::erros');
}); // ← Correção aqui também

// Login e cadastro de usuários
    $routes->get('cadastrar', 'AuthController::cadastrar');
    $routes->post('salvarUsuario', 'AuthController::salvarUsuario');
    $routes->get('login', 'AuthController::login');
    $routes->post('autenticar', 'AuthController::autenticar');
    $routes->get('logout', 'AuthController::logout');

//esqueci senha
    //mostra o formulário
    $routes->get('esqueciasenha', 'AuthController::esqueciASenha');
    //envia o formulário
    $routes->post('esqueciasenha', 'AuthController::enviarTokenSenha');

    //redefinir senha
    $routes->get('redefinirsenha/(:any)', 'AuthController::alterarSenha/$1');
    $routes->post('redefinirsenha/(:any)', 'AuthController::salvarSenha/$1');


//rotas para edição de perfil do usuário
    $routes->get('perfil','PerfilController::index',['filter'=>'authAdmin']); //rota para carregar a página de edição do perfil
    $routes->post('perfil/update','PerfilController::update',['filter'=>'authAdmin']); //rota para carregar o update: username e email
    $routes->post('perfil/updateSenha','PerfilController::updateSenha',['filter'=>'authAdmin']); //rota para carregar o update: nova senha
    $routes->post('perfil/updateFoto','PerfilController::updateFoto',['filter'=>'authAdmin']); //rota para carregar o update: foto





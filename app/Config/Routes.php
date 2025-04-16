<?php

use CodeIgniter\Router\RouteCollection;


//metodo -> url -> Controller -> Função
$routes->get('/', 'HomeController::index');

//CRIAR UMA PÁGINA SOBRE/CONTATO 
//CRIAR A ROTA

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
});

//login e cadastro de usuários
$routes->get('cadastrar', 'AuthController::cadastrar');// Mostrar o formulário de cadastro
$routes->post('salvarUsuario', 'AuthController::salvarUsuario');// Enviar os dados do formulário de cadastro (POST)

$routes->get('login', 'AuthController::login');// Mostrar o formulário de login
$routes->post('autenticar', 'AuthController::autenticar');// Autenticar o usuário (POST)

$routes->get('logout', 'AuthController::logout');// Fazer logout


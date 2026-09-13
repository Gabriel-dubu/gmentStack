<?php

declare(strict_types=1);

use App\Controllers\Admin\LeadController as AdminLeadController;
use App\Controllers\LeadController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PageController as AdminPageController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

/** @var Router $router */

// ---- Rotas públicas ----
$router->get('/', [PageController::class, 'home']);
$router->get('/produtos/{id}', [HomeController::class, 'produto']);
$router->post('/{slug}/lead', [LeadController::class, 'store']);

// ---- Painel administrativo ----

// Alguém digitando só "/admin" (sem /login ou /dashboard) é mandado pro
// lugar certo dependendo se já está autenticado ou não.
$router->get('/admin', function (): void {
    $target = \App\Core\Auth::check() ? '/admin/dashboard' : '/admin/login';
    header('Location: ' . \App\Core\Config::get('base_path', '') . $target);
    exit;
});

$router->group('/admin', [], function (Router $router) {
    $router->get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
    $router->post('/login', [AuthController::class, 'login'], [GuestMiddleware::class]);
    $router->post('/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);

    $router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

    // CRUD de páginas de conteúdo (protegido por AuthMiddleware)
    $router->get('/pages', [AdminPageController::class, 'index'], [AuthMiddleware::class]);
    $router->get('/pages/create', [AdminPageController::class, 'create'], [AuthMiddleware::class]);
    $router->post('/pages', [AdminPageController::class, 'store'], [AuthMiddleware::class]);
    $router->get('/pages/{id}/edit', [AdminPageController::class, 'edit'], [AuthMiddleware::class]);
    // HTML puro não manda PUT/DELETE; usamos POST com sufixo na URL.
    $router->post('/pages/{id}', [AdminPageController::class, 'update'], [AuthMiddleware::class]);
    $router->post('/pages/{id}/delete', [AdminPageController::class, 'destroy'], [AuthMiddleware::class]);

    //Rotas de admin dos LEADS
    $router->get('/leads', [AdminLeadController::class, 'index'], [AuthMiddleware::class]);
    $router->post('/leads/{id}/delete', [AdminLeadController::class, 'destroy'], [AuthMiddleware::class]);
});

// ---- Catch-all público para páginas cadastradas via admin ----
// IMPORTANTE: precisa ser a ÚLTIMA rota GET registrada. O router testa as
// rotas na ordem em que foram adicionadas, então tudo que é mais específico
// (acima) sempre é resolvido primeiro. Como o padrão é "um segmento sem
// barra", ele nunca colide com "/admin/..." ou "/produtos/{id}".
$router->get('/{slug}', [PageController::class, 'show']);

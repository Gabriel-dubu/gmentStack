<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $groupMiddleware = [];
    private string $groupPrefix = '';

    public function get(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    // Agrupa rotas sob um prefixo e uma lista de middlewares comuns
    // (ex: tudo em /admin/* passando por AuthMiddleware).
    public function group(string $prefix, array $middleware, callable $callback): void
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        $this->groupPrefix .= $prefix;
        $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    private function addRoute(string $method, string $path, callable|array $handler, array $middleware): void
    {
        $fullPath = rtrim($this->groupPrefix . $path, '/');
        $fullPath = $fullPath === '' ? '/' : $fullPath;

        $this->routes[$method][] = [
            'path' => $fullPath,
            'handler' => $handler,
            'middleware' => array_merge($this->groupMiddleware, $middleware),
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = rtrim($uri, '/');
        $uri = $uri === '' ? '/' : $uri;

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            // Converte {id} em um grupo de captura, escapando o resto do padrão
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $params = array_map('urldecode', $matches);

                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();

                    // Um middleware que retorna false já cuidou da resposta
                    // (redirect, 403, etc.) — o dispatch para por aqui.
                    if ($middleware->handle() === false) {
                        return;
                    }
                }

                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        $this->handleNotFound($uri);
    }

    /**
     * Nenhuma rota bateu com a URI. O comportamento muda conforme a área:
     *
     * - Dentro de /admin: mostramos um 404 de verdade. Não faz sentido
     *   "advinhar" para onde mandar alguém que errou uma URL dentro da
     *   área logada — melhor deixar claro que a página não existe.
     *
     * - Fora de /admin: como as páginas públicas são cadastradas
     *   dinamicamente pelo admin (e o slug delas pode mudar/ser removido),
     *   uma URL pública quebrada manda o visitante de volta pra home em
     *   vez de mostrar um 404 "cru".
     */
    private function handleNotFound(string $uri): void
    {
        if ($uri === '/admin' || str_starts_with($uri, '/admin/')) {
            http_response_code(404);
            $this->renderNotFound();
            return;
        }

        header('Location: ' . Config::get('base_path', '') . '/');
        exit;
    }

    private function callHandler(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$controllerClass, $methodName] = $handler;
            $controller = new $controllerClass();
            $controller->$methodName(...$params);
            return;
        }

        $handler(...$params);
    }

    private function renderNotFound(): void
    {
        require __DIR__ . '/../Views/errors/404.php';
    }
}

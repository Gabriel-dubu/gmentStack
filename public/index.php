<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use App\Core\Request;
use App\Core\Router;

$router = new Router();
require __DIR__ . '/../routes/web.php';

$router->dispatch(Request::method(), Request::uri());

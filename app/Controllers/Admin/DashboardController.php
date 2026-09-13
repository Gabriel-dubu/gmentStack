<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->view('admin/dashboard', [
            'user' => Auth::user(),
        ]);
    }
}

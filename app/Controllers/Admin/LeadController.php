<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Models\Lead;

class LeadController extends Controller
{
    public function index(): void
    {
        $this->view('admin/leads/index', [
            'leads' => Lead::all(),
            'success' => Session::flash('success'),
        ]);
    }

    public function destroy(string $id): void
    {
        $token = is_string(Request::input('_csrf_token')) ? Request::input('_csrf_token') : null;

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('/admin/leads');
        }

        Lead::delete((int) $id);

        Session::flash('success', 'Lead removido.');
        $this->redirect('/admin/leads');
    }
}
<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('admin/login', [
            'error' => Session::flash('error'),
        ]);
    }

    public function login(): void
    {
        $token = is_string(Request::input('_csrf_token')) ? Request::input('_csrf_token') : null;

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Sessão expirada ou token inválido. Tente novamente.');
            $this->redirect('/admin/login');
        }

        $email = filter_var(Request::input('email', ''), FILTER_VALIDATE_EMAIL);
        $password = (string) Request::input('password', '');

        if (!$email || $password === '') {
            Session::flash('error', 'Preencha e-mail e senha corretamente.');
            $this->redirect('/admin/login');
        }

        // Mensagem genérica de propósito: não revela se o e-mail existe ou não.
        if (!Auth::attempt($email, $password)) {
            Session::flash('error', 'Credenciais inválidas, ou muitas tentativas. Tente novamente em alguns minutos.');
            $this->redirect('/admin/login');
        }

        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        $token = is_string(Request::input('_csrf_token')) ? Request::input('_csrf_token') : null;

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('/admin/login');
        }

        Auth::logout();
        $this->redirect('/admin/login');
    }
}

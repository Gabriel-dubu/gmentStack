<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Models\Lead;
use App\Models\Page;

class LeadController extends Controller
{
    public function store(string $slug): void
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $token = is_string(Request::input('_csrf_token')) ? Request::input('_csrf_token') : null;

        if (!Csrf::validate($token)) {
            Session::flash('lead_error', 'Sessão expirada, tente novamente.');
            $this->redirect('/' . $slug);
        }

        $name = trim((string) Request::input('name', ''));
        $phone = trim((string) Request::input('phone', ''));
        $company = trim((string) Request::input('company', ''));
        $email = trim((string) Request::input('email', ''));
        $situation = trim((string) Request::input('situation', ''));
        $message = trim((string) Request::input('message', ''));

        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Informe seu nome.';
        }

        if (!preg_match('/^[0-9()+\-\s]{8,20}$/', $phone)) {
            $errors['phone'] = 'Informe um telefone/WhatsApp válido.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail inválido.';
        }

        if ($errors !== []) {
            Session::flash('lead_errors', $errors);
            Session::flash('lead_old', [
                'name' => $name,
                'phone' => $phone,
                'company' => $company,
                'email' => $email,
                'situation' => $situation,
                'message' => $message,
            ]);
            $this->redirect('/' . $slug);
        }

        Lead::create([
            'page_id' => $page['id'],
            'name' => $name,
            'phone' => $phone,
            'company' => $company !== '' ? $company : null,
            'email' => $email !== '' ? $email : null,
            'situation' => $situation !== '' ? $situation : null,
            'message' => $message !== '' ? $message : null,
        ]);

        Session::flash('lead_success', 'Recebemos seus dados! Em breve entraremos em contato.');
        $this->redirect('/' . $slug);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;
use App\Models\PageFaq;
use App\Models\PageSection;
use App\Models\PageService;
use App\Models\PageStep;
use App\Models\PageTeamMember;

class PageController extends Controller
{
    private const KNOWN_TEMPLATES = ['landing', 'profissional'];

    public function home(): void
    {
        $page = Page::findBySlug('inicio');

        if (!$page) {
            $this->view('home/placeholder');
            return;
        }

        $this->renderPage($page);
    }

    public function show(string $slug): void
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $this->renderPage($page);
    }

    private function renderPage(array $page): void
    {
        $template = in_array($page['template'], self::KNOWN_TEMPLATES, true) ? $page['template'] : 'institucional';
        $pageId = (int) $page['id'];

        $this->view('home/' . $template, [
            'page' => $page,
            'sections' => PageSection::allByPage($pageId),
            'services' => PageService::allByPage($pageId),
            'team' => PageTeamMember::allByPage($pageId),
            'faqs' => PageFaq::allByPage($pageId),
            'steps' => PageStep::allByPage($pageId),
            'leadErrors' => Session::flash('lead_errors') ?? [],
            'leadOld' => Session::flash('lead_old') ?? [],
            'leadSuccess' => Session::flash('lead_success'),
        ]);
    }
}

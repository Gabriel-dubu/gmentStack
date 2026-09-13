<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Core\Upload;
use App\Models\Page;
use App\Models\PageFaq;
use App\Models\PageSection;
use App\Models\PageService;
use App\Models\PageStep;
use App\Models\PageTeamMember;
use RuntimeException;

class PageController extends Controller
{
    private const RESERVED_SLUGS = ['admin', 'produtos', 'assets', 'uploads', 'login', 'logout', 'dashboard'];
    private const TEMPLATES = ['institucional', 'landing', 'profissional'];
    private const HERO_LAYOUTS = ['centered', 'card-right', 'card-left'];
    private const LAYOUTS = [
        'text-only',
        'image-left',
        'image-right',
        'checklist',
        'checklist-list',
        'pain-card',
        'checklist-list-right',
        'checklist-list-left',
    ];

    private function uploadDir(): string
    {
        return __DIR__ . '/../../../public/uploads/pages';
    }

    public function index(): void
    {
        $this->view('admin/pages/index', [
            'pages' => Page::all(),
            'success' => Session::flash('success'),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/pages/create', [
            'errors' => Session::flash('errors') ?? [],
            'old' => Session::flash('old') ?? [],
        ]);
    }

    public function store(): void
    {
        $this->checkCsrf('/admin/pages/create');

        [$data, $errors] = $this->validate(Request::all());

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', Request::all());
            $this->redirect('/admin/pages/create');
        }

        try {
            $data['image'] = Upload::image('image', $this->uploadDir());
            $data['logo'] = Upload::image('logo', $this->uploadDir());
        } catch (RuntimeException $e) {
            Session::flash('errors', ['image' => $e->getMessage()]);
            Session::flash('old', Request::all());
            $this->redirect('/admin/pages/create');
        }

        $pageId = Page::create($data);

        $all = Request::all();
        $this->saveSections($pageId, $all['sections'] ?? [], $_FILES['sections'] ?? null);
        $this->saveServices($pageId, $all['services'] ?? []);
        $this->saveTeam($pageId, $all['team'] ?? [], $_FILES['team'] ?? null);
        $this->saveFaqs($pageId, $all['faqs'] ?? []);
        $this->saveSteps($pageId, $all['steps'] ?? []);

        Session::flash('success', 'Página criada com sucesso.');
        $this->redirect('/admin/pages');
    }

    public function edit(string $id): void
    {
        $page = Page::find((int) $id);

        if (!$page) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $this->view('admin/pages/edit', [
            'page' => $page,
            'sections' => PageSection::allByPage((int) $id),
            'services' => PageService::allByPage((int) $id),
            'team' => PageTeamMember::allByPage((int) $id),
            'faqs' => PageFaq::allByPage((int) $id),
            'steps' => PageStep::allByPage((int) $id),
            'errors' => Session::flash('errors') ?? [],
        ]);
    }

    public function update(string $id): void
    {
        $this->checkCsrf('/admin/pages/' . $id . '/edit');

        $pageId = (int) $id;
        $page = Page::find($pageId);

        if (!$page) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        [$data, $errors] = $this->validate(Request::all(), $pageId);

        if ($errors !== []) {
            Session::flash('errors', $errors);
            $this->redirect('/admin/pages/' . $id . '/edit');
        }

        try {
            $newImage = Upload::image('image', $this->uploadDir());
            $newLogo = Upload::image('logo', $this->uploadDir());
        } catch (RuntimeException $e) {
            Session::flash('errors', ['image' => $e->getMessage()]);
            $this->redirect('/admin/pages/' . $id . '/edit');
        }

        $data['image'] = $page['image'];
        if ($newImage) {
            Upload::delete($this->uploadDir(), $page['image']);
            $data['image'] = $newImage;
        }

        $data['logo'] = $page['logo'];
        if ($newLogo) {
            Upload::delete($this->uploadDir(), $page['logo']);
            $data['logo'] = $newLogo;
        }

        Page::update($pageId, $data);

        $all = Request::all();
        $this->saveSections($pageId, $all['sections'] ?? [], $_FILES['sections'] ?? null);
        $this->saveServices($pageId, $all['services'] ?? []);
        $this->saveTeam($pageId, $all['team'] ?? [], $_FILES['team'] ?? null);
        $this->saveFaqs($pageId, $all['faqs'] ?? []);
        $this->saveSteps($pageId, $all['steps'] ?? []);

        Session::flash('success', 'Página atualizada com sucesso.');
        $this->redirect('/admin/pages');
    }

    public function destroy(string $id): void
    {
        $this->checkCsrf('/admin/pages');

        $pageId = (int) $id;
        $page = Page::find($pageId);

        if ($page) {
            foreach (PageSection::allByPage($pageId) as $section) {
                Upload::delete($this->uploadDir(), $section['image']);
            }
            foreach (PageTeamMember::allByPage($pageId) as $member) {
                Upload::delete($this->uploadDir(), $member['photo']);
            }
            Upload::delete($this->uploadDir(), $page['image']);
            Upload::delete($this->uploadDir(), $page['logo']);
            Page::delete($pageId);
        }

        Session::flash('success', 'Página removida.');
        $this->redirect('/admin/pages');
    }

    private function checkCsrf(string $redirectTo): void
    {
        $token = is_string(Request::input('_csrf_token')) ? Request::input('_csrf_token') : null;

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect($redirectTo);
        }
    }

    /** @return array{0: array, 1: array} [dados validados, erros por campo] */
    private function validate(array $input, ?int $excludeId = null): array
    {
        $errors = [];

        $title = trim((string) ($input['title'] ?? ''));
        $subtitle = trim((string) ($input['subtitle'] ?? ''));
        $heroEyebrow = trim((string) ($input['hero_eyebrow'] ?? ''));
        $titleHighlight = trim((string) ($input['title_highlight'] ?? ''));
        $heroLayout = (string) ($input['hero_layout'] ?? 'centered');
        $trustBadges = trim((string) ($input['trust_badges'] ?? ''));
        $content = trim((string) ($input['content'] ?? ''));
        $rawSlug = trim((string) ($input['slug'] ?? ''));
        $template = (string) ($input['template'] ?? 'institucional');
        $ctaText = trim((string) ($input['cta_text'] ?? ''));
        $ctaLink = trim((string) ($input['cta_link'] ?? ''));
        $ctaSecondaryText = trim((string) ($input['cta_secondary_text'] ?? ''));
        $ctaSecondaryLink = trim((string) ($input['cta_secondary_link'] ?? ''));
        $whatsapp = trim((string) ($input['whatsapp'] ?? ''));
        $topbarPhone = trim((string) ($input['topbar_phone'] ?? ''));
        $topbarNote = trim((string) ($input['topbar_note'] ?? ''));
        $mapAddress = trim((string) ($input['map_address'] ?? ''));
        $privacyNotice = trim((string) ($input['privacy_notice'] ?? ''));
        $imageCaption = trim((string) ($input['image_caption'] ?? ''));

        if (!in_array($template, self::TEMPLATES, true)) {
            $template = 'institucional';
        }

        if (!in_array($heroLayout, self::HERO_LAYOUTS, true)) {
            $heroLayout = 'centered';
        }

        if ($title === '') {
            $errors['title'] = 'O título é obrigatório.';
        } elseif (mb_strlen($title) > 200) {
            $errors['title'] = 'O título pode ter no máximo 200 caracteres.';
        }

        if ($content === '' && $template !== 'profissional') {
            $errors['content'] = 'O conteúdo é obrigatório.';
        }

        if ($whatsapp !== '' && strlen(preg_replace('/\D+/', '', $whatsapp) ?? '') < 10) {
            $errors['whatsapp'] = 'Informe o WhatsApp com DDI+DDD, ex: 55659xxxxxxx.';
        }

        $slug = slugify($rawSlug !== '' ? $rawSlug : $title);

        if (in_array($slug, self::RESERVED_SLUGS, true)) {
            $errors['slug'] = 'Esse slug é reservado pelo sistema — escolha outro.';
        } elseif (Page::slugExists($slug, $excludeId)) {
            $errors['slug'] = 'Já existe uma página com esse slug.';
        }

        return [
            [
                'title' => $title,
                'subtitle' => $subtitle !== '' ? $subtitle : null,
                'hero_eyebrow' => $heroEyebrow !== '' ? $heroEyebrow : null,
                'title_highlight' => $titleHighlight !== '' ? $titleHighlight : null,
                'hero_layout' => $heroLayout,
                'trust_badges' => $trustBadges !== '' ? $trustBadges : null,
                'content' => $content,
                'template' => $template,
                'cta_text' => $ctaText !== '' ? $ctaText : null,
                'cta_link' => $ctaLink !== '' ? $ctaLink : null,
                'cta_secondary_text' => $ctaSecondaryText !== '' ? $ctaSecondaryText : null,
                'cta_secondary_link' => $ctaSecondaryLink !== '' ? $ctaSecondaryLink : null,
                'whatsapp' => $whatsapp !== '' ? $whatsapp : null,
                'topbar_phone' => $topbarPhone !== '' ? $topbarPhone : null,
                'topbar_note' => $topbarNote !== '' ? $topbarNote : null,
                'map_address' => $mapAddress !== '' ? $mapAddress : null,
                'privacy_notice' => $privacyNotice !== '' ? $privacyNotice : null,
                'image_caption' => $imageCaption !== '' ? $imageCaption : null,
                'slug' => $slug,
            ],
            $errors,
        ];
    }

    private function saveSections(int $pageId, array $sectionsInput, ?array $sectionsFilesRaw): void
    {
        $normalizedFiles = $sectionsFilesRaw ? normalizeNestedFiles($sectionsFilesRaw, 'image') : [];

        PageSection::deleteByPage($pageId);

        $position = 0;

        foreach ($sectionsInput as $index => $section) {
            $content = trim((string) ($section['content'] ?? ''));

            if ($content === '') {
                continue;
            }

            $title = trim((string) ($section['title'] ?? ''));
            $asideTitle = trim((string) ($section['aside_title'] ?? ''));
            $asideContent = trim((string) ($section['aside_content'] ?? ''));
            $bgColor = $this->sanitizeColor($section['bg_color'] ?? null);
            $layout = in_array($section['layout'] ?? '', self::LAYOUTS, true) ? $section['layout'] : 'text-only';

            $existingImage = $section['existing_image'] ?? null;
            $removeImage = !empty($section['remove_image']);
            $image = $existingImage ?: null;

            try {
                $newImage = Upload::fromFileArray($normalizedFiles[$index] ?? null, $this->uploadDir());
            } catch (RuntimeException) {
                $newImage = null;
            }

            if ($newImage) {
                if ($existingImage) {
                    Upload::delete($this->uploadDir(), $existingImage);
                }
                $image = $newImage;
            } elseif ($removeImage && $existingImage) {
                Upload::delete($this->uploadDir(), $existingImage);
                $image = null;
            }

            PageSection::create([
                'page_id' => $pageId,
                'position' => $position,
                'title' => $title !== '' ? $title : null,
                'content' => $content,
                'aside_title' => $asideTitle !== '' ? $asideTitle : null,
                'aside_content' => $asideContent !== '' ? $asideContent : null,
                'image' => $image,
                'bg_color' => $bgColor,
                'layout' => $layout,
            ]);

            $position++;
        }
    }

    private function saveServices(int $pageId, array $servicesInput): void
    {
        PageService::deleteByPage($pageId);

        $position = 0;

        foreach ($servicesInput as $service) {
            $title = trim((string) ($service['title'] ?? ''));
            $description = trim((string) ($service['description'] ?? ''));

            if ($title === '' || $description === '') {
                continue;
            }

            $icon = mb_substr(trim((string) ($service['icon'] ?? '')), 0, 4);

            PageService::create([
                'page_id' => $pageId,
                'position' => $position,
                'icon' => $icon !== '' ? $icon : null,
                'title' => $title,
                'description' => $description,
            ]);

            $position++;
        }
    }

    private function saveTeam(int $pageId, array $teamInput, ?array $teamFilesRaw): void
    {
        $normalizedFiles = $teamFilesRaw ? normalizeNestedFiles($teamFilesRaw, 'photo') : [];

        $existingMembers = PageTeamMember::allByPage($pageId);
        PageTeamMember::deleteByPage($pageId);

        $position = 0;

        foreach ($teamInput as $index => $member) {
            $name = trim((string) ($member['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $role = trim((string) ($member['role'] ?? ''));
            $whatsapp = trim((string) ($member['whatsapp'] ?? ''));
            $existingPhoto = $member['existing_photo'] ?? null;
            $removePhoto = !empty($member['remove_photo']);
            $photo = $existingPhoto ?: null;

            try {
                $newPhoto = Upload::fromFileArray($normalizedFiles[$index] ?? null, $this->uploadDir());
            } catch (RuntimeException) {
                $newPhoto = null;
            }

            if ($newPhoto) {
                if ($existingPhoto) {
                    Upload::delete($this->uploadDir(), $existingPhoto);
                }
                $photo = $newPhoto;
            } elseif ($removePhoto && $existingPhoto) {
                Upload::delete($this->uploadDir(), $existingPhoto);
                $photo = null;
            }

            PageTeamMember::create([
                'page_id' => $pageId,
                'position' => $position,
                'photo' => $photo,
                'name' => $name,
                'role' => $role !== '' ? $role : null,
                'whatsapp' => $whatsapp !== '' ? $whatsapp : null,
            ]);

            $position++;
        }

        $keptPhotos = array_column(PageTeamMember::allByPage($pageId), 'photo');
        foreach ($existingMembers as $old) {
            if (!empty($old['photo']) && !in_array($old['photo'], $keptPhotos, true)) {
                Upload::delete($this->uploadDir(), $old['photo']);
            }
        }
    }

    private function saveFaqs(int $pageId, array $faqsInput): void
    {
        PageFaq::deleteByPage($pageId);

        $position = 0;

        foreach ($faqsInput as $faq) {
            $question = trim((string) ($faq['question'] ?? ''));
            $answer = trim((string) ($faq['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                continue;
            }

            PageFaq::create([
                'page_id' => $pageId,
                'position' => $position,
                'question' => $question,
                'answer' => $answer,
            ]);

            $position++;
        }
    }

    private function saveSteps(int $pageId, array $stepsInput): void
    {
        PageStep::deleteByPage($pageId);

        $position = 0;

        foreach ($stepsInput as $step) {
            $title = trim((string) ($step['title'] ?? ''));
            $description = trim((string) ($step['description'] ?? ''));

            if ($title === '' || $description === '') {
                continue;
            }

            PageStep::create([
                'page_id' => $pageId,
                'position' => $position,
                'title' => $title,
                'description' => $description,
            ]);

            $position++;
        }
    }

    private function sanitizeColor(?string $color): ?string
    {
        if (!$color) {
            return null;
        }

        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) === 1 ? $color : null;
    }
}

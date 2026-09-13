<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function slugify(string $text): string
{
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = $ascii !== false ? $ascii : $text;
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');

    return $text === '' ? 'pagina' : $text;
}

function normalizeNestedFiles(array $filesGroup, string $subField): array
{
    $result = [];

    foreach ($filesGroup['name'] ?? [] as $index => $_) {
        $result[$index] = [
            'name' => $filesGroup['name'][$index][$subField] ?? '',
            'type' => $filesGroup['type'][$index][$subField] ?? '',
            'tmp_name' => $filesGroup['tmp_name'][$index][$subField] ?? '',
            'error' => $filesGroup['error'][$index][$subField] ?? UPLOAD_ERR_NO_FILE,
            'size' => $filesGroup['size'][$index][$subField] ?? 0,
        ];
    }

    return $result;
}

function contrastTextColor(?string $hexColor): string
{
    if (!$hexColor || !preg_match('/^#[0-9a-fA-F]{6}$/', $hexColor)) {
        return '#1f2937';
    }

    $hex = ltrim($hexColor, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    return $luminance > 0.55 ? '#1f2937' : '#f8fafc';
}

function renderPlainTextBlock(string $text): string
{
    $blocks = preg_split('/\n\s*\n/', trim($text)) ?: [];
    $html = '';

    foreach ($blocks as $block) {
        $block = trim($block);
        if ($block === '') {
            continue;
        }
        $html .= '<p>' . nl2br(e($block)) . '</p>';
    }

    return $html;
}

// Monta um link "wa.me" válido a partir de um número digitado de qualquer
// jeito (com parênteses, traço, espaço...) e uma mensagem opcional já
// pré-preenchida. Nunca confiamos no número como veio — extraímos só os
// dígitos antes de montar a URL.
function whatsappLink(?string $number, string $message = ''): ?string
{
    if (!$number) {
        return null;
    }

    $digits = preg_replace('/\D+/', '', $number) ?? '';

    if ($digits === '') {
        return null;
    }

    $url = 'https://wa.me/' . $digits;

    if ($message !== '') {
        $url .= '?text=' . rawurlencode($message);
    }

    return $url;
}


/* Transforma cada linha do texto em um item de lista com marcador de check
    pensado para blocos tipo "diferenciais", "o que fazemos", etc. */
function renderChecklist(string $text): string
{
    return renderPlainList($text, 'checklist');
}

function renderHeroTitle(string $title, ?string $highlight): string
{
    if (!$highlight) {
        return e($title);
    }

    $pos = mb_stripos($title, $highlight);

    if ($pos === false) {
        return e($title);
    }

    $before = mb_substr($title, 0, $pos);
    $match = mb_substr($title, $pos, mb_strlen($highlight));
    $after = mb_substr($title, $pos + mb_strlen($highlight));

    return e($before) . '<span class="highlight">' . e($match) . '</span>' . e($after);
}

// Lista genérica: cada linha do texto vira um <li>. Usada tanto pro checklist
// em grade quanto pra variações de layout (lista única, selos de confiança).
function renderPlainList(string $text, string $listClass = 'checklist'): string
{
    $lines = preg_split('/\r\n|\r|\n/', trim($text)) ?: [];
    $html = '<ul class="' . e($listClass) . '">';

    foreach ($lines as $line) {
        $line = trim($line);
        $line = preg_replace('/^[✓✔•\-\*]\s*/u', '', $line) ?? $line;

        if ($line === '') {
            continue;
        }

        $html .= '<li>' . e($line) . '</li>';
    }

    return $html . '</ul>';
}

function initials(string $text): string
{
    $words = preg_split('/\s+/', trim($text)) ?: [];
    $letters = '';

    foreach ($words as $word) {
        if ($word === '') {
            continue;
        }
        $letters .= mb_strtoupper(mb_substr($word, 0, 1));
        if (mb_strlen($letters) >= 2) {
            break;
        }
    }

    return $letters !== '' ? $letters : '•';
}

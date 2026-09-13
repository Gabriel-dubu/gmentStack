<?php $titulo = $page['title']; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(($basePath ?? '') . '/assets/css/institucional.css') ?>">
</head>

<body class="pro-body">

    <?php $whatsappHero = whatsappLink($page['whatsapp'] ?? null, 'Olá! Vi o site e gostaria de falar com um especialista.'); ?>

    <?php if (!empty($page['topbar_phone']) || !empty($page['topbar_note'])): ?>
        <div class="pro-topbar">
            <div class="pro-topbar-inner">
                <?php if (!empty($page['topbar_note'])): ?><span><?= e($page['topbar_note']) ?></span><?php endif; ?>
                <?php if (!empty($page['topbar_phone'])): ?><span><?= e($page['topbar_phone']) ?></span><?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <nav class="pro-nav">
        <div class="pro-nav-inner">
            <div class="pro-nav-identity">
                <?php if (!empty($page['logo'])): ?>
                    <img src="<?= e(($basePath ?? '') . '/uploads/pages/' . $page['logo']) ?>" alt="" class="logo-badge logo-badge-image">
                <?php else: ?>
                    <span class="logo-badge"><?= e(initials($page['title'])) ?></span>
                <?php endif; ?>
                <span class="pro-nav-brand"><?= e($page['title']) ?></span>
            </div>
            <div class="pro-nav-links">
                <?php if (!empty($services)): ?><a href="#servicos">Áreas</a><?php endif; ?>
                <?php if (!empty($steps)): ?><a href="#como-funciona">Como funciona</a><?php endif; ?>
                <?php if (!empty($team)): ?><a href="#equipe">Quem somos</a><?php endif; ?>
                <?php if (!empty($faqs)): ?><a href="#duvidas">Dúvidas</a><?php endif; ?>
                <a href="#contato">Fale Conosco</a>
            </div>
        </div>
    </nav>

    <?php $heroLayout = in_array($page['hero_layout'] ?? 'centered', ['card-right', 'card-left'], true) ? $page['hero_layout'] : 'centered'; ?>
    <header class="hero hero-pro<?= $heroLayout !== 'centered' ? ' hero-split hero-' . e($heroLayout) : '' ?>">
        <div class="<?= $heroLayout === 'centered' ? 'hero-inner' : 'hero-split-inner' ?>">
            <div class="hero-split-content">
                <?php if (!empty($page['hero_eyebrow'])): ?>
                    <p class="hero-eyebrow"><?= e($page['hero_eyebrow']) ?></p>
                <?php endif; ?>

                <h1><?= renderHeroTitle($page['title'], $page['title_highlight'] ?? null) ?></h1>

                <?php if (!empty($page['subtitle'])): ?>
                    <p class="hero-subtitle"><?= e($page['subtitle']) ?></p>
                <?php endif; ?>

                <div class="hero-ctas">
                    <?php if ($whatsappHero): ?>
                        <a href="<?= e($whatsappHero) ?>" class="button button-whatsapp" target="_blank" rel="noopener">
                            💬 <?= e($page['cta_text'] ?? 'Falar no WhatsApp') ?>
                        </a>
                    <?php else: ?>
                        <a href="#contato" class="button button-gold">
                            <?= e($page['cta_text'] ?? 'Fale Conosco') ?>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($page['cta_secondary_text'])): ?>
                        <a href="<?= e($page['cta_secondary_link'] ?: '#contato') ?>" class="button button-outline">
                            <?= e($page['cta_secondary_text']) ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($heroLayout === 'centered' && !empty($page['trust_badges'])): ?>
                    <div class="trust-badges"><?= renderPlainList($page['trust_badges'], 'checklist') ?></div>
                <?php endif; ?>
            </div>

            <?php if ($heroLayout !== 'centered' && !empty($page['trust_badges'])): ?>
                <div class="hero-trust-card">
                    <?= renderPlainList($page['trust_badges'], 'checklist') ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if (!empty($services)): ?>
        <section class="pro-section" id="servicos">
            <div class="pro-section-inner">
                <h2 class="pro-section-title">Áreas de Atuação</h2>
                <div class="services-grid">
                    <?php foreach ($services as $service): ?>
                        <div class="service-card">
                            <?php if (!empty($service['icon'])): ?>
                                <div class="service-icon"><?= e($service['icon']) ?></div>
                            <?php endif; ?>
                            <h3><?= e($service['title']) ?></h3>
                            <p><?= e($service['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($steps)): ?>
        <section class="pro-section pro-section-alt" id="como-funciona">
            <div class="pro-section-inner pro-steps-inner">
                <h2 class="pro-section-title">Como funciona</h2>
                <ol class="steps-list">
                    <?php foreach ($steps as $i => $step): ?>
                        <li class="step-item">
                            <span class="step-number"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <div>
                                <h3><?= e($step['title']) ?></h3>
                                <p><?= e($step['description']) ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>
    <?php endif; ?>

    <?php $hasImage = !empty($page['image']);
    $hasContent = !empty($page['content']); ?>
    <?php if ($hasImage || $hasContent || !empty($sections)): ?>
        <section class="pro-section pro-section-alt">
            <div class="pro-section-inner">

                <?php if ($hasImage || $hasContent): ?>
                    <div class="intro-block<?= !$hasContent ? ' intro-block-image-only' : '' ?><?= !$hasImage ? ' intro-block-text-only' : '' ?>">
                        <?php if ($hasImage): ?>
                            <figure class="intro-image-wrap">
                                <img
                                    src="<?= e(($basePath ?? '') . '/uploads/pages/' . $page['image']) ?>"
                                    alt="<?= e($page['title']) ?>"
                                    class="intro-image">
                                <?php if (!empty($page['image_caption'])): ?>
                                    <figcaption class="intro-caption"><?= e($page['image_caption']) ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endif; ?>

                        <?php if ($hasContent): ?>
                            <div class="intro-text"><?= renderPlainTextBlock($page['content']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($sections as $section): ?>
                    <?php $layout = $section['layout'] ?? 'text-only'; ?>
                    <?php $textColor = contrastTextColor($section['bg_color'] ?? null); ?>

                    <?php if ($layout === 'checklist'): ?>
                        <section class="content-section">
                            <div
                                class="checklist-card"
                                style="<?php if (!empty($section['bg_color'])): ?>background-color: <?= e($section['bg_color']) ?>;<?php endif; ?> color: <?= e($textColor) ?>;">
                                <?php if (!empty($section['title'])): ?><h2><?= e($section['title']) ?></h2><?php endif; ?>
                                <?= renderChecklist($section['content']) ?>
                            </div>
                        </section>

                    <?php elseif ($layout === 'checklist-list'): ?>
                        <section class="content-section">
                            <div class="checklist-list-card">
                                <?php if (!empty($section['title'])): ?><h2><?= e($section['title']) ?></h2><?php endif; ?>
                                <?= renderPlainList($section['content'], 'checklist-list') ?>
                            </div>
                        </section>

                    <?php elseif ($layout === 'pain-card'): ?>
                        <section class="content-section">
                            <div class="pain-card">
                                <?php if (!empty($section['title'])): ?><h2><?= e($section['title']) ?></h2><?php endif; ?>
                                <?= renderPlainList($section['content'], 'pain-bullets') ?>
                                <a href="#contato" class="button button-gold">Quero avaliar meu caso</a>
                            </div>
                        </section>

                    <?php else: ?>
                        <section
                            class="content-section content-section-<?= e($layout) ?>"
                            style="<?php if (!empty($section['bg_color'])): ?>background-color: <?= e($section['bg_color']) ?>;<?php endif; ?> color: <?= e($textColor) ?>;">
                            <div class="content-section-inner">
                                <?php if (!empty($section['image'])): ?>
                                    <img
                                        src="<?= e(($basePath ?? '') . '/uploads/pages/' . $section['image']) ?>"
                                        alt="<?= e($section['title'] ?? '') ?>"
                                        class="content-section-image">
                                <?php endif; ?>
                                <div class="content-section-text">
                                    <?php if (!empty($section['title'])): ?><h2><?= e($section['title']) ?></h2><?php endif; ?>
                                    <?= renderPlainTextBlock($section['content']) ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($team)): ?>
        <section class="pro-section" id="equipe">
            <div class="pro-section-inner">
                <h2 class="pro-section-title">Nossa Equipe</h2>
                <div class="team-grid">
                    <?php foreach ($team as $member): ?>
                        <?php $memberLink = whatsappLink($member['whatsapp'] ?? null, 'Olá ' . $member['name'] . '! Gostaria de receber orientação.'); ?>
                        <div class="team-card">
                            <?php if (!empty($member['photo'])): ?>
                                <img
                                    src="<?= e(($basePath ?? '') . '/uploads/pages/' . $member['photo']) ?>"
                                    alt="<?= e($member['name']) ?>"
                                    class="team-photo">
                            <?php else: ?>
                                <div class="team-photo team-photo-placeholder">👤</div>
                            <?php endif; ?>
                            <h3><?= e($member['name']) ?></h3>
                            <?php if (!empty($member['role'])): ?><p class="team-role"><?= e($member['role']) ?></p><?php endif; ?>
                            <?php if ($memberLink): ?>
                                <a href="<?= e($memberLink) ?>" class="button button-whatsapp button-sm" target="_blank" rel="noopener">
                                    💬 Falar com <?= e(explode(' ', $member['name'])[0]) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($faqs)): ?>
        <section class="pro-section pro-section-alt" id="duvidas">
            <div class="pro-section-inner pro-faq-inner">
                <h2 class="pro-section-title">Perguntas Frequentes</h2>
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item">
                        <summary><?= e($faq['question']) ?></summary>
                        <div class="faq-answer"><?= renderPlainTextBlock($faq['answer']) ?></div>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="pro-section pro-section-alt" id="contato">
        <div class="pro-section-inner pro-contact-inner">
            <h2 class="pro-section-title"><?= e($page['cta_text'] ?? 'Fale com um especialista agora') ?></h2>

            <?php if (!empty($leadSuccess)): ?>
                <p class="alert alert-success flash"><?= e($leadSuccess) ?></p>
            <?php else: ?>
                <form method="POST" action="<?= e(($basePath ?? '') . '/' . $page['slug'] . '/lead') ?>" class="lead-form" id="lead-form">
                    <?= \App\Core\Csrf::field() ?>

                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" value="<?= e($leadOld['name'] ?? '') ?>" required>
                    <?php if (!empty($leadErrors['name'])): ?><p class="field-error flash"><?= e($leadErrors['name']) ?></p><?php endif; ?>

                    <label for="company">Empresa (opcional)</label>
                    <input type="text" id="company" name="company" value="<?= e($leadOld['company'] ?? '') ?>">

                    <label for="phone">WhatsApp</label>
                    <input type="text" id="phone" name="phone" placeholder="(11) 91234-5678" value="<?= e($leadOld['phone'] ?? '') ?>" required>
                    <?php if (!empty($leadErrors['phone'])): ?><p class="field-error flash"><?= e($leadErrors['phone']) ?></p><?php endif; ?>

                    <label for="email">E-mail (opcional)</label>
                    <input type="email" id="email" name="email" value="<?= e($leadOld['email'] ?? '') ?>">
                    <?php if (!empty($leadErrors['email'])): ?><p class="field-error flash"><?= e($leadErrors['email']) ?></p><?php endif; ?>

                    <label for="situation">Situação atual (opcional)</label>
                    <select id="situation" name="situation">
                        <option value="">Selecione</option>
                        <option value="Ainda não recebi nenhuma notificação">Ainda não recebi nenhuma notificação</option>
                        <option value="Já recebi uma notificação/citação">Já recebi uma notificação/citação</option>
                        <option value="Já existe um processo em andamento">Já existe um processo em andamento</option>
                        <option value="Outro">Outro</option>
                    </select>

                    <label for="message">Resumo do caso (opcional)</label>
                    <textarea id="message" name="message" rows="4" placeholder="Não inclua senhas ou dados bancários."><?= e($leadOld['message'] ?? '') ?></textarea>

                    <button type="submit" class="button button-gold button-block">Quero ser atendido</button>

                    <div class="lead-form-alt-actions">
                        <button type="button" class="button-link" onclick="sendLeadViaWhatsapp()">Ou enviar por WhatsApp</button>
                        <button type="button" class="button-link" onclick="sendLeadViaEmail()">Ou enviar por e-mail</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!empty($page['map_address'])): ?>
        <section class="pro-map">
            <iframe
                src="https://www.google.com/maps?q=<?= rawurlencode($page['map_address']) ?>&output=embed"
                width="100%"
                height="360"
                style="border:0;"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
    <?php endif; ?>

    <?php if (!empty($page['privacy_notice'])): ?>
        <section class="pro-section privacy-section">
            <div class="pro-section-inner pro-faq-inner">
                <h2 class="pro-section-title">Aviso de Privacidade</h2>
                <?= renderPlainTextBlock($page['privacy_notice']) ?>
            </div>
        </section>
    <?php endif; ?>

    <footer class="pro-footer">
        <?php if (!empty($page['topbar_phone']) || !empty($page['map_address'])): ?>
            <p>
                <?php if (!empty($page['map_address'])): ?><?= e($page['map_address']) ?><?php endif; ?>
                <?php if (!empty($page['topbar_phone'])): ?> · <?= e($page['topbar_phone']) ?><?php endif; ?>
            </p>
        <?php endif; ?>
        <p>&copy; <?= date('Y') ?> <?= e($page['title']) ?>. Todos os direitos reservados.</p>
    </footer>

    <?php if ($whatsappHero): ?>
        <a href="<?= e($whatsappHero) ?>" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Falar no WhatsApp">💬</a>
    <?php endif; ?>

    <script>
        function collectLeadData() {
            return {
                name: document.getElementById('name').value.trim(),
                company: document.getElementById('company').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                email: document.getElementById('email').value.trim(),
                situation: document.getElementById('situation').value.trim(),
                message: document.getElementById('message').value.trim(),
            };
        }

        function buildLeadMessage(data) {
            const lines = ['Olá, meu nome é ' + (data.name || '(não informado)') + '.'];
            if (data.company) lines.push('Empresa: ' + data.company);
            if (data.email) lines.push('E-mail: ' + data.email);
            if (data.situation) lines.push('Situação: ' + data.situation);
            if (data.message) lines.push('Resumo: ' + data.message);
            return lines.join('\n');
        }

        function sendLeadViaWhatsapp() {
            const data = collectLeadData();
            const phone = "<?= e(preg_replace('/\D+/', '', (string) ($page['whatsapp'] ?? ''))) ?>";
            if (!phone) {
                alert('WhatsApp não configurado para esta página.');
                return;
            }
            window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(buildLeadMessage(data)), '_blank');
        }

        function sendLeadViaEmail() {
            const data = collectLeadData();
            const subject = encodeURIComponent('Contato via site - ' + (data.name || 'Site'));
            window.location.href = 'mailto:?subject=' + subject + '&body=' + encodeURIComponent(buildLeadMessage(data));
        }
    </script>

</body>

</html>
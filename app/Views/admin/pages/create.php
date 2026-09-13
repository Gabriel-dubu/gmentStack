<?php
$titulo = 'Nova Página - Admin';
require __DIR__ . '/../../layouts/header.php';
require __DIR__ . '/../../layouts/admin-nav.php';
?>

<main class="page">
    <h1>Nova página</h1>

    <form method="POST" action="<?= e(($basePath ?? '') . '/admin/pages') ?>" enctype="multipart/form-data">
        <?= \App\Core\Csrf::field(); ?>

        <label for="template">Tipo de página</label>
        <select id="template" name="template">
            <option value="institucional" <?= ($old['template'] ?? 'institucional') === 'institucional' ? 'selected' : '' ?>>
                Institucional (empresa, serviços, sobre)
            </option>
            <option value="landing" <?= ($old['template'] ?? '') === 'landing' ? 'selected' : '' ?>>
                Página de vendas / captura de lead (simples)
            </option>
            <option value="profissional" <?= ($old['template'] ?? '') === 'profissional' ? 'selected' : '' ?>>
                Profissional (hero + serviços + equipe + FAQ + WhatsApp + mapa)
            </option>
        </select>

        <label for="title">Título</label>
        <input type="text" id="title" name="title" value="<?= e($old['title'] ?? '') ?>" required autofocus>
        <?php if (!empty($errors['title'])): ?><p class="field-error flash"><?= e($errors['title']) ?></p><?php endif; ?>

        <label for="subtitle">Subtítulo (aparece no hero, abaixo do título)</label>
        <input type="text" id="subtitle" name="subtitle" value="<?= e($old['subtitle'] ?? '') ?>" placeholder="Ex: Especialistas em recuperação fiscal para o seu negócio">

        <label for="hero_eyebrow">Rótulo pequeno acima do título (opcional)</label>
        <input type="text" id="hero_eyebrow" name="hero_eyebrow" value="<?= e($old['hero_eyebrow'] ?? '') ?>" placeholder="Ex: DEFESA EMPRESARIAL ESPECIALIZADA">

        <label for="title_highlight">
            Palavra/trecho do título para destacar em dourado (opcional)
            <small>Precisa ser um trecho exato que já existe no título acima.</small>
        </label>
        <input type="text" id="title_highlight" name="title_highlight" value="<?= e($old['title_highlight'] ?? '') ?>">

        <label for="hero_layout">
            Layout do Hero
            <small>No layout com card, os Selos de confiança (abaixo) aparecem dentro do card, em vez de em linha.</small>
        </label>
        <select id="hero_layout" name="hero_layout">
            <option value="centered" <?= ($old['hero_layout'] ?? 'centered') === 'centered' ? 'selected' : '' ?>>Centralizado</option>
            <option value="card-right" <?= ($old['hero_layout'] ?? '') === 'card-right' ? 'selected' : '' ?>>Alinhado à esquerda, card à direita</option>
            <option value="card-left" <?= ($old['hero_layout'] ?? '') === 'card-left' ? 'selected' : '' ?>>Alinhado à direita, card à esquerda</option>
        </select>

        <label for="trust_badges">
            Selos de confiança (opcional)
            <small>Um por linha. Ex: Sigilo profissional / Atuação nacional / Resposta rápida</small>
        </label>
        <textarea id="trust_badges" name="trust_badges" rows="3"><?= e($old['trust_badges'] ?? '') ?></textarea>

        <label for="slug">Slug (opcional — gerado do título se deixar em branco)</label>
        <input type="text" id="slug" name="slug" value="<?= e($old['slug'] ?? '') ?>" placeholder="ex: recuperacao-fiscal">
        <?php if (!empty($errors['slug'])): ?><p class="field-error flash"><?= e($errors['slug']) ?></p><?php endif; ?>

        <label for="content">
            Conteúdo (introdução da página)
            <small>Deixe em branco se as informações já estão em Serviços/Equipe abaixo — evite repetir o mesmo texto em mais de um lugar.</small>
        </label>
        <textarea id="content" name="content" rows="6"><?= e($old['content'] ?? '') ?></textarea>
        <?php if (!empty($errors['content'])): ?><p class="field-error flash"><?= e($errors['content']) ?></p><?php endif; ?>

        <label for="cta_text">
            Texto do botão de ação principal
            <small>(institucional: texto do botão comum. landing/profissional: título acima do formulário/WhatsApp)</small>
        </label>
        <input type="text" id="cta_text" name="cta_text" value="<?= e($old['cta_text'] ?? '') ?>" placeholder="Ex: Fale com um especialista">

        <label for="cta_link">
            Link do botão principal
            <small>(só usado no template institucional — na landing/profissional o botão vai direto pro formulário/WhatsApp)</small>
        </label>
        <input type="text" id="cta_link" name="cta_link" value="<?= e($old['cta_link'] ?? '') ?>" placeholder="Ex: /contato ou https://wa.me/55...">

        <label for="cta_secondary_text">Botão secundário do hero — texto (opcional)</label>
        <input type="text" id="cta_secondary_text" name="cta_secondary_text" value="<?= e($old['cta_secondary_text'] ?? '') ?>" placeholder="Ex: Entender o procedimento">

        <label for="cta_secondary_link">Botão secundário do hero — link (opcional, padrão vai pro formulário de contato)</label>
        <input type="text" id="cta_secondary_link" name="cta_secondary_link" value="<?= e($old['cta_secondary_link'] ?? '') ?>" placeholder="#contato">

        <label for="image">Imagem principal (opcional, JPG/PNG/WEBP/GIF, até 2MB)</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
        <?php if (!empty($errors['image'])): ?><p class="field-error flash"><?= e($errors['image']) ?></p><?php endif; ?>

        <label for="image_caption">Legenda da imagem (opcional)</label>
        <input type="text" id="image_caption" name="image_caption" value="<?= e($old['image_caption'] ?? '') ?>" placeholder="Ex: Nosso escritório em Cuiabá/MT">

        <label for="whatsapp">WhatsApp da página (formato: DDI+DDD+número, ex: 55659xxxxxxx)</label>
        <input type="text" id="whatsapp" name="whatsapp" value="<?= e($old['whatsapp'] ?? '') ?>" placeholder="556599999999">
        <?php if (!empty($errors['whatsapp'])): ?><p class="field-error flash"><?= e($errors['whatsapp']) ?></p><?php endif; ?>

        <label for="topbar_phone">Telefone na barra superior (opcional)</label>
        <input type="text" id="topbar_phone" name="topbar_phone" value="<?= e($old['topbar_phone'] ?? '') ?>" placeholder="(65) 99644-6210">

        <label for="topbar_note">
            Nota/registro na barra superior (opcional)
            <small>Ex: OAB/MT 23.603/O, CRC 12345, CNPJ 12.345.678/0001-00 — qualquer credencial do seu ramo</small>
        </label>
        <input type="text" id="topbar_note" name="topbar_note" value="<?= e($old['topbar_note'] ?? '') ?>">

        <label for="logo">Logo (opcional — se não enviar, usamos as iniciais do título automaticamente)</label>
        <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp,image/gif">

        <label for="map_address">Endereço para o mapa (opcional)</label>
        <input type="text" id="map_address" name="map_address" value="<?= e($old['map_address'] ?? '') ?>" placeholder="Rua Exemplo, 123 - Cidade/UF">

        <label for="privacy_notice">
            Aviso de Privacidade (opcional)
            <small>Aparece como seção própria antes do rodapé, se preenchido</small>
        </label>
        <textarea id="privacy_notice" name="privacy_notice" rows="5"><?= e($old['privacy_notice'] ?? '') ?></textarea>

        <hr>

        <p><small><strong>Atenção:</strong> use "Seções" só pra conteúdo institucional extra (ex: "Nossa História", "Diferenciais", "Missão"). Se a informação já está em Serviços ou Equipe, não repita aqui.</small></p>
        <h2>Seções de conteúdo</h2>
        <p><small>Cada seção pode ter cor de fundo e imagem próprias. Deixe o conteúdo em branco pra seção não ser salva.</small></p>

        <div id="sections-container"></div>

        <button type="button" id="add-section" class="button" style="margin-top:1rem;">+ Adicionar seção</button>

        <template id="section-template">
            <div class="section-block">
                <button type="button" class="remove-section link-button link-danger">Remover seção</button>

                <label>Título da seção (opcional)</label>
                <input type="text" name="sections[__INDEX__][title]">

                <label>Conteúdo</label>
                <textarea name="sections[__INDEX__][content]" rows="4"></textarea>

                <label>Cor de fundo (opcional)</label>
                <input type="color" name="sections[__INDEX__][bg_color]" value="#ffffff">

                <label>Layout</label>
                <select name="sections[__INDEX__][layout]">
                    <option value="text-only">Somente texto</option>
                    <option value="checklist">Checklist em grade (2 colunas)</option>
                    <option value="checklist-list">Lista em coluna única (com divisórias)</option>
                    <option value="checklist-list-right">Lista + card ao lado (card na direita)</option>
                    <option value="checklist-list-left">Lista + card ao lado (card na esquerda)</option>
                    <option value="pain-card">Card escuro de dor/problema + CTA</option>
                    <option value="image-left">Imagem à esquerda</option>
                    <option value="image-right">Imagem à direita</option>
                </select>

                <label>Imagem (opcional)</label>
                <input type="file" name="sections[__INDEX__][image]" accept="image/jpeg,image/png,image/webp,image/gif">

                <label>
                    Título do card ao lado
                    <small>Só usado nos layouts "Lista + card ao lado"</small>
                </label>
                <input type="text" name="sections[__INDEX__][aside_title]" placeholder="Ex: Essa situação está acontecendo com você?">

                <label>
                    Texto do card ao lado (opcional)
                    <small>Um item por linha vira um bullet. Um botão "Quero avaliar meu caso" aparece automaticamente embaixo.</small>
                </label>
                <textarea name="sections[__INDEX__][aside_content]" rows="4"></textarea>
            </div>
        </template>

        <hr>
        <h2>Áreas de atuação / Serviços</h2>
        <p><small>Aparece como uma grade de cards. Deixe título e descrição em branco pra não salvar o card.</small></p>

        <div id="services-container"></div>
        <button type="button" id="add-service" class="button" style="margin-top:1rem;">+ Adicionar serviço</button>

        <template id="service-template">
            <div class="section-block">
                <button type="button" class="remove-service link-button link-danger">Remover</button>
                <label>Ícone/emoji (opcional)</label>
                <input type="text" name="services[__INDEX__][icon]" placeholder="⚖️" maxlength="4">
                <label>Título</label>
                <input type="text" name="services[__INDEX__][title]">
                <label>Descrição</label>
                <textarea name="services[__INDEX__][description]" rows="3"></textarea>
            </div>
        </template>

        <hr>
        <h2>Equipe</h2>
        <p><small>Cada membro pode ter foto e um WhatsApp próprio. Deixe o nome em branco pra não salvar.</small></p>

        <div id="team-container"></div>
        <button type="button" id="add-team" class="button" style="margin-top:1rem;">+ Adicionar membro</button>

        <template id="team-template">
            <div class="section-block">
                <button type="button" class="remove-team link-button link-danger">Remover</button>
                <label>Nome</label>
                <input type="text" name="team[__INDEX__][name]">
                <label>Cargo/especialidade</label>
                <input type="text" name="team[__INDEX__][role]">
                <label>WhatsApp direto (opcional)</label>
                <input type="text" name="team[__INDEX__][whatsapp]" placeholder="556599999999">
                <label>Foto (opcional)</label>
                <input type="file" name="team[__INDEX__][photo]" accept="image/jpeg,image/png,image/webp,image/gif">
            </div>
        </template>

        <hr>
        <h2>Perguntas Frequentes (FAQ)</h2>
        <p><small>Aparece como acordeão (clica pra expandir). Deixe pergunta/resposta em branco pra não salvar.</small></p>

        <div id="faqs-container"></div>
        <button type="button" id="add-faq" class="button" style="margin-top:1rem;">+ Adicionar pergunta</button>

        <template id="faq-template">
            <div class="section-block">
                <button type="button" class="remove-faq link-button link-danger">Remover</button>
                <label>Pergunta</label>
                <input type="text" name="faqs[__INDEX__][question]">
                <label>Resposta</label>
                <textarea name="faqs[__INDEX__][answer]" rows="3"></textarea>
            </div>
        </template>

        <hr>
        <h2>Como funciona (passos numerados)</h2>
        <p><small>Aparece como uma lista vertical numerada. Deixe título/descrição em branco pra não salvar.</small></p>

        <div id="steps-container"></div>
        <button type="button" id="add-step" class="button" style="margin-top:1rem;">+ Adicionar passo</button>

        <template id="step-template">
            <div class="section-block">
                <button type="button" class="remove-step link-button link-danger">Remover</button>
                <label>Título do passo</label>
                <input type="text" name="steps[__INDEX__][title]">
                <label>Descrição</label>
                <textarea name="steps[__INDEX__][description]" rows="3"></textarea>
            </div>
        </template>

        <button type="submit" style="margin-top:2rem;">Salvar</button>
    </form>
</main>

<script>
    (function() {
        function setupRepeater(containerId, templateId, addButtonId, removeClass) {
            const container = document.getElementById(containerId);
            const template = document.getElementById(templateId);
            const addButton = document.getElementById(addButtonId);
            let index = 0;

            function addBlock() {
                const html = template.innerHTML.replaceAll('__INDEX__', String(index));
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;
                container.appendChild(wrapper.firstElementChild);
                index++;
            }

            addButton.addEventListener('click', addBlock);

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains(removeClass)) {
                    e.target.closest('.section-block').remove();
                }
            });

            return addBlock;
        }

        setupRepeater('sections-container', 'section-template', 'add-section', 'remove-section')();
        setupRepeater('services-container', 'service-template', 'add-service', 'remove-service');
        setupRepeater('team-container', 'team-template', 'add-team', 'remove-team');
        setupRepeater('faqs-container', 'faq-template', 'add-faq', 'remove-faq');
        setupRepeater('steps-container', 'step-template', 'add-step', 'remove-step');
    })();
</script>

<?php
require __DIR__ . '/../../layouts/footer.php';
?>
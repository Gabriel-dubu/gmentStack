<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class Upload
{
    private const MAX_BYTES = 2 * 1024 * 1024; // 2MB

    // Whitelist de MIME types -> extensão. Nunca confiamos na extensão
    // ou no Content-Type que o navegador manda (ambos podem ser forjados);
    // o tipo real é lido dos bytes do arquivo via finfo.
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    /**
     * Processa o upload de um campo de arquivo.
     * Retorna o nome do arquivo salvo, ou null se nada foi enviado.
     * Lança RuntimeException com mensagem amigável em caso de arquivo inválido.
     */
    public static function image(string $field, string $destDir): ?string
    {
        return self::fromFileArray($_FILES[$field] ?? null, $destDir);
    }

    /**
     * Mesma validação de sempre, mas recebendo o array do arquivo já pronto
     * (útil quando o campo vem de um grupo, tipo sections[0][image]).
     */
    public static function fromFileArray(?array $file, string $destDir): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha ao enviar a imagem.');
        }

        if ($file['size'] > self::MAX_BYTES) {
            throw new RuntimeException('Imagem maior que o limite de 2MB.');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Upload inválido.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!isset(self::ALLOWED[$mime])) {
            throw new RuntimeException('Formato não permitido. Envie um JPG, PNG, WEBP ou GIF.');
        }

        $extension = self::ALLOWED[$mime];
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;

        if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            throw new RuntimeException('Não foi possível preparar o diretório de upload.');
        }

        if (!move_uploaded_file($file['tmp_name'], rtrim($destDir, '/') . '/' . $filename)) {
            throw new RuntimeException('Não foi possível salvar a imagem.');
        }

        return $filename;
    }

    public static function delete(string $destDir, ?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $path = rtrim($destDir, '/') . '/' . $filename;

        if (is_file($path)) {
            unlink($path);
        }
    }
}

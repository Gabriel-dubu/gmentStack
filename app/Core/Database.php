<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    // Conexão única (singleton) sempre usando prepared statements reais,
    // nunca emulados, o que fecha a porta para SQL injection clássico.
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = Config::get('database');

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $config['host'],
                $config['name']
            );

            try {
                self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log('Erro de conexão com o banco: ' . $e->getMessage());
                throw new PDOException('Não foi possível conectar ao banco de dados.');
            }
        }

        return self::$instance;
    }
}

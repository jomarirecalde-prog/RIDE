<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    /** @param array{host: string, port: int, name: string, user: string, pass: string, charset: string} $config */
    public static function init(array $config): void
    {
        if (self::$pdo !== null) {
            return;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['name'],
            $config['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::migratePending();
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Unknown database')) {
                self::createDatabase($config);
                self::$pdo = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::runMigrations();
                self::migratePending();
            } else {
                throw $e;
            }
        }
    }

    public static function migratePending(): void
    {
        if (self::$pdo === null) {
            return;
        }
        self::ensureSchemaMigrationsTable();

        self::applyMigrationFile('phase2');
        self::applyMigrationFile('phase3');
        self::applyMigrationFile('phase4');
        self::applyMigrationFile('phase5');
        self::applyMigrationFile('phase6');
        self::applyMigrationFile('phase7');
        self::applyMigrationFile('phase8');
        self::applyMigrationFile('phase9');
        self::applyMigrationFile('phase10');
        self::applyMigrationFile('phase11');
        self::applyMigrationFile('phase12');
        self::applyMigrationFile('phase13');
        self::applyMigrationFile('phase14');
        self::applyMigrationFile('phase15');
        self::applyMigrationFile('phase16');
        self::applyMigrationFile('phase17');
        self::applyMigrationFile('phase18');
        self::applyMigrationFile('phase19');
        self::applyMigrationFile('phase20');
        self::applyMigrationFile('phase21');
        self::applyMigrationFile('phase22');
        self::applyMigrationFile('phase23');
    }

    private static function ensureSchemaMigrationsTable(): void
    {
        if (self::$pdo === null) {
            return;
        }

        $ddl = 'CREATE TABLE IF NOT EXISTS schema_migrations (
                version VARCHAR(40) PRIMARY KEY,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB';

        self::$pdo->exec($ddl);

        try {
            self::$pdo->query('SELECT 1 FROM schema_migrations LIMIT 1');
        } catch (PDOException $e) {
            // MySQL 1932: table exists in dictionary but InnoDB files are missing/corrupt.
            if (!str_contains($e->getMessage(), "doesn't exist in engine")
                && (int) $e->errorInfo[1] !== 1932) {
                throw $e;
            }
            self::$pdo->exec('DROP TABLE IF EXISTS schema_migrations');
            self::$pdo->exec(
                'CREATE TABLE schema_migrations (
                    version VARCHAR(40) PRIMARY KEY,
                    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB'
            );
        }
    }

    private static function applyMigrationFile(string $version): void
    {
        if (self::$pdo === null) {
            return;
        }

        $file = BASE_PATH . '/database/migrations/' . $version . '.sql';
        if (!is_file($file)) {
            return;
        }

        $check = self::$pdo->prepare('SELECT 1 FROM schema_migrations WHERE version = ?');
        $check->execute([$version]);
        if ($check->fetchColumn()) {
            return;
        }

        $sql = file_get_contents($file);
        if ($sql === false) {
            return;
        }
        self::$pdo->exec($sql);
        $stmt = self::$pdo->prepare('INSERT INTO schema_migrations (version) VALUES (?)');
        $stmt->execute([$version]);
    }

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            throw new \RuntimeException('Database not initialized.');
        }
        return self::$pdo;
    }

    /** @param array{host: string, port: int, name: string, user: string, pass: string} $config */
    private static function createDatabase(array $config): void
    {
        $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        $name = $config['name'];
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    private static function runMigrations(): void
    {
        $schema = file_get_contents(BASE_PATH . '/database/schema.sql');
        if ($schema === false) {
            return;
        }
        self::$pdo->exec($schema);

        $seedPath = BASE_PATH . '/database/seeds.sql';
        if (is_file($seedPath)) {
            $seeds = file_get_contents($seedPath);
            if ($seeds !== false) {
                self::$pdo->exec($seeds);
            }
        }
    }
}

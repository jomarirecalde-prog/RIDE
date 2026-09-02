<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class AdminMessage
{
    /** @return list<array<string, mixed>> */
    public static function latestList(int $limit = 10): array
    {
        $limit = max(1, min($limit, 50));
        $stmt = Database::pdo()->prepare(
            'SELECT id, message, created_by, created_at
             FROM admin_messages
             WHERE is_active = 1
             ORDER BY created_at DESC, id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll() ?: [];
    }

    /** @return array<string, mixed>|null */
    public static function latest(): ?array
    {
        $stmt = Database::pdo()->query(
            'SELECT id, message, created_by, created_at
             FROM admin_messages
             WHERE is_active = 1
             ORDER BY created_at DESC, id DESC
             LIMIT 1'
        );
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function publish(string $message, ?int $createdBy = null): void
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO admin_messages (message, created_by, is_active)
             VALUES (?, ?, 1)'
        );
        $stmt->execute([$message, $createdBy]);
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = Database::pdo()->prepare(
            'SELECT id, message, created_by, created_at, is_active
             FROM admin_messages
             WHERE id = ? AND is_active = 1
             LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function update(int $id, string $message): bool
    {
        if ($id <= 0 || $message === '') {
            return false;
        }

        $stmt = Database::pdo()->prepare(
            'UPDATE admin_messages
             SET message = ?
             WHERE id = ? AND is_active = 1'
        );

        if (!$stmt->execute([$message, $id])) {
            return false;
        }

        return self::find($id) !== null;
    }

    public static function deactivate(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = Database::pdo()->prepare(
            'UPDATE admin_messages
             SET is_active = 0
             WHERE id = ? AND is_active = 1'
        );

        return $stmt->execute([$id]) && $stmt->rowCount() > 0;
    }

    public static function unreadCountForUser(int $userId): int
    {
        if ($userId <= 0) {
            return 0;
        }

        $stmt = Database::pdo()->prepare(
            'SELECT COUNT(*)
             FROM admin_messages am
             LEFT JOIN global_message_reads gmr ON gmr.user_id = ?
             WHERE am.is_active = 1
               AND (gmr.last_read_at IS NULL OR am.created_at > gmr.last_read_at)'
        );
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }
}

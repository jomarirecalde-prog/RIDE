<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class HighlightSlide
{
    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    private const ALLOWED_MIME = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    private const MAX_BYTES = 5 * 1024 * 1024;
    private const RELATIVE_DIR = 'assets/uploads/highlights';

    /** @return list<array<string, mixed>> */
    public static function all(): array
    {
        $stmt = Database::pdo()->query(
            'SELECT id, title, caption, image_path, sort_order, is_active, created_by, created_at, updated_at
             FROM highlight_slides
             ORDER BY sort_order ASC, id ASC'
        );

        return array_map([self::class, 'hydrate'], $stmt->fetchAll() ?: []);
    }

    /** @return list<array<string, mixed>> */
    public static function activeList(): array
    {
        $stmt = Database::pdo()->query(
            'SELECT id, title, caption, image_path, sort_order, is_active, created_by, created_at, updated_at
             FROM highlight_slides
             WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC'
        );

        return array_map([self::class, 'hydrate'], $stmt->fetchAll() ?: []);
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, title, caption, image_path, sort_order, is_active, created_by, created_at, updated_at
             FROM highlight_slides
             WHERE id = ?
             LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ? self::hydrate($row) : null;
    }

    public static function create(array $file, string $title, string $caption, ?int $createdBy): int
    {
        $relativePath = self::storeImage($file);

        $pdo = Database::pdo();
        $max = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM highlight_slides')->fetchColumn();

        $stmt = $pdo->prepare(
            'INSERT INTO highlight_slides (title, caption, image_path, sort_order, is_active, created_by)
             VALUES (?, ?, ?, ?, 1, ?)'
        );
        $stmt->execute([
            $title !== '' ? $title : null,
            $caption !== '' ? $caption : null,
            $relativePath,
            $max + 1,
            $createdBy,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, string $title, string $caption, bool $isActive): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE highlight_slides
             SET title = ?, caption = ?, is_active = ?
             WHERE id = ?'
        );
        $stmt->execute([
            $title !== '' ? $title : null,
            $caption !== '' ? $caption : null,
            $isActive ? 1 : 0,
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $slide = self::find($id);
        if ($slide === null) {
            return;
        }

        $stmt = Database::pdo()->prepare('DELETE FROM highlight_slides WHERE id = ?');
        $stmt->execute([$id]);
        self::deleteImageFile((string) $slide['image_path']);
    }

    /** @param list<int> $orderedIds */
    public static function reorder(array $orderedIds): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE highlight_slides SET sort_order = ? WHERE id = ?'
        );
        $order = 1;
        foreach ($orderedIds as $id) {
            $id = (int) $id;
            if ($id <= 0) {
                continue;
            }
            $stmt->execute([$order, $id]);
            $order++;
        }
    }

    public static function move(int $id, int $direction): void
    {
        $slides = self::all();
        $index = null;
        foreach ($slides as $i => $slide) {
            if ((int) $slide['id'] === $id) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            return;
        }

        $newIndex = $index + $direction;
        if ($newIndex < 0 || $newIndex >= count($slides)) {
            return;
        }

        $reordered = $slides;
        [$reordered[$index], $reordered[$newIndex]] = [$reordered[$newIndex], $reordered[$index]];
        self::reorder(array_map(static fn(array $s): int => (int) $s['id'], $reordered));
    }

    public static function imageUrl(string $relativePath): string
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $full = BASE_PATH . '/public/' . $relativePath;
        $version = is_file($full) ? (string) filemtime($full) : '1';

        return base_url($relativePath) . '?v=' . $version;
    }

    private static function storeImage(array $file): string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(self::uploadErrorMessage($error));
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            throw new \RuntimeException('Image upload failed.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > self::MAX_BYTES) {
            throw new \RuntimeException('Image must be 5 MB or smaller.');
        }

        $ext = self::detectExtension($file, $tmp);
        if ($ext === null) {
            throw new \RuntimeException('Image must be a JPG, PNG, WebP, or GIF file.');
        }

        $dir = BASE_PATH . '/public/' . self::RELATIVE_DIR;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException('Could not create highlights upload folder.');
        }

        $filename = 'slide_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($tmp, $dest)) {
            if (!@copy($tmp, $dest)) {
                throw new \RuntimeException('Could not save highlight image.');
            }
            @unlink($tmp);
        }

        return self::RELATIVE_DIR . '/' . $filename;
    }

    private static function deleteImageFile(string $relativePath): void
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        if ($relativePath === '' || !str_starts_with($relativePath, self::RELATIVE_DIR . '/')) {
            return;
        }

        $full = BASE_PATH . '/public/' . $relativePath;
        $real = realpath($full);
        $uploadRoot = realpath(BASE_PATH . '/public/' . self::RELATIVE_DIR);
        if ($real === false || $uploadRoot === false || !str_starts_with($real, $uploadRoot)) {
            return;
        }

        if (is_file($real)) {
            unlink($real);
        }
    }

    private static function detectExtension(array $file, string $tmp): ?string
    {
        $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }

        $mime = null;
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $detected = finfo_file($finfo, $tmp);
                finfo_close($finfo);
                if (is_string($detected)) {
                    $mime = strtolower($detected);
                }
            }
        }

        if ($mime !== null && isset(self::ALLOWED_MIME[$mime])) {
            return self::ALLOWED_MIME[$mime];
        }

        if (in_array($ext, self::ALLOWED_EXT, true)) {
            $info = @getimagesize($tmp);
            if (is_array($info) && isset($info['mime'], self::ALLOWED_MIME[strtolower((string) $info['mime'])])) {
                return self::ALLOWED_MIME[strtolower((string) $info['mime'])];
            }
        }

        return null;
    }

    private static function uploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image is too large.',
            UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please select an image to upload.',
            default => 'Image upload failed.',
        };
    }

    /** @param array<string, mixed> $row */
    private static function hydrate(array $row): array
    {
        $path = (string) ($row['image_path'] ?? '');
        $row['image_url'] = $path !== '' ? self::imageUrl($path) : '';
        $row['is_active'] = (bool) ($row['is_active'] ?? false);
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['sort_order'] = (int) ($row['sort_order'] ?? 0);

        return $row;
    }
}

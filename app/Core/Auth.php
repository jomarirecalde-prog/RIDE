<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function user(): ?array
    {
        $id = $_SESSION['user_id'] ?? null;
        if (!$id) {
            return null;
        }
        return User::findById((int) $id);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_roles'] = User::roleSlugs((int) $user['id']);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function hasRole(string ...$roles): bool
    {
        $userRoles = $_SESSION['user_roles'] ?? [];
        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return true;
            }
        }
        return false;
    }

    public static function can(string $permission): bool
    {
        $id = $_SESSION['user_id'] ?? null;
        if (!$id) {
            return false;
        }
        return User::hasPermission((int) $id, $permission);
    }
}

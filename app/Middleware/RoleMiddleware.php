<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

final class RoleMiddleware
{
    public static function require(string ...$roles): callable
    {
        return static function () use ($roles): void {
            if (!Auth::check()) {
                redirect('login');
            }
            if (!Auth::hasRole(...$roles)) {
                http_response_code(403);
                view('errors.403');
                exit;
            }
        };
    }
}

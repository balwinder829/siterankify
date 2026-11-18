<?php
declare(strict_types=1);

namespace App\Helpers;

class ViewHelper {
    public static function url(string $path = ''): string {
        return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
    }

    public static function e(?string $string): string {
        return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
    }

    public static function csrf(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool {
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}

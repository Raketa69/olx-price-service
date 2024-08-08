<?php

declare(strict_types=1);

namespace App\Helpers;

class Helper
{
    public static function getPost(): array
    {
        $post = json_decode(file_get_contents('php://input'), true);

        if (!empty($post)) {
            return $post;
        }

        if (json_last_error() == JSON_ERROR_NONE) {
            return $post;
        }

        return [];
    }
}
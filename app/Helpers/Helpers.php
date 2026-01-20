<?php

use Illuminate\Support\Str;

if (! function_exists('avatar_placeholder')) {
    /**
     * Generate avatar placeholder using UI Avatars API
     *
     * @param  string  $name  Name to display on avatar
     * @param  int  $size  Avatar size in pixels (default: 200)
     * @param  string  $background  Background color hex without # (default: random)
     * @param  string  $color  Text color hex without # (default: ffffff)
     * @return string Avatar URL
     */
    function avatar_placeholder($name, $size = 200, $background = null, $color = 'ffffff')
    {
        if (empty($name)) {
            $name = 'User';
        }

        // Extract initials (first letter of first 2 words)
        $words = explode(' ', trim($name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            if (! empty($word)) {
                $initials .= mb_substr($word, 0, 1);
            }
        }

        // Random background colors if not provided
        if (! $background) {
            $colors = ['206bc4', '2fb344', 'd63939', 'f76707', 'ae3ec9', '4299e1', 'ed64a6'];
            $background = $colors[abs(crc32($name)) % count($colors)];
        }

        // Build URL
        $params = [
            'name' => urlencode($initials),
            'size' => $size,
            'background' => $background,
            'color' => $color,
            'bold' => 'true',
            'format' => 'svg',
        ];

        return 'https://ui-avatars.com/api/?'.http_build_query($params);
    }
}

if (! function_exists('limit_text')) {
    function limit_text($text, $limit = 100, $end = '...')
    {
        if (strlen($text) > $limit) {
            $text = substr($text, 0, $limit);
            $text = substr($text, 0, strrpos($text, ' '));
            $text = $text.$end;
        }

        return $text;
    }
}

if (! function_exists('format_image_url')) {
    function formatImageUrl($url)
    {
        return url($url);
    }
}

if (! function_exists('format_datetime')) {
    function format_datetime($datetime)
    {
        return date('d/m/Y H:i:s', strtotime($datetime));
    }
}

if (! function_exists('format_date')) {
    function formatDate($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}

if (! function_exists('format_time')) {
    function formatTime($time)
    {
        return date('H:i:s', strtotime($time));
    }
}

if (! function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 0, ',', '.').' ₫';
    }
}

if (! function_exists('setSidebarActive')) {

    function setSidebarActive(array $routes): ?string
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }

        return null;
    }
}

if (! function_exists('setSidebarShow')) {
    function setSidebarShow(array $routes): ?string
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'show';
            }
        }

        return null;
    }
}

function generate_text_depth_tree($depth, $word = '|--')
{
    return str_repeat($word, $depth);
}

if (! function_exists('format_datetime_ago')) {
    function format_datetime_ago($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return $diff.' giây trước';
        }

        $diff = round($diff / 60);
        if ($diff < 60) {
            return $diff.' phút trước';
        }

        $diff = round($diff / 60);
        if ($diff < 24) {
            return $diff.' giờ trước';
        }

        $diff = round($diff / 24);
        if ($diff < 30) {
            return $diff.' ngày trước';
        }

        $diff = round($diff / 30);
        if ($diff < 12) {
            return $diff.' tháng trước';
        }
    }
}

function uniqid_real($lenght = 13)
{
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new \Exception('no cryptographically secure random function available');
    }

    return Str::upper(substr(bin2hex($bytes), 0, $lenght));
}

if (! function_exists('limit_text')) {
    function limit_text($text, $limit = 100, $end = '...')
    {
        if (strlen($text) > $limit) {
            $text = substr($text, 0, $limit);
            $text = substr($text, 0, strrpos($text, ' '));
            $text = $text.$end;
        }

        return $text;
    }
}

if (! function_exists('generate_employee_code')) {
    function generate_employee_code()
    {
        return 'EMP-'.date('y').'/'.uniqid_real(6);
    }
}

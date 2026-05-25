<?php
/**
 * Router for PHP built-in development server.
 * Mimics .htaccess RewriteRule for clean URL routing.
 * Usage: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the request is for an existing file or directory, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let PHP's built-in server handle static files
}

// Match clean pSEO slugs like /class-9-physics-coaching-in-ganaur
if (preg_match('#^/([a-zA-Z0-9-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/page.php';
    return;
}

// Default: serve index.php
require __DIR__ . '/index.php';

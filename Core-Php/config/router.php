<?php

// ─── Define all routes here ───
// 'url' => ['controller' => 'function', 'view' => 'view file', 'title' => 'page title']

$routes = [
    'register'    => [
        'controller' => 'handleRegister',
        'view'       => 'register',
        'title'      => 'Register'
    ],
    'verify'      => [
        'controller' => 'handleVerify',
        'view'       => 'verify',
        'title'      => 'Verify Email'
    ],
    // 'login'       => [
    //     'controller' => 'handleLogin',
    //     'view'       => 'login',
    //     'title'      => 'Login'
    // ],
    // 'create_post' => [
    //     'controller' => 'handleCreatePost',
    //     'view'       => 'create_post',
    //     'title'      => 'Create Post'
    // ],
    // 'post'        => [
    //     'controller' => 'handlePost',
    //     'view'       => 'post',
    //     'title'      => 'Blog Post'
    // ],
    // 'logout'      => [
    //     'controller' => 'handleLogout',
    //     'view'       => null,   // no view needed, just redirects
    //     'title'      => ''
    // ],
    'resend_code' => [
        'controller' => 'handleResendCode',
        'view'       => null,   // no view, just redirects
        'title'      => ''
    ],
    ''            => [
        'controller' => 'handleHome',
        'view'       => 'home',
        'title'      => 'Home'
    ],
];


function runRouter($pdo, $routes) {

    // ─── Get current page from URL ───
    // e.g. localhost:9001/register.php → 'register'
    // $current = basename($_SERVER['PHP_SELF'], '.php');
// ─── Get page from URL ───
    $current = $_GET['page'] ?? '';
    $current = basename($current, '.php');  // strip .php if added
    // ─── Check route exists ───
    if (!array_key_exists($current, $routes)) {
        http_response_code(404);
        echo "404 - Page not found";
        exit;
    }

    $route      = $routes[$current];
    $controller = $route['controller'];
    $view       = $route['view'];
    $title      = $route['title'];

    // ─── Run controller function ───
    $data = $controller($pdo);

    // ─── If no view (redirect-only routes) ───
    if ($view === null) return;

    // ─── Render layout with view ───
    renderLayout($title, function () use ($data, $view) {
        if ($data) extract($data);
        require ROOT . '/views/' . $view . '.php';
    });
}
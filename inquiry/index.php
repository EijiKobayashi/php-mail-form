<?php
declare(strict_types=1);

define('APP_RUN', true);

// Modern Security Headers
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: private, no-cache, no-store, must-revalidate');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    $secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/config.php';

$query  = $_GET['q'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Initialize form data
$form_data = get_initial_form_data();
$errors    = [];

// Handle return from confirmation screen
if ($method === 'POST' && isset($_POST['back'])) {
    $form_data = extract_post_form_data();
    $page_title = '入力フォーム画面';
    require __DIR__ . '/templates/form.php';
    exit;
}

if ($query === 'confirmation') {
    if ($method !== 'POST') {
        header('Location: /inquiry/');
        exit;
    }

    $token = $_POST['token'] ?? '';
    if (!validate_csrf_token($token)) {
        header('Location: /inquiry/error/');
        exit;
    }

    $form_data = extract_post_form_data();
    $errors    = validate_form_data($form_data);

    if (!empty($errors)) {
        $page_title = '入力フォーム画面';
        require __DIR__ . '/templates/form.php';
        exit;
    }

    $page_title = '確認画面';
    require __DIR__ . '/templates/confirmation.php';
    exit;

} elseif ($query === 'send') {
    if ($method !== 'POST') {
        header('Location: /inquiry/');
        exit;
    }

    $token = $_POST['token'] ?? '';
    if (!validate_csrf_token($token)) {
        header('Location: /inquiry/error/');
        exit;
    }

    $form_data = extract_post_form_data();
    $errors    = validate_form_data($form_data);

    if (!empty($errors)) {
        $page_title = '入力フォーム画面';
        require __DIR__ . '/templates/form.php';
        exit;
    }

    require __DIR__ . '/templates/send.php';
    exit;

} elseif ($query === 'thanks') {
    $page_title = '完了画面';
    require __DIR__ . '/templates/thanks.php';
    exit;

} elseif ($query === 'error') {
    $page_title = 'エラー画面';
    require __DIR__ . '/templates/error.php';
    exit;

} else {
    // Default: Form screen
    if ($method === 'POST') {
        $form_data = extract_post_form_data();
    }
    $page_title = '入力フォーム画面';
    require __DIR__ . '/templates/form.php';
    exit;
}

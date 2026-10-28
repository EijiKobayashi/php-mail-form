<?php
declare(strict_types=1);

if (!defined('APP_RUN')) {
    http_response_code(403);
    exit('Access denied');
}

// Multibyte configuration (modern UTF-8 standard)
mb_language('Japanese');
mb_internal_encoding('UTF-8');

// Mail configuration
$mail_config = [
    'admin_email'   => 'e@oshinco.com',
    'from_email'    => 'no-reply@oshinco.com', // Must match system domain for SPF/DKIM/DMARC alignment
    'from_name'     => 'お問い合わせ窓口',
    'admin_subject' => '【メールフォーム】お客様よりお問い合わせがありました',
    'user_subject'  => '【メールフォーム】お問い合わせありがとうございました',
];

/**
 * Context-aware HTML escaping helper (prevents XSS)
 */
function h(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Clean user input: strip null bytes and normalize line endings
 */
function clean_input(mixed $data): string {
    if (!is_string($data)) {
        return '';
    }
    // Remove null bytes
    $data = str_replace("\0", '', $data);
    // Normalize line breaks
    $data = str_replace(["\r\n", "\r"], "\n", $data);
    return trim($data);
}

/**
 * Clean string for mail headers: strip \r and \n to prevent Mail Header Injection
 */
function clean_header(string $str): string {
    return str_replace(["\r", "\n", "\0"], '', $str);
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF Token with constant-time comparison
 */
function validate_csrf_token(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Invalidate CSRF Token
 */
function clear_csrf_token(): void {
    unset($_SESSION['csrf_token']);
}

/**
 * Initial empty form data
 */
function get_initial_form_data(): array {
    return [
        'subject'     => '',
        'name'        => '',
        'email'       => '',
        'postal_code' => '',
        'address'     => '',
        'phone'       => '',
        'content'     => '',
    ];
}

/**
 * Extract and clean form data from $_POST
 */
function extract_post_form_data(): array {
    return [
        'subject'     => clean_input($_POST['subject'] ?? ''),
        'name'        => clean_input($_POST['name'] ?? ''),
        'email'       => clean_input($_POST['email'] ?? ''),
        'postal_code' => clean_input($_POST['postal_code'] ?? ''),
        'address'     => clean_input($_POST['address'] ?? ''),
        'phone'       => clean_input($_POST['phone'] ?? ''),
        'content'     => clean_input($_POST['content'] ?? ''),
    ];
}

/**
 * Validate form inputs
 */
function validate_form_data(array $data): array {
    $errors = [];

    // Allowed subjects whitelist
    $allowed_subjects = ['お問い合わせ', '資料請求'];
    if (empty($data['subject']) || !in_array($data['subject'], $allowed_subjects, true)) {
        $errors['subject'] = '件名を選択してください。';
    }

    // Name (required, max 100)
    if ($data['name'] === '') {
        $errors['name'] = 'お名前を入力してください。';
    } elseif (mb_strlen($data['name'], 'UTF-8') > 100) {
        $errors['name'] = 'お名前は100文字以内で入力してください。';
    }

    // Email (required, RFC valid, no newlines/control chars, max 254)
    if ($data['email'] === '') {
        $errors['email'] = 'メールアドレスを入力してください。';
    } elseif (
        mb_strlen($data['email'], 'UTF-8') > 254 ||
        !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
        preg_match('/[\r\n]/', $data['email']) ||
        preg_match('/@\[[^\]]++\]\z/', $data['email'])
    ) {
        $errors['email'] = '正しいメールアドレスを入力してください。';
    }

    // Postal code (optional, 123-4567 or 1234567)
    if ($data['postal_code'] !== '') {
        if (!preg_match('/\A\d{3}-?\d{4}\z/', $data['postal_code'])) {
            $errors['postal_code'] = '郵便番号の形式が正しくありません。';
        }
    }

    // Address (optional, max 500)
    if ($data['address'] !== '' && mb_strlen($data['address'], 'UTF-8') > 500) {
        $errors['address'] = 'ご住所は500文字以内で入力してください。';
    }

    // Phone (optional, max 20, format check)
    if ($data['phone'] !== '') {
        if (!preg_match('/\A\d{2,4}-?\d{2,4}-?\d{3,4}\z/', $data['phone'])) {
            $errors['phone'] = '電話番号の形式が正しくありません。';
        }
    }

    // Content (required, max 5000)
    if ($data['content'] === '') {
        $errors['content'] = '質問等を入力してください。';
    } elseif (mb_strlen($data['content'], 'UTF-8') > 5000) {
        $errors['content'] = '質問等は5000文字以内で入力してください。';
    }

    return $errors;
}

/**
 * Compose Admin Notification Email Body (clean plain text, no HTML entities)
 */
function build_admin_mail_body(array $data): string {
    return <<<TEXT
【メールフォーム】お問い合わせがありました。

内容は下記の通りです。
------------------------------------------------
件名：
{$data['subject']}

お名前: {$data['name']}
メールアドレス: {$data['email']}
郵便番号: {$data['postal_code']}
ご住所: {$data['address']}
電話番号: {$data['phone']}

質問等：
{$data['content']}

-------------------------------------------------------------
(c) Oshinco Co-po. All Rights Reserved.
https://oshinco.com/co-op/
TEXT;
}

/**
 * Compose User Autoreply Email Body (clean plain text, no HTML entities)
 */
function build_user_mail_body(array $data): string {
    return <<<TEXT
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　このメールは、入力いただいたメールアドレス宛てに自動的にお送りしています。
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{$data['name']} 様

この度は、お問い合わせいただきありがとうございます。
下記の内容で承りましたのでお知らせします。

====================
■ 件名
{$data['subject']}
■ お名前: {$data['name']}
■ メールアドレス: {$data['email']}
■ 郵便番号: {$data['postal_code']}
■ ご住所: {$data['address']}
■ 電話番号: {$data['phone']}
■ 質問等
{$data['content']}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

※ このメールは「送信専用メールアドレス」から配信されています。
　 ご返信いただいてもお答えできませんのでご了承ください。
※ 内容によってはご回答できない場合もございますのでご了承ください。
※ 万一このメールを受け取られる覚えのない方は、大変お手数をおかけしますが
　 その旨をご記入の上、本メールの内容とともにご返信くださいますよう
　 お願いいたします。
※ 個人情報の取り扱いにつきましては、次のURLをご参照ください。
　 https://oshinco.com/co-op/

―――――――――――――――――――――――――――――――――――
(c) Oshinco Co-po. All Rights Reserved.
TEXT;
}

/**
 * Send inquiry emails safely
 */
function send_inquiry_mails(array $data, array $config): bool {
    $user_name  = clean_header($data['name']);
    $user_email = clean_header($data['email']);

    $admin_email   = $config['admin_email'];
    $admin_subject = clean_header($config['admin_subject']);
    $from_email    = $config['from_email'];
    $from_name     = clean_header($config['from_name']);
    $user_subject  = clean_header($config['user_subject']);

    // Admin email
    $admin_body = build_admin_mail_body($data);
    $admin_encoded_subject = mb_encode_mimeheader($admin_subject, 'UTF-8', 'B');
    $encoded_from = mb_encode_mimeheader($from_name, 'UTF-8', 'B') . " <{$from_email}>";
    $encoded_user = mb_encode_mimeheader($user_name, 'UTF-8', 'B') . " <{$user_email}>";

    $admin_headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ' . $encoded_from,
        'Reply-To: ' . $encoded_user,
        'X-Mailer: PHP/' . phpversion(),
    ];

    $envelope_param = '-f ' . escapeshellarg($from_email);
    $admin_sent = @mail($admin_email, $admin_encoded_subject, $admin_body, implode("\r\n", $admin_headers), $envelope_param);

    // User autoreply email
    $user_body = build_user_mail_body($data);
    $user_encoded_subject = mb_encode_mimeheader($user_subject, 'UTF-8', 'B');

    $user_headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ' . $encoded_from,
        'Reply-To: ' . $from_email,
        'X-Mailer: PHP/' . phpversion(),
    ];

    $user_sent = @mail($user_email, $user_encoded_subject, $user_body, implode("\r\n", $user_headers), $envelope_param);

    if (!$admin_sent || !$user_sent) {
        error_log('Failed to send mail in inquiry form. admin_sent: ' . ($admin_sent ? '1' : '0') . ', user_sent: ' . ($user_sent ? '1' : '0'));
    }

    return $admin_sent && $user_sent;
}

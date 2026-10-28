<?php
declare(strict_types=1);

/**
 * php-mail-form テストランナー (KISS: 外部パッケージ不要のネイティブテスト)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_RUN', true);
require_once __DIR__ . '/../inquiry/config.php';

$test_failures = 0;
$test_count = 0;

function assert_test(string $name, bool $condition, string $msg = ''): void {
    global $test_failures, $test_count;
    $test_count++;
    if ($condition) {
        echo "[PASS] $name\n";
    } else {
        echo "[FAIL] $name: $msg\n";
        $test_failures++;
    }
}

echo "=== php-mail-form Test Suite ===\n\n";

// 1. XSS 防御: h() 関数の挙動
$xss = '<script>alert("XSS")</script>&\'"';
$escaped = h($xss);
assert_test('h() escapes HTML special characters', 
    $escaped === '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;&amp;&#039;&quot;',
    "Got: $escaped"
);
assert_test('h(null) returns empty string without error', h(null) === '');

// 2. メールヘッダ・インジェクション防御: clean_header()
$injected_header = "victim@example.com\r\nBcc: evil@attacker.com\nSubject: Injected";
$cleaned_header = clean_header($injected_header);
assert_test('clean_header() strips carriage return and line feed',
    $cleaned_header === 'victim@example.comBcc: evil@attacker.comSubject: Injected',
    "Got: $cleaned_header"
);

// 3. NULLバイト除去・改行正規化: clean_input()
$input_with_null = "test\0value\r\nline2";
$cleaned_input = clean_input($input_with_null);
assert_test('clean_input() removes null bytes and normalizes CRLF',
    $cleaned_input === "testvalue\nline2",
    "Got: " . var_export($cleaned_input, true)
);

// 4. CSRF トークン生成・検証 (Timing attack safe)
$token = generate_csrf_token();
assert_test('CSRF token is 64-char hex string', strlen($token) === 64);
assert_test('validate_csrf_token validates genuine token', validate_csrf_token($token) === true);
assert_test('validate_csrf_token rejects forged token', validate_csrf_token('fake_token_12345') === false);
assert_test('validate_csrf_token rejects empty token', validate_csrf_token('') === false);
assert_test('validate_csrf_token rejects null token', validate_csrf_token(null) === false);
clear_csrf_token();
assert_test('clear_csrf_token removes token from session', empty($_SESSION['csrf_token']));

// 5. フォーム入力バリデーション
$valid_data = [
    'subject'     => 'お問い合わせ',
    'name'        => '山田 太郎',
    'email'       => 'yamada@example.com',
    'postal_code' => '123-4567',
    'address'     => '東京都千代田区1-1',
    'phone'       => '03-1234-5678',
    'content'     => 'お問い合わせ内容です。',
];
$errors = validate_form_data($valid_data);
assert_test('validate_form_data passes valid data', empty($errors), var_export($errors, true));

// 5a. 不正な件名の排除
$invalid_subject_data = $valid_data;
$invalid_subject_data['subject'] = '未許可の件名';
$errors = validate_form_data($invalid_subject_data);
assert_test('validate_form_data rejects unwhitelisted subject', isset($errors['subject']));

// 5b. CRLFを含むメールアドレスの拒絶
$injection_email_data = $valid_data;
$injection_email_data['email'] = "yamada@example.com\r\nBcc: spam@evil.com";
$errors = validate_form_data($injection_email_data);
assert_test('validate_form_data rejects email with CRLF', isset($errors['email']));

// 5c. 電話番号の形式チェック
$invalid_phone_data = $valid_data;
$invalid_phone_data['phone'] = "invalid-phone-abc";
$errors = validate_form_data($invalid_phone_data);
assert_test('validate_form_data rejects invalid phone format', isset($errors['phone']));

// 6. メール本文におけるプレーンテキストの維持（HTML実体参照で汚染されないこと）
$special_text_data = $valid_data;
$special_text_data['content'] = 'A & B < C > "quotes" \'apostrophe\'';
$admin_body = build_admin_mail_body($special_text_data);
assert_test('build_admin_mail_body preserves raw text without HTML entities',
    str_contains($admin_body, 'A & B < C > "quotes" \'apostrophe\'') &&
    !str_contains($admin_body, '&amp;') &&
    !str_contains($admin_body, '&lt;'),
    "Body had unexpected content: $admin_body"
);

echo "\n--- Total: $test_count, Failures: $test_failures ---\n";
exit($test_failures === 0 ? 0 : 1);


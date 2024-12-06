<?php

session_cache_limiter('nocache');
session_start();
mb_language("Ja");
mb_internal_encoding("UTF-8");
mb_detect_order("ASCII, JIS, UTF-8, EUC-JP, SJIS");

$admin_email   = "e@oshinco.com";
$from_email    = "TEST <no-reply@oshinco.com>";
$admin_subject = "【メールフォーム】お客様よりお問い合わせがありました";
$user_subject  = "【メールフォーム】お問い合わせありがとうございました";

$check         = $_POST['check'];
$token         = $_POST['token'];
$session_token = isset($_SESSION['token']) ? $_SESSION['token'] : '';
$subject       = sanitize($_POST['subject']);
$name          = sanitize($_POST['name']);
$email         = sanitize($_POST['email']);
$postal_code   = sanitize($_POST['postal_code']);
$address       = sanitize($_POST['address']);
$phone         = sanitize($_POST['phone']);
$content       = sanitize($_POST['content']);

function sanitize($data) {
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/@\[[^\]]++\]\z/', $email);
}

function is_valid_phone_number($number) {
  return is_string($number) && preg_match('/\A\d{2,4}+-\d{2,4}+-\d{4}\z/', $number);
}

function is_valid_required() {
  global $check;
  global $token;
  global $session_token;
  global $subject;
  global $name;
  global $email;
  global $content;
  if (
    $check != "" &&
    $token != "" &&
    $token === $session_token &&
    $subject != "" &&
    $name != "" &&
    $email != "" &&
    is_valid_email($email) != "" &&
    $content != ""
  ) {
    return TRUE;
  } else {
    return FALSE;
  }
}

$admin_text = "
【メールフォーム】お問い合わせがありました。

内容は下記の通りです。
------------------------------------------------
件名：
$subject
お名前: $name
メールアドレス: $email
郵便番号: $postal_code
ご住所: $address
電話番号: $phone
質問等：
$content

-------------------------------------------------------------
(c) Oshinco Co-po. All Rights Reserved.
https://oshinco.com/co-op/
";

$user_text = "
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　このメールは、入力いただいたメールアドレス宛てに自動的にお送りしています。
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

$name 様

この度は、お問い合わせいただきありがとうございます。
下記の内容で承りましたのでお知らせします。

====================
■ 件名
$subject
■ お名前: $name
■ メールアドレス: $email
■ 郵便番号: $postal_code
■ ご住所: $address
■ 電話番号: $phone
■ 質問等
$content

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
";

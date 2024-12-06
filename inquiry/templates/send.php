<?php
session_start();
if ($_POST['token'] !== $_SESSION['token']) {
  header('Location: ./error');
  exit;
}
unset($_SESSION['token']);

// TO: ADMIN
$admin_headers .= "From: $email\r\n";
$admin_headers .= "Reply-To: $email\n";
$admin_headers .= "Return-path: $email\n";
$admin_headers .= "Content-Type: text/plain; charset=iso-2022-jp";
$admin_subject = mb_encode_mimeheader($admin_subject, "ISO-2022-JP");
$admin_text    = mb_convert_encoding($admin_text, "JIS", "UTF-8");

// TO: USER
$user_headers  .= "From: $from_email\r\n";
$user_headers  .= "Reply-To: $from_email\n";
$user_headers  .= "Return-path: $from_email\n";
$user_headers  .= "Content-Type: text/plain; charset=iso-2022-jp";
$user_subject  = mb_encode_mimeheader($user_subject, "ISO-2022-JP");
$user_text     = mb_convert_encoding($user_text, "JIS", "UTF-8");

if (is_valid_required() == TRUE) {
  mail($admin_email, $admin_subject, $admin_text, $admin_headers);
  mail($email, $user_subject, $user_text, $user_headers);
  header("Location: ./thanks");
  exit;
} else {
  header("Location: ./");
  exit;
}

setcookie('PHPSESSID', '', time() - 1800);
session_destroy();

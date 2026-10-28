<?php
declare(strict_types=1);

if (!defined('APP_RUN')) {
    http_response_code(403);
    exit('Access denied');
}

// Send emails
$sent = send_inquiry_mails($form_data, $mail_config);

// Clear CSRF token to prevent duplicate submissions
clear_csrf_token();
session_regenerate_id(true);

if ($sent) {
    header('Location: /inquiry/thanks/');
    exit;
} else {
    // Mail failed; logged via send_inquiry_mails()
    header('Location: /inquiry/thanks/');
    exit;
}

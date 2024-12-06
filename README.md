# php-mail-form

PHP のシンプルなメールフォーム

## URL 構成

- 入力フォーム
  https://localhost/inquiry/
- 確認画面
  https://localhost/inquiry/confirmation/
- 完了画面
  https://localhost/inquiry/thanks/
- エラー画面
  https://localhost/inquiry/error/

## ディレクトリ構成

```
/inquiry
 │
 ├─ assets/
 │  ├─ css/style.css
 │  └─ js/validation.js
 │
 ├─ templates/
 │  ├─ _module_footer.inc (htmlテンプレート)
 │  ├─ _module_header.inc (htmlテンプレート)
 │  ├─ confirmation.php (htmlテンプレート)
 │  ├─ error.php (htmlテンプレート)
 │  ├─ form.php (htmlテンプレート)
 │  ├─ send.php
 │  └─ thanks.php (htmlテンプレート)
 │
 ├─ .htaccess
 ├─ config.php
 └─ index.php
```

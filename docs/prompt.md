# AIエージェント開発プロンプト仕様書（Agent Instruction Prompt）

このドキュメントは、**どのAIコーディングエージェント（Claude, ChatGPT, Gemini, Copilot 等）に入力しても、全くブレずに同一仕様・同一品質のコードを生成させるための標準プロンプトテンプレート**です。

---

## 1. エージェント向け共通指示プロンプト（コピー用）

以下の枠内の文章をそのままAIエージェントのプロンプトとして使用してください。

```markdown
# 役割・ミッション
あなたは「モダンPHP・セキュリティ・KISS原則」の熟練エンジニアです。
以下の仕様書に基づき、PHP 8.2+ 対応のセキュアなメールフォームを実装またはリファクタリングしてください。

## 開発の絶対原則
1. **後方互換・データ移行の破棄**: 過去のレガシーコードとの互換性は一切不要です。常に現在において最も洗練された理想的コードに書き換えてください。
2. **KISS原則の徹底**: 不要なComposerパッケージやフレームワークは使用せず、PHP標準関数のみで最小限かつシンプルに実装してください。
3. **資料ファースト**: 仕様書（docs/）に定義された項目・バリデーション・ルーティングと完全一致させてください。
4. **TDD**: テスト可能で堅牢なコードにし、PHP Warning/Deprecated/Noticeを一切出さないでください（declare(strict_types=1); 必須）。

## セキュリティ必須要件
1. **メールヘッダ・インジェクション対策**:
   - メールヘッダに挿入する値（$email, $name, $subject）から改行コード（\r, \n）およびNULLバイト（\0）を完全除去・拒絶すること。
2. **XSS対策**:
   - 入力時に安易にhtmlspecialcharsを適用せず、HTMLテンプレート描画時にのみ出力エスケープ関数 h(?string $str): string を通すこと。
   - メール本文はプレーンテキストのため、HTMLエンティティ（&amp; 等）を混入させない生の文字列を保持すること。
3. **CSRF対策**:
   - random_bytes(32) を使用してトークンを生成し、hash_equals() で定数時間比較すること。
   - 送信完了時にトークンを破棄すること。
4. **DMARC / SPF適合**:
   - 送信元 From: はシステム固定アドレス（no-reply@...）とし、ユーザーのメールアドレスは Reply-To: に設定すること。
5. **直接ファイルアクセス遮断**:
   - 定数 APP_RUN の定義有無を検証し、外部からの直接アクセスを 403 で拒絶すること。
   - セッションクッキーには HttpOnly, SameSite=Lax, Secure（HTTPS時）を明示すること。

## ディレクトリ・ファイル構成
- inquiry/index.php : ルーティング・セキュリティヘッダ・セッション初期化
- inquiry/config.php : 設定値・バリデーション関数・エスケープ関数・メール送信関数
- inquiry/templates/form.php : 入力フォーム画面
- inquiry/templates/confirmation.php : 確認画面（戻るボタンはformaction属性でPOST送信）
- inquiry/templates/send.php : メール送信実行・PRGリダイレクト
- inquiry/templates/thanks.php : 送信完了画面
- inquiry/templates/error.php : エラー画面
- inquiry/templates/_module_header.inc / _module_footer.inc : 共通ヘッダ/フッタ
- inquiry/assets/js/validation.js : バリデーションエラー時のみ送信阻止するJS
- inquiry/.htaccess : config.php, *.inc, templates/ への直接アクセス禁止

## バリデーション仕様
- 件名 (subject): 必須。「お問い合わせ」「資料請求」のいずれか。
- お名前 (name): 必須。100文字以内。
- メールアドレス (email): 必須。RFC適合かつ254文字以内、改行なし。
- 郵便番号 (postal_code): 任意。3桁-4桁形式（\A\d{3}-?\d{4}\z）。
- ご住所 (address): 任意。500文字以内。
- 電話番号 (phone): 任意。市外局番ハイフン形式（\A\d{2,4}-?\d{2,4}-?\d{3,4}\z）。
- 質問等 (content): 必須。5000文字以内。
```

---

## 2. プロンプト検証基準（受け入れテストチェックリスト）

AIエージェントが出力したコードが合格しているか、以下のチェックリストで即時判定できます。

- [ ] `php -l` を全ファイルに通して構文エラーがゼロであること。
- [ ] `php -d error_reporting=E_ALL` で実行した際、WarningやNoticeが1件も出ないこと。
- [ ] メールヘッダに `\r\n` を含むメールアドレスを入力した際、インジェクションが遮断されること。
- [ ] `<script>alert(1)</script>` を送信した際、確認画面でエスケープされ、メール本文には生の `<script>` が届くこと。
- [ ] CSRFトークンを改変してPOSTした際、直ちにエラー画面へ遷移すること。
- [ ] 外部から直接 `/inquiry/templates/send.php` や `/inquiry/config.php` を叩いたとき、`Access denied`（403）で停止すること。


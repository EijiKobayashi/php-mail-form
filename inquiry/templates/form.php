<?php
if (!defined('APP_RUN')) {
    http_response_code(403);
    exit('Access denied');
}
$page_title = '入力フォーム画面';
?>
<?php include __DIR__ . '/_module_header.inc'; ?>

<main>
  <div class="main">
    <div class="main__inner">

      <section>
        <div class="inquiry section">
          <h2 class="inquiry__title section__title">お問い合わせ</h2>
          <div class="steps">
            <ol class="steps__inner">
              <li class="step step1 is-current"><span>入力</span></li>
              <li class="step step2"><span>確認</span></li>
              <li class="step step3"><span>完了</span></li>
            </ol>
          </div><!--  / .steps /  -->

          <p class="inquiry__notice">お問い合わせは下記フォームよりお願いします。すべての項目にご記入の上、ご送信ください。</p>
          <form method="post" action="/inquiry/confirmation/" name="application" id="application" class="application h-adr" novalidate>
            <input type="hidden" class="p-country-name" value="Japan">
            <input type="hidden" name="token" id="token" value="<?= h(generate_csrf_token()) ?>" />

            <div class="group">
              <div class="label"><label for="subject">件名 <span class="require">必須</span></label></div>
              <div class="control<?= isset($errors['subject']) ? ' is-error' : '' ?>">
                <select name="subject" id="subject" class="large" required>
                  <option value="">件名を選択してください</option>
                  <option value="お問い合わせ"<?= ($form_data['subject'] === 'お問い合わせ') ? ' selected="selected"' : '' ?>>お問い合わせ</option>
                  <option value="資料請求"<?= ($form_data['subject'] === '資料請求') ? ' selected="selected"' : '' ?>>資料請求</option>
                </select>
                <span class="error-message"><?= h($errors['subject'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="name">お名前 <span class="require">必須</span></label></div>
              <div class="control<?= isset($errors['name']) ? ' is-error' : '' ?>">
                <input type="text" name="name" id="name" class="large" maxlength="100" value="<?= h($form_data['name']) ?>" placeholder="お名前" required />
                <span class="error-message"><?= h($errors['name'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="email">メールアドレス <span class="require">必須</span></label></div>
              <div class="control<?= isset($errors['email']) ? ' is-error' : '' ?>">
                <input type="email" name="email" id="email" class="large" maxlength="254" value="<?= h($form_data['email']) ?>" placeholder="メールアドレス" data-conv-half-alphanumeric="true" required />
                <span class="error-message"><?= h($errors['email'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="postal_code">郵便番号</label></div>
              <div class="control<?= isset($errors['postal_code']) ? ' is-error' : '' ?>">
                <input type="text" name="postal_code" id="postal_code" class="medium p-postal-code" maxlength="8" value="<?= h($form_data['postal_code']) ?>" placeholder="郵便番号" pattern="\d{3}-?\d{4}" />
                <span class="error-message"><?= h($errors['postal_code'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="address">ご住所 </label></div>
              <div class="control<?= isset($errors['address']) ? ' is-error' : '' ?>">
                <input type="text" name="address" id="address" class="large p-region p-locality p-street-address p-extended-address" maxlength="500" value="<?= h($form_data['address']) ?>" placeholder="住所" />
                <span class="error-message"><?= h($errors['address'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="phone">電話番号 </label></div>
              <div class="control<?= isset($errors['phone']) ? ' is-error' : '' ?>">
                <input type="tel" name="phone" id="phone" class="large" maxlength="20" value="<?= h($form_data['phone']) ?>" placeholder="電話番号" />
                <span class="error-message"><?= h($errors['phone'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="content">質問等 <span class="require">必須</span></label></div>
              <div class="control<?= isset($errors['content']) ? ' is-error' : '' ?>">
                <textarea name="content" id="content" class="large" cols="50" rows="5" maxlength="5000" placeholder="質問等をご記載ください" required><?= h($form_data['content']) ?></textarea>
                <span class="error-message"><?= h($errors['content'] ?? '') ?></span>
              </div>
            </div><!--  / .group /  -->

            <div class="notice">
              <p></p>
            </div><!--  / .notice /  -->
            <div class="submit">
              <button type="submit" class="button">確認画面へ <i class="icon ion-md-arrow-forward"></i></button>
            </div>
          </form>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php include __DIR__ . '/_module_footer.inc'; ?>

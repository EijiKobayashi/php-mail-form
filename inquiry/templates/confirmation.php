<?php
if (!defined('APP_RUN')) {
    http_response_code(403);
    exit('Access denied');
}
$page_title = '確認画面';
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
              <li class="step step1"><span>入力</span></li>
              <li class="step step2 is-current"><span>確認</span></li>
              <li class="step step3"><span>完了</span></li>
            </ol>
          </div><!--  / .steps /  -->
          <p class="inquiry__notice">必要事項をご確認の上、「送信する」ボタンを押してください。</p>
          <form method="post" action="/inquiry/send/" name="application" id="application" class="application">
            <input type="hidden" name="token" id="token" value="<?= h(generate_csrf_token()) ?>" />
            <input type="hidden" name="subject" value="<?= h($form_data['subject']) ?>" />
            <input type="hidden" name="name" id="name" value="<?= h($form_data['name']) ?>" />
            <input type="hidden" name="email" id="email" value="<?= h($form_data['email']) ?>" />
            <input type="hidden" name="postal_code" id="postal_code" value="<?= h($form_data['postal_code']) ?>" />
            <input type="hidden" name="address" id="address" value="<?= h($form_data['address']) ?>" />
            <input type="hidden" name="phone" id="phone" value="<?= h($form_data['phone']) ?>" />
            <input type="hidden" name="content" id="content" value="<?= h($form_data['content']) ?>" />

            <div class="group">
              <div class="label"><label>件名:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['subject']) ? h($form_data['subject']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>お名前:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['name']) ? h($form_data['name']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>メールアドレス:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['email']) ? h($form_data['email']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>郵便番号:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['postal_code']) ? h($form_data['postal_code']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>ご住所:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['address']) ? h($form_data['address']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>電話番号:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['phone']) ? h($form_data['phone']) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label>質問等:</label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?= !empty($form_data['content']) ? nl2br(h($form_data['content'])) : '─' ?>
                </div>
              </div>
            </div><!--  / .group /  -->

            <div class="notice">
              <p></p>
            </div><!--  / .notice /  -->
            <div class="submit">
              <button type="submit" class="button">送信する <i class="icon ion-md-arrow-forward"></i></button>
              <button type="submit" name="back" value="1" formaction="/inquiry/" class="button button__return" formnovalidate><i class="icon ion-md-arrow-back"></i> 戻る</button>
            </div>
          </form>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php include __DIR__ . '/_module_footer.inc'; ?>

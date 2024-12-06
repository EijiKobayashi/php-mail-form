<?php
session_start();
if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}
$page_title = '入力フォーム画面';
?>
<?php include(dirname(__FILE__) . '/_module_header.inc'); ?>

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
          <form method="post" action="/inquiry/confirmation" name="application" id="application" class="application h-adr" enctype="multipart/form-data">
            <input type="hidden" class="p-country-name" value="Japan">
            <input type="hidden" name="check" id="check" value="confirmation" />
            <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>" />
            <div class="group">
              <div class="label"><label for="subject">件名 <span class="require">必須</span></label></div>
              <div class="control">
                <select name="subject" class="large">
                  <option value="">件名を選択してください</option>
                  <option value="お問い合わせ"
                    <?php if ($subject === 'お問い合わせ') {
                      echo ' selected="selected"';
                    } ?>>お問い合わせ</option>
                  <option value="資料請求"
                    <?php if ($subject === '資料請求') {
                      echo ' selected="selected"';
                    } ?>>資料請求</option>
                </select>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="name">お名前 <span class="require">必須</span></label></div>
              <div class="control">
                <input type="text" name="name" id="name" class="large" maxlength="100" size="" value="<?php echo $name; ?>" placeholder="お名前" required />
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="email">メールアドレス <span class="require">必須</span></label></div>
              <div class="control">
                <input type="email" name="email" id="email" class="large" maxlength="100" size="" value="<?php echo $email; ?>" placeholder="メールアドレス" data-conv-half-alphanumeric="true" required />
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="postal_code">郵便番号</label></div>
              <div class="control">
                <input type="text" name="postal_code" id="postal_code" class="medium p-postal-code" maxlength="8" value="<?php echo $postal_code; ?>" placeholder="郵便番号" pattern="\d*" />
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="address">ご住所 </label></div>
              <div class="control">
                <input type="text" name="address" id="address" class="large p-region p-locality p-street-address p-extended-address" maxlength="512" size="" value="<?php echo $address; ?>" placeholder="住所" />
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="phone">電話番号 </label></div>
              <div class="control">
                <input type="tel" name="phone" id="phone" class="large" maxlength="12" size="" value="<?php echo $phone; ?>" placeholder="電話番号" />
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="content">質問等 <span class="require">必須</span></label></div>
              <div class="control">
                <textarea name="content" id="content" class="large" cols="50" rows="5" placeholder="質問等をご記載ください" required><?php echo $content; ?></textarea>
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

<?php include(dirname(__FILE__) . '/_module_footer.inc'); ?>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['token'] !== $_SESSION['token']) {
  header('Location: ./error');
  exit;
}
$page_title = '確認画面';
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
              <li class="step step1"><span>入力</span></li>
              <li class="step step2 is-current"><span>確認</span></li>
              <li class="step step3"><span>完了</span></li>
            </ol>
          </div><!--  / .steps /  -->
          <p class="inquiry__notice">必要事項をご入力の上、「送信する」ボタンを押してください。</p>
          <form method="post" action="/inquiry/send/" name="application" id="application" class="application" enctype="multipart/form-data">
            <input type="hidden" name="check" id="check" value="send" />
            <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
            <input type="hidden" name="session_token" id="session_token" value="<?php echo $session_token; ?>" />
            <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
            <input type="hidden" name="name" id="name" value="<?php echo $name; ?>" />
            <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
            <input type="hidden" name="postal_code" id="postal_code" value="<?php echo $postal_code; ?>" />
            <input type="hidden" name="address" id="address" value="<?php echo $address; ?>" />
            <input type="hidden" name="phone" id="phone" value="<?php echo $phone; ?>" />
            <input type="hidden" name="content" id="content" value="<?php echo $content; ?>" />
            <div class="group">
              <div class="label"><label for="subject">件名: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($subject) {
                    echo $subject;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->

            <div class="group">
              <div class="label"><label for="name">お名前: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($name) {
                    echo $name;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="email">メールアドレス: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($email) {
                    echo $email;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="postal_code">住所: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($postal_code) {
                    echo $postal_code;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="address">ご住所: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($address) {
                    echo $address;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="phone">電話番号: <!--span class="require">必須</span--></label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($phone) {
                    echo $phone;
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="group">
              <div class="label"><label for="content">質問等: </label></div>
              <div class="control">
                <div class="control__confirmation">
                  <?php if ($content) {
                    echo nl2br($content);
                  } else {
                    echo '─';
                  } ?></div>
              </div>
            </div><!--  / .group /  -->
            <div class="notice">
              <p></p>
            </div><!--  / .notice /  -->
            <div class="submit">
              <button type="submit" class="button"
                <?php if (is_valid_required() == FALSE) {
                  echo ' disabled="disabled"';
                } ?>>送信する <i class="icon ion-md-arrow-forward"></i></button>
              <a href="javascript:document.forms.application.action='/inquiry/';document.forms.application.submit();" class="button button__return"><i class="icon ion-md-arrow-back"></i> 戻る</a>
            </div>
          </form>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php include(dirname(__FILE__) . '/_module_footer.inc'); ?>
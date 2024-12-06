<?php
$page_title = '完了画面';
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
              <li class="step step2"><span>確認</span></li>
              <li class="step step3 is-current"><span>完了</span></li>
            </ol>
          </div><!--  / .steps /  -->
          <form>
            <div class="inquiry__thanks">
              <h4><i class="icon ion-md-paper-plane"></i></h4>
              <p>お問い合わせありがとうございました。<br>後ほど、スタッフよりメールまたはお電話にて連絡をさせていただきます。<br>今しばらくお待ちくださいますようお願い申し上げます。</p>
              <p>1週間以上連絡がない場合は、<br>お手数ですがお電話 03-0000-2222 までご連絡いただけますと幸いです。</p>
            </div>
            <div class="submit">
              <a href="/" class="button button__return"><i class="icon ion-md-arrow-back"></i> ホームへ戻る</a>
            </div>
          </form>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php
session_start();
session_unset();
session_destroy();
include(dirname(__FILE__) . '/_module_footer.inc'); ?>
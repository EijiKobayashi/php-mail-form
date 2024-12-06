<?php
session_start();
session_unset();
session_destroy();
$page_title = 'エラー画面';
?>
<?php include(dirname(__FILE__) . '/_module_header.inc'); ?>

<main>
  <div class="main">
    <div class="main__inner">

      <section>
        <div class="inquiry section">
          <h2 class="inquiry__title section__title">お問い合わせ</h2>

          <form>
            <div class="inquiry__thanks">
              <h4>エラーです。</h4>
            </div>
            <div class="submit">
              <a href="./inquiry" class="button button__return"><i class="icon ion-md-arrow-back"></i> メールフォームへ戻る</a>
            </div>
          </form>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php include(dirname(__FILE__) . '/_module_footer.inc'); ?>
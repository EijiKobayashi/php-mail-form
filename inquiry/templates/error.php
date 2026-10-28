<?php
if (!defined('APP_RUN')) {
    http_response_code(403);
    exit('Access denied');
}
$page_title = 'エラー画面';
?>
<?php include __DIR__ . '/_module_header.inc'; ?>

<main>
  <div class="main">
    <div class="main__inner">

      <section>
        <div class="inquiry section">
          <h2 class="inquiry__title section__title">お問い合わせ</h2>

          <div class="inquiry__thanks">
            <h4>不正なアクセス、またはセッションの有効期限が切れました。</h4>
            <p>お手数ですが、もう一度最初から入力をお願いいたします。</p>
          </div>
          <div class="submit">
            <a href="/inquiry/" class="button button__return"><i class="icon ion-md-arrow-back"></i> メールフォームへ戻る</a>
          </div>
        </div><!--  / .inquiry /  -->
      </section>

    </div><!--  / .main__inner /  -->
  </div><!--  / #main /  -->
</main>

<?php
clear_csrf_token();
include __DIR__ . '/_module_footer.inc';
?>

<footer>
  <div class="footer">
    <address class="footer__copyrights">&#169; <?php echo date('Y'); ?> Oshinco Co-po. All Rights Reserved.</address>
  </div><!--  / #footer /  -->
</footer>

<!--script type='text/javascript' src='/assets/js/jquery-3.4.1.min.js'></script-->
<script src="https://yubinbango.github.io/yubinbango/yubinbango.js"></script>

<?php
/*
session_start();
// session_regenerate_id();
$token = session_id();
$_SESSION['token'] = $token;
header("Content-Type: text/javascript; charset=utf-8");
echo '<script>';
echo 'document.addEventListener("DOMContentLoaded", function() {' . "\n";
echo '  var tokenInput = document.querySelector("input#token");' . "\n";
echo '  if (tokenInput) {' . "\n";
echo '    tokenInput.value = "' . $token . '";' . "\n";
echo '  }' . "\n";
echo '});' . "\n";
echo '</script>';
*/
?>

</body>

</html>
<?php
header("Content-Type: text/html; charset=utf-8");
header("Cache-Control: Private");

// require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/wp-load.php'); // WordPressを読み込む場合
require_once('./config.php');

define('INDEX', './templates/form.php');
define('CONFIRMATION', './templates/confirmation.php');
define('THANKS', './templates/thanks.php');
define('ERROR', './templates/error.php');
define('SEND', './templates/send.php');

if (isset($_GET['q'])) {
  $query = $_GET['q'];
}

if ($query === "error") {
  require_once(ERROR);
} elseif ($query === "confirmation") {
  if ($check === "confirmation" && is_valid_required() === TRUE) {
    require_once(CONFIRMATION);
  } else {
    require_once(INDEX);
  }
} elseif ($query === "send") {
  if ($check === "send" && is_valid_required() === TRUE) {
    require_once(SEND);
  } else {
    require_once(INDEX);
  }
} elseif ($query === "thanks") {
  require_once(THANKS);
} else {
  require_once(INDEX);
}

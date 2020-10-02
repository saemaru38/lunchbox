<!--ログアウト機能-->
<?php $page_title = 'log_out'; ?>
<?php include('6_header.php'); ?>
<?php
// セッションを開始
session_start();
// セッションを破棄
$_SESSION = array();
session_destroy();
?>
<div class = "center">
    <h2>ログアウトしました</h2>
    <a class = "button" href = '6_log_in.php'>ログインページに戻る</a>
</div>
</body>
</html>
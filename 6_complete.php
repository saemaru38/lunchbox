<!--登録完了の画面-->
<?php $page_title = 'complete'; ?>
<?php include('6_header.php'); ?>
    <p>登録が完了しました</p>
    <a href="https://portal.tech-base.net/storage/userfile/u43713/6_log_in.php"
    target="_blank">ログインはこちら</a>
    
    <?php
        session_start();
        if(!isset($_SESSION["user_id"])) {
            header("Location: 6_log_in.php");
            exit;
        }

    ?>
	</body>
</html>
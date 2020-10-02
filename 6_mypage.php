<!--マイページ-->
<?php $page_title = 'mypage'; ?>
<?php include('6_header.php'); ?>
<h2><?php
    //ログインしていない場合
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header("Location: 6_log_in.php");
        exit;
    }
    echo "ID:".$_SESSION["user_id"]."<br>"
?></h2>
<div class = "right">
    <a class = "button" href="6_post.php">投稿する</a>
    <a href = '6_log_out.php'>ログアウト</a>
</div>
<?php
//    echo "マイページです";
//    echo $_SESSION["user_id"]."さんのページ<br>";
    
    //########接続########
    $dsn = 'mysql:dbname=**********;host=localhost';
    $user = 'USER';
    $password = 'PASSWORD';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //入力したデータをselectによって表示
    $sql = 'SELECT * FROM 6_post';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
?>
    <?php foreach ($results as $row):?>
        <?php if($row['user_id'] == $_SESSION["user_id"]):?>
        <img src="images/<?php echo $row['image_name']; ?>" width="300" height="300">
        <?php echo "<br>".$row['cmt']."<br>";?>
        <?php endif;?>
    <?php endforeach;?>
    

    </body>
</html>
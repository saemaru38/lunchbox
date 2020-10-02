<!--管理者ページ-->
<?php $page_title = 'manage'; ?>
<?php include('6_header.php'); ?>
<h2>管理者ページ</h2>
<?php
    //########データベースに接続########
    $dsn = 'mysql:dbname=**********;host=localhost';
	$user = 'USER';
	$password = 'PASSWORD';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    echo "##############ユーザ一覧#########################<br>";

    //########入力したデータを表示########
    $sql = 'SELECT * FROM users';
    $stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
    foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	    echo $row['id'].',';
        echo $row['name'].',';
        echo $row['email'].',';
    	echo $row['user_id'].',';
		echo $row['pass'].'<br>';
	    echo "<hr>";
    }
    
    echo "##############投稿一覧###########################<br>";
    
    
    //入力したデータをselectによって表示
    $sql = 'SELECT * FROM 6_post';
    $stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
?>
<!--投稿内容を表示-->
<?php foreach ($results as $row):?>
<img src="images/<?php echo $row['image_name']; ?>" width="300" height="300">
<?php echo $row['num'].',';
	  echo $row['user_id'].',';
      echo $row['cmt'].',';
      echo $row['image_name'];
?>
<?php echo "<br><hr>";?>
<?php endforeach;?>
<?php echo "##############################################<br>";?>
</body>
</html>
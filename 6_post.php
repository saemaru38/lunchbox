<!--投稿フォーム-->
<?php $page_title = 'post'; ?>
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
<div class = "right button"><br><a href="6_mypage.php">マイページへ</a></div>
<?php

    $image = ""; //画像
    $user_id = ""; //ユーザID
    $cmt = ""; //コメント
    $post = ""; //投稿ボタン
    
    //####送信ボタンが押されたときの処理####        
    if(isset($_POST["post"]) == true){
//        $image = $_POST["image"];
//        $user_id = $_POST["user_id"]; 
        $cmt = $_POST["cmt"];
        $post = $_POST["post"];
    }
            
    //########接続########
    $dsn = 'mysql:dbname=**********;host=localhost';
    $user = 'USER';
    $password = 'PASSWORD';
    $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //########テーブルを作成########
    $sql = "CREATE TABLE IF NOT EXISTS 6_post" //entry6_2というテーブルを作成
    ."("
    . "num INT AUTO_INCREMENT PRIMARY KEY," //管理ナンバー
    . "user_id char(8)," //ユーザID
    . "cmt TEXT," //コメント
    . "image_name TEXT" //画像の名前
//  . "pic char(32)"
    . ");";
    $stmt = $pdo->query($sql);
    
    //########テーブルの削除########
    /*$sql = 'DROP TABLE 6_post';
    $stmt = $pdo->query($sql);
	echo "テーブルを消去しました";
	*/
	
    try {
        $dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
        echo $e->getMessage();
    }

    //####画像のアップロード####
    $image_err_f = "false"; //画像の有無を表すフラグ
    if (isset($_POST['post'])) {//送信ボタンが押された場合
        $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
        $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
        $file = "images/$image";
//        $sql = "INSERT INTO images(name) VALUES (:name)";
//        $stmt = $dbh->prepare($sql);
//        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        if (!empty($_FILES['image']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
            move_uploaded_file($_FILES['image']['tmp_name'], './images/' . $image);//imagesディレクトリにファイル保存
            if (exif_imagetype($file)) {//画像ファイルかのチェック
                $message = '画像をアップロードしました';
                $image_err_f = "true";
                $stmt->execute();
            } else {
                $message = '画像ファイルではありません';
            }
        }
    }

    ?>
    
    <!--送信ボタンが押された場合-->
    <?php if (isset($_POST['post'])): ?>
    <p><?php echo $message; ?></p>
    <?php endif;?>
    
    <?php
    $err_f = "false"; //入力エラーフラグ
    /*if($image_err_f == "true" && !empty($user_id)&&isset($user_id) 
    && !empty($cmt)&&isset($cmt)){*/
    if($image_err_f == "true" && !empty($cmt)&&isset($cmt)){
        $err_f = "true";
        //作成したテーブルにINSERTを使ってデータを入力する
        $sql = $pdo -> prepare("INSERT INTO 6_post (user_id, cmt, image_name) 
        VALUES (:user_id, :cmt, :image_name)");
//        $sql -> bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sql -> bindParam(':user_id', $_SESSION["user_id"], PDO::PARAM_STR);
        $sql -> bindParam(':cmt', $cmt, PDO::PARAM_STR);
        $sql -> bindParam(':image_name', $image, PDO::PARAM_STR);
        $sql -> execute();
        header("Location:6_mypage.php");
    }
?>
        
<!--送信ボタンが押された場合-->
    <p>画像を選択してください</p>
    <form method="post" enctype="multipart/form-data">
    <input type="file" name="image" required = "required"><br><br>
    <!--ユーザID<br>
    <input type = "text" name = "user_id" required placeholder = "ユーザID"
    value = "<?php /*if($err_f == "false"){ echo $user_id; }*/?>"
    ><br>-->
    感想<br>
    <textarea name="cmt" required placeholder = "感想" cols="40" rows="8"
    width = "200px" height = "30px"
    value = "<?php if($err_f == "false"){ echo $cmt; }?>"
    ></textarea>
    <button><input type="submit" name="post" value="投稿"></button>
</form>
</body>
</html>
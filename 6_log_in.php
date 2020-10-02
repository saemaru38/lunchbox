<!--ログイン機能-->
<?php $page_title = 'log_in'; ?>
<?php include('6_header.php'); ?>
    <?php
        //########データベースに接続########
        $dsn = 'mysql:dbname=**********;host=localhost';
	    $user = 'USER';
	    $password = 'PASSWORD';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    
        session_start();
        $error_message = "";
        
        $user_id = ""; //新規ユーザのユーザID
        $pass = ""; //新規ユーザのパスワード
        $log_in = ""; //ログインボタン
        $flag = "false"; //最初にエラーが表示されないようにするためのフラグ
            
        if(isset($_POST["log_in"])){
            $flag = "true";
            $user_id = $_POST["user_id"];
            $pass = $_POST["pass"];
            $log_in = $_POST["log_in"]; 
        }
        
        if(isset($log_in)) {
            $sql = 'SELECT * FROM users';
            $stmt = $pdo->query($sql);
        	$results = $stmt->fetchAll();
            foreach ($results as $row){
                if($user_id == $row['user_id'] && $pass == $row['pass']) {    
                    $_SESSION["user_id"] = $user_id;
                    header("Location: 6_mypage.php");   
                    exit;
                }
            }
        }
    ?>
    <div class = "center">
    <form action = "" method = "post">
        <h2>ログイン</h2>
        <?php if($flag == "true"):?>
        <?php echo "※ユーザIDまたはパスワードが違います。<br>もう一度入力して下さい。<br>";?>
        <?php endif;?>
        <input type = "text" name = "user_id" placeholder = "ユーザID" class = "sample1"><br>
        <input type = "password" name = "pass" placeholder = "パスワード" class = "sample1"><br>
        <input type = "submit" name = "log_in" value = "ログイン" class = "sample1"><br>
        <p>ユーザIDとパスワードを入力してください</p>
    </form>
    <a href="6_index.php">新規登録はこちら</a>
    </div>
        
    </body>
</html>
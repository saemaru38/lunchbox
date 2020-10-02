<!--ホーム画面，新規登録画面-->
<?php $page_title = 'index'; ?>
<?php include('6_header.php'); ?>
        <!--<h1 id = "midashi_1"><a href="6_home.php">お弁当掲示板</a></h1>-->
        <div class = "center">
            <p>「お弁当掲示板」は、お弁当を作ってくれている人への感謝を伝える掲示板です。</p>
            <p>日ごろなかなか伝えることのできない「ありがとう」を伝えてみませんか？</p>
            <a href="6_log_in.php">ログインはこちら</a>
        </div>

    <?php
        //########会員登録情報########
        $name = ""; //新規ユーザの名前
        $email = ""; //新規ユーザのe-mail
        $user_id = ""; //新規ユーザのユーザID
        $pass = ""; //新規ユーザのパスワード
        $entry = "";
        $err_f = "false"; //入力エラーフラグ
        
        if(isset($_POST["entry"]) == true){
            $name = $_POST["name"]; 
            $email = $_POST["email"];
            $user_id = $_POST["user_id"];
            $pass = $_POST["pass"];
            $entry = $_POST["entry"];
        }
        
        
        //########データベースに接続########
        $dsn = 'mysql:dbname=**********;host=localhost';
	    $user = 'USER';
	    $password = 'PASSWORD';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    //echo "接続できました"."<br>";
	    
	    //########データベースにテーブルを作成########
        $sql = "CREATE TABLE IF NOT EXISTS users" //iinformationというテーブルを作成
        ."("
        . "id INT AUTO_INCREMENT PRIMARY KEY," //自動で登録されているナンバリング
        . "name char(10)," //氏名(文字列，半角英数10文字)
        . "email TEXT," //e-mail
        . "user_id char(8)," //ユーザID
        . "pass char(8)" //パスワード
        . ");";
        $stmt = $pdo->query($sql);
        
        //#####同じIDが存在しないか#####
        $id_err_f = "true";
        $sql = 'SELECT * FROM users';
        $stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['user_id'] == $user_id){
                echo "このIDは既に使用されています";
                $id_err_f = "false";
                break;
            }
        }
        
        
		
        //########メールについて########
        require 'src/Exception.php';
        require 'src/PHPMailer.php';
        require 'src/SMTP.php';
        require 'setting.php';
        
        //########会員登録########
        if($id_err_f == "true"){
        if(!empty($name) && isset($name)){
            if(!empty($email)&&isset($email)){
                if(!empty($user_id)&&isset($user_id)){
                    if(!empty($pass)&&isset($pass)){
                        //$flag = "y";
                        $err_f = "true";
                        //########usersにデータを入力########
                        $sql = $pdo -> prepare("INSERT INTO users (name, email, user_id, pass) VALUES (:name, :email, :user_id, :pass)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':email', $email, PDO::PARAM_STR);
                        $sql -> bindParam(':user_id', $user_id, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $sql -> execute();
                        // PHPMailerのインスタンス生成
                        $mail = new PHPMailer\PHPMailer\PHPMailer();
            
                        $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
                        $mail->SMTPAuth = true;
                        $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
                        $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
                        $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
                        $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
                        $mail->Port = SMTP_PORT; // 接続するTCPポート

                        // メール内容設定
                        $mail->CharSet = "UTF-8";
                        $mail->Encoding = "base64";
                        $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
                        $mail->addAddress($email, $name.'さん'); //受信者（送信先）を追加する
            //          $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
            //          $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
            //          $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
                        $mail->Subject = MAIL_SUBJECT; // メールタイトル
                        $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
                        $body = 'メールの中身';

                        $mail->Body  = $body; // メール本文
                        // メール送信の実行
                        if(!$mail->send()) {
                	        echo 'メッセージは送られませんでした！';
    	                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            echo '送信完了！';
                            header("Location:6_complete.php"); //ログインページに移動
                        }   
                    } else {
                        echo "パスワードを入力してください.<br>";
                    }
                } else {
                    echo "ユーザIDを入力してください.<br>";
                }
            } else {
                echo "e-mailを入力してください";
            }
        } else if(!empty($_POST["entry"])&&isset($_POST["entry"])){
            echo "氏名を入力してください.<br>";
        }
        }
        ?>
        <div class = "center title">
            <form action = "" method = "post">
            <h2>新規登録フォーム</h2>
            氏名<br>
            <input type = "text" name = "name" placeholder = "氏名" class = "sample1"
            value = "<?php if($err_f == "false"){ echo $name; }?>"
            ><br><br>
            E-mail<br>
            <input type = "email" name = "email" placeholder = "E-mail" class = "sample1"
            value = "<?php if($err_f == "false"){ echo $email; }?>"
            ><br><br>
            ユーザID(半角英数8文字以内)<br>
            <input type = "text" name = "user_id" placeholder = "ユーザID" class = "sample1"
            value = "<?php if($err_f == "false"){ echo $user_id; }?>"
            ><br><br>
            パスワード(半角英数8文字以内)<br>
            <input type = "password" name = "pass" placeholder = "パスワード" class = "sample1"
            value = "<?php if($err_f == "false"){ echo $pass; }?>"
            ><br><br>
            <input type = "submit" name = "entry" value = "登録" class = "sample1"><br>
        </form>
        </div>
    </body>
</html>
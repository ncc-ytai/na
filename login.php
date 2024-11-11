<?php
require('utils.php');
$error_message = '';

if(isset($_POST['userid']) && isset($_POST['password'])){
    if(!alphanum_check($_POST['userid'])){
        //ユーザーIDが英数字以外の場合(空白含む)
        $error_message = 'ユーザーIDまたはパスワードが不正です';
    }
    if(!alphanum_check($_POST['password'])){
        //パスワードが英数字以外の場合(空白含む)
        $error_message = 'ユーザーIDまたはパスワードが不正です';
    }
    if(empty($error_message)){
        //エラーがなければ実行をする
        //echo "OK";

        $userid = $_POST['userid'];
        $password = $_POST['password'];

        try{
            //データベースの接続
            $dsn = new PDO('mysql:host=localhost; dbname=login; charset=utf8','root','');
        }catch(PDOException $e){
            //データベース接続エラーの時エラーメッセージを出力して終了する
            exit($e->getMessage());
        }
        //クリエにPOSTされたuseridと同じIDパスワードを取得する
        //$query = $dsn->prepare('SELECT password FROM user WHERE userid = :userid');
        $query = $dsn->prepare('SELECT * FROM user WHERE userid = :userid');
        //SQL文をセットした後にパラメータ(:userid)に値をセットする
        $query->bindValue('userid', $userid, PDO::PARAM_STR);

        //クエリを実行
        $query ->execute();

        //結果を表示
        $result = $query->fetch();

        //送信したパスワードとデータベース上のパスワードを比較する
        if($result !==FALSE && $password === $result['password']){
            //パスワードが一致した場合
            //echo 'ログイン成功';
            echo "<table border=1>";
            echo "<tr>";
            echo "<td>{$result['userid']}</td>";
            echo "<td>{$result['password']}</td>";
            echo "</tr>";
        }else{
            $error_message = "ユーザーIDまたはパスワードが違います。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
    </head>
    <body>
        <?php
            if(isset($_POST['userid'])){
                echo $_POST[userid];
            }
        ?>
        <form action="" method="post">
            <p><span>ユーザー名</span><input type="text" name="userid"></p>
            <p><span>パスワード</span><input type="text" name="password"></p>
            <p><input type="submit" value="ログイン">
        </form>
    </body>
</html>


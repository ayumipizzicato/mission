<!-- ver.2 -->
<!--見返して復習できるように、デバック等を残しています。-->
<!--データベース名、ユーザー名、パスワードの入力は、全2か所-->

<?php

#データベースへ接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

#テーブルを作成　テーブル名に-つけられないの意外と注意ポイントな気がする
    $sql = "CREATE TABLE IF NOT EXISTS table5_1t"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"  #文字列、半角英数で32文字
    . "comment TEXT,"   #文字列
    . "t TEXT," #文字列　
    . "pass char(10)"   #文字列、半角英数で10文字
    .");";
    $stmt = $pdo->query($sql);
#echo "作成、異常なし<br><br>";

#デバック用
#echo "接続異常なし<br>";

if(!empty($_POST["name"])||!empty($_POST["comment"])||!empty($_POST["pass"])||!empty($_POST["ediNo"])
    ||!empty($_POST["edi"])||!empty($_POST["passE"])
    ||!empty($_POST["del"])||!empty($_POST["passD"])){
        
    #定義
    $name_p=$_POST["name"]; #投稿フォーム名前
    $comment_p=$_POST["comment"]; #コメント
    $pass_p=$_POST["pass"]; #パスワード
    $edinow_p=$_POST["ediNo"]; #編集中番号
    
    $edi_p=$_POST["edi"]; #編集予定番号
    $passE_p=$_POST["passE"];
    
    $del_p=$_POST["del"]; #削除番号
    $passD_p=$_POST["passD"];
    
    $day_p=date("Y/m/d/H:i:s");
    
#デバック用
#echo $name_p.",".$comment_p.",".$day_p.",".$pass_p."<br>POST読み取り異常なし<br><br>";

    #保存
    if( !empty($_POST["pass"])
        &&!empty($_POST["name"])
        &&!empty($_POST["comment"])
        &&empty($_POST["ediNo"])){
    #以下新規投稿処理
            
#デバック用            
#echo "if新規投稿異常なし<br>";
#echo $name_p.$comment_p.$day_p.$pass_p."<br><br>";
#メモ　ここまで問題なし
    
    #データベースへ入力
            $name =  $name_p ;
            $comment = $comment_p ; 
            $time = $day_p;
            $pass = $pass_p ;
        
            $sql = $pdo -> prepare("INSERT INTO table5_1t (name, comment, t, pass) VALUES (:name, :comment, :t, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':t', $time, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            
            $sql -> execute();
  

        } #保存if 閉じ
        elseif(!empty($_POST["pass"])
                &&!empty($_POST["name"])
                &&!empty($_POST["comment"])
                &&!empty($_POST["ediNo"])){
                
#デバック用
#echo "保存　編集if異常なし<br>";
#echo $edinow_p.",".$name_p.",".$comment_p.",".$day_p.",".$pass_p."<br><br>";

    #以下編集保存処理
                $id = $edinow_p; //変更する投稿番号
                $name = $name_p;
                $comment = $comment_p; 
                $time = $day_p;
                $pass = $pass_p;
                $sql = 'UPDATE table5_1t SET name=:name,comment=:comment,t=:t,pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':t', $time, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->execute();
                    
                } #編集if 閉じ
       

    #編集処理
    if( !empty($_POST["edi"])
        &&!empty($_POST["passE"])){
        
#デバック用
#echo "編集if異常なし<br>";
#echo "編集する投稿番号は ".$edi_p." <br><br>";

    #編集予定番号=投稿番号のデータを変数に代入
        $id = $edi_p ;
        $sql = 'SELECT * FROM table5_1t WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(); 
            foreach ($results as $row){
/* デバック用
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['t'].',';
                echo $row['pass'];
                echo "<hr>";
*/

    #データベースの値を変数に代入
                $id_ep = $row['id'];
                $name_ep = $row['name'];
                $comment_ep = $row['comment'];
                $pass_ep = $row['pass'];

            } #foreach閉じ

    }   #編集処理if閉じ


    #削除処理、削除
    if( !empty($_POST["del"])
        &&!empty($_POST["passD"])){
    
    
            #投稿番号のデータを変数に代入
        $id = $del_p ;
        $sql = 'SELECT * FROM table5_1t WHERE id=:id ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(); 
            foreach ($results as $row){
    #データベースの値を変数に代入
                $id_dp = $row['id'];
#                $name_ep = $row['name'];
#                $comment_ep = $row['comment'];
                $pass_dp = $row['pass'];  
#デバック用            
#echo $id_dp.",".$pass_dp;       
    
        if($del_p==$id_dp
            &&$pass_dp==$passD_p){
                            
#デバック用     
#echo "削除if異常なし<br>".$del_p."を削除<br><br>";
            
        $id = $del_p;
        $sql = 'delete from table5_1t where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
            
        } #パスワード合ってるかどうか
        }} #削除処理閉じ
        
} #全体if閉じ 

?>

<html>
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    mission_5-1<br>
    <hr>
    <form action="" method="post">
        <form action="" method="post">
<!--新規投稿フォーム-->
        名前を入力してください：<br>
        <input type="text" name="name" placeholder="name" 
        value='<?php 
                    if(!empty($_POST["edi"])){
    
                        if($edi_p==$id_ep
                            &&$pass_ep==$passE_p){
                            echo $name_ep;
                        }
                    }
                ?>'  >
        <br>
        コメントを入力してください:<br>
        <input type="text" name="comment" placeholder="comment" 
        value='<?php 
                    if(!empty($_POST["edi"])){
    
                        if($edi_p==$id_ep
                            &&$pass_ep==$passE_p){
                            echo $comment_ep;
                        }
                    }
                ?>'  >

        <!--以下見えないようにする-->
        <!--編集中番号-<br>-->
        <input type="hidden" name="ediNo" 
        value='<?php 
                    if(!empty($_POST["edi"])){
    
                        if($edi_p==$id_ep
                            &&$pass_ep==$passE_p){
                            echo $id_ep;
                        }
                    }
                ?>'>
        <!--ここまで-->
        
        <br>
        パスワードを入力してください:<br>
        <input type="text" name="pass" placeholder="pass">
        <input type="submit" name="submit">
        
        <br><br>
<!--編集フォーム-->
        編集する投稿の番号を入力してください:<br>
        <input type="number" name="edi" placeholder="number">
         <br>
        パスワードを入力してください:<br>
        <input type="text" name="passE" placeholder="pass">
        <input type="submit" name="edition" value="決定">
        
<!--削除フォーム-->
        <br><br>
        削除する番号を入力してください:<br>
        <input type="number" name="del" placeholder="number">
         <br>
        パスワードを入力してください:<br>
        <input type="text" name="passD" placeholder="pass">
        <input type="submit" name="delete" value="決定">
    </form>

投稿一覧<br> <hr><hr>
<?php

#データベースへ接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
#出力
    $sql = 'SELECT * FROM table5_1t';
    
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        
        echo "#".$row['id'];
        echo "  ".$row['comment']. "  ";
        echo "by.".$row['name'].' ,';
        echo $row['t'];
#        echo $row['pass'].',';
    echo "<hr>";
    }

?>


</body>
</html>

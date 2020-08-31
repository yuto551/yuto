<?php
$name="";
$comment="";
$pass="";

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$sql = "CREATE TABLE IF NOT EXISTS keijiban"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass char(16)"
	.");";
	$stmt = $pdo->query($sql);
?>

<?php
#編集番号指定フォームの受信
if(isset($_POST["edit"])){
$edit_number=$_POST["edit"];#編集番号の設定;
$pass=$_POST["pass_edit"];
    $sql = 'select * from keijiban where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $edit_number, PDO::PARAM_INT);
	$stmt->execute();
	$results=$stmt ->fetchAll();
	if($results[0]['pass'] == $pass){ 
	if($results[0]['pass']==$pass){ 
        $name=$results[0]['name'];
        $comment=$results[0]['comment'];
        echo "編集します";
    }else{
    echo "編集できません";
    }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
         <form method="post" action="5-1.php">
        <input type="hidden" name="edit_number" 
        value="<?php if($edit_number){echo $edit_number;}?>">
        [投稿フォーム]<br>
        <input type="text" name="name" 
        value="<?php if($name){echo $name;}?>">
        <input type="text" name="comment" 
        value="<?php if($comment){echo $comment;}?>">
        <input type="text" name="pass_new" placeholder="パスワード">
        <input type="submit" value="送信"><br>
        </form>
        <form method="post" action="5-1.php">
        [編集フォーム]<br>
        <input type="number"name="edit" placeholder="編集番号">
        <input type="text" name="pass_edit" placeholder="パスワード">
        <input type="submit" value="編集">
    </form>
    <form method="post" action="5-1.php">
        [削除フォーム]<br>
        <input type="number"name="deletenum" placeholder="削除番号">
        <input type="text" name="pass_delete" placeholder="パスワード">
        <input type="submit" value="削除">
    </form>
    
    
    <?php
    #フォームの受信
    if(isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["pass_new"])){
        if($_POST["edit_number"]!=0 ){
            $edit_number=
            $_POST["edit_number"];
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $pass=$_POST["pass_new"];
            $sql="update keijiban set name = :name, comment =:comment, pass =:pass where id =:id";
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $edit_number, PDO::PARAM_INT);
            	$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	            $stmt->execute();
            echo "編集しました<br>";
    }
    else{
        
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $pass=$_POST["pass_new"];
    #日付のデータ
    $date=date("Y年m月d日 H時i分s秒");
    $sql = $pdo -> prepare("INSERT INTO keijiban (name, comment, pass) VALUES (:name, :comment, :pass)");
	$sql -> bindParam(":name", $name, PDO::PARAM_STR);
	$sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
	$sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
	$sql -> execute();
        echo "投稿を受け付けました<br>";
    }
    }
if(isset($_POST["deletenum"])){
    $delete_num=$_POST["deletenum"];
    $pass=$_POST["pass_delete"];
    $id = $delete_num;
    $sql = "SELECT * FROM keijiban where id=:id"; 
    $stmt = $pdo->prepare($sql);//SQLの準備
    $stmt->bindParam(':id', $delete_num, PDO::PARAM_INT);//差し替えるパラメータの値を指定
    $stmt->execute();                             
    $results = $stmt->fetchAll();
    if($results[0]['pass'] == $pass){ 
    if($results[0]['pass'] == $pass){
	$sql = 'delete from keijiban where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $delete_num, PDO::PARAM_INT);
	$stmt->execute();
	echo "削除しました<br>";
    }else{
        echo "ERROR<br>";
    }
    }
}
    $sql = 'SELECT * FROM keijiban';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row["id"].',';
		echo $row["name"].',';
		echo $row["comment"].'<br>';
	echo "<hr>";
	}
    ?>
    </body>
    </html>
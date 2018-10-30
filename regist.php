<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body>

<?php

$hostname = "localhost";
$uname = "root";
$upass = "root";
$dbname = "php_test";
$tblname = "user_data";

//MySQL に接続する。
require_once('dbconnect.php');
$res_dbcon = new MySqlConnection($hostname , $uname , $upass , $dbname);

//UUID作成
$id = md5(uniqid(rand(),1));
$sql = "SELECT user_id from " . $tblname . " WHERE `user_id`=" . $id;
$user_id = 0;
$res_dbcon->res_bind_exec($sql, $user_id);
while($user_id != 0)
{
  $id = md5(uniqid(rand(),1));
  $sql = "SELECT * from " . $tblname . " WHERE `user_id`=" . $id;
  $res_dbcon->res_bind_exec($sql, $user_id);
}

//DBにユーザデータを保存
$sql = "INSERT INTO " . $tblname . " (user_id,user_name,user_mail,user_text) VALUES " . "(?,?,?,?);";
$res_dbcon->bind_exec($sql, $id, htmlspecialchars($_POST["name"], ENT_QUOTES), htmlspecialchars($_POST["mail"], ENT_QUOTES), nl2br(htmlspecialchars($_POST["comment"], ENT_QUOTES)));

//echo "登録完了。";

//MySQL への接続を切断する。
$res_dbcon->close();

// 結果ページへリダイレクト
if($res_dbcon->result==0)
  header('Location: success.html');
else
  header('Location: failed.html');
?>

</table>
</body>
</html>
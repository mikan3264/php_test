<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body>

<?php
// MySql操作クラス
// prepareして実行するところがまだ効率悪い
// 同じSQLならbindして使いまわせるようにしたい
// 文字列のescapeもしていない
class MySqlConnection
{
  private $res_dbcon;
  public $result = 0;
  
  //------------------------------------------------------------------
  // bindするパラメータの型判定
  private function _create_bind_param_args(&$param)
  {
    $res = "";
    
    // 整数値か？
    if(is_int($param))
    {
      $res = "i";
    }
    // 実数値か？
    else if(is_double($param))
    {
      $res = "d";
    }
    else
    {
      // 文字列かどうか
      if(strpos($param, "\0") === false)
      {
        $res = "s";
      }
      else
      {
        $res = "b";
      }
    }
    
    return $res;
  }
  
  // SQL実行
  private function exec($stmt, &...$res)
  {
    // 実行
    $stmt->execute();
    
    // 指定があれば結果をbind
    if(count($res) > 0)
      $stmt->bind_result(...$res);
    
    $stmt->fetch();
    
    $stmt->close();
  }
  
  //------------------------------------------------------------------
  // コンストラクタ
  function __construct($hostname , $uname , $upass , $dbname)
  {
    $this->connect( $hostname , $uname , $upass , $dbname );
  }
  
  // デストラクタ
  function __destruct()
  {
    $this->close();
  }
  
  // MySqlに接続
  public function connect( $hostname , $uname , $upass , $dbname )
  {
    $this->res_dbcon = new mysqli( $hostname , $uname , $upass , $dbname );
    if ($this->res_dbcon->connect_errno)
    {
      $this->result = 1;
    }
    else
    {
      if (!$this->res_dbcon->set_charset("utf8"))
      {
        $this->result = 2;
      }
      else
      {
        //printf("Current character set: %s\n", $this->res_dbcon->character_set_name());
      }
    }
  }
  
  // 切断
  public function close()
  {
    if($this->res_dbcon)
    {
      $this->res_dbcon->close();
      $this->res_dbcon = null;
    }
  }
  
  // パラメータをbindして実行
  public function bind_exec($sql, ...$params)
  {
    if ($stmt = $this->res_dbcon->prepare($sql))
    {
      $types = "";
      foreach ($params as $n)
      {
        $types .= $this->_create_bind_param_args($n);
      }
      $stmt->bind_param($types, ...$params);
      
      $this->exec($stmt);
    }
  }
  
  // 結果をbindして実行
  public function res_bind_exec($sql, &...$res)
  {
    if ($stmt = $this->res_dbcon->prepare($sql))
    {
      $this->exec($stmt, ...$res);
    }
  }
  
  // 何も指定せず実行
  public function execute($sql)
  {
    if ($stmt = $this->res_dbcon->prepare($sql))
    {
      $this->exec($stmt);
    }
  }
}
?>

</table>
</body>
</html>
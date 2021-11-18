<?php
require_once("functions.php");
define('MAXITEM',5);    

 if($_SERVER['REQUEST_METHOD'] === 'POST'){   

    if(isset($_POST["name"])){ 

        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8'); 

    } 

    $page = 1;   

} elseif($_SERVER['REQUEST_METHOD'] === 'GET'){  

    if (isset($_GET['page'])) { 

        [ページ番号を設定処理1]  

        $name = htmlspecialchars($_GET["name"], ENT_QUOTES, 'UTF-8'); 

    } else { 

        [ページ番号を設定処理2]  

        $name = htmlspecialchars($_GET["name"], ENT_QUOTES, 'UTF-8'); 

    } 
    
    if ($page > 1) { 

           

        [レコードの先頭位置を計算]   

    } else { 

        [レコードの先頭位置を設定]   
    } 

} 
$dbh = db_conn();
$data = [];

try{ 

    $sql = "SELECT * FROM user WHERE name like :name [SQL文に追加]"; 

    $stmt = $dbh->prepare($sql); 

    $stmt->bindValue(':name', '%'.$name.'%', PDO::PARAM_STR); 

    $stmt->bindValue(':start', $start, PDO::PARAM_INT); 

    $stmt->bindValue(':page', MAXITEM, PDO::PARAM_INT); 

    $stmt->execute(); 

    $count = 0; 

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 

        $data[] = $row; 

        $count++; 

    } 
}catch (PDOException $e){
    echo($e->getMessage());
    die();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>検索結果画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
       <div>
            <h1>ユーザー一覧</h1>
       </div>
    </header>
</div>
<hr>
<p><?php echo $count;?>件見つかりました。</p>
<table border=1>
    <tr><th>id</th><th>名前</th><th>メールアドレス</th><th>性別</th></tr>
    <?php foreach($data as $row): ?>
    <tr>
    <td><?php echo $row['id'];?></td>
    <td><?php echo $row['name'];?></td>
    <td><?php echo $row['email'];?></td>
    <td>
        <?php
           if ($row['gender'] === 1) {
              echo "男性";
           } elseif ($row['gender'] === 2) {
              echo "女性";
           } else {
              echo "その他";
           }
        ?>
    </td>
    </tr>
    <?php endforeach; ?>
</table>
<p style="margin:8px;">
<form action="" method="GET"> 

 

<div> 

    <p>現在 <?php echo $page; ?> ページ目です。</p> 

<?php 

   $stmt = $dbh->prepare("SELECT COUNT(*) id FROM user WHERE name like :name"); 

   $stmt->bindValue(':name', '%'.$name.'%', PDO::PARAM_STR); 

   $stmt->execute(); 

   $page_num = $stmt->fetchColumn(); 

   // ページネーションの数を取得する 

   $pagination = [総ページ数の計算処理] 

?> 

<?php  

   for ($x=1; $x <= $pagination ; $x++) { 

      if([表示ページの場合]){ 

      echo $x; 

      } else { 

          echo ' '; 

          echo '<a href=?page='. $x. '&name='. $name.'>'. $x. '</a>'; 

      echo ' '; 

  } 

   } 

?> 

</div> 

 

<div class="button-wrapper"> 

    <button type="button" onclick="history.back()">戻る</button> 

</div> 

</form> 
<hr>
<div class="container">
    <footer>
        <p>CCC.</p>
    </footer>
</div>

</body>
</html>

<?php
session_start();
require('../php/dbconnect.php');

$login = [];
$error = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $login['email'] = filter_input(INPUT_POST , 'email', FILTER_SANITIZE_EMAIL);
  if($login['email'] === ''){
    $error['email'] = 'blank';
  }

  $login['password'] = filter_input(INPUT_POST , 'password', FILTER_SANITIZE_STRING);
  if($login['password'] === ''){
    $error['password'] = 'blank';
  }
 

    //エラーチェック通過 
    if(empty($error)){
    $db = dbconnect();
    $stmt = $db->prepare('select id, name, password from members where email=? limit 1');
    if(!$stmt){
     die($db->error);
    }

    $stmt->bind_param('s', $login['email']);
    $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }

    $stmt->bind_result($id, $name, $hash);
    $stmt->fetch();
    
    if(password_verify($login['password'], $hash)){
    
    //ログイン成功
    session_regenerate_id();
    $_SESSION['id'] = $id ;
    $_SESSION['name'] = $name ;
    header('Location: ../php/community.php');
    exit();
    } else {
      $error['login'] = 'failed';
    }
  }
}

?>
<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="旅の情報を共有する掲示板サイト">
  <meta name="keywords" content="旅, 情報共有">
  <meta property="og:title" content="Trabel Board ~旅の掲示板~">
  <meta property="og:type" content="article">
  <meta property="og:description" content="旅の情報を共有する掲示板サイト">
  <meta property="og:site_name" content="プログラミング教材">
  <link rel="icon" type="image/png" href="../img/k0754_5.png" >
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/join&login.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Sawarabi+Mincho&display=swap"
    rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>
</head>

<body>
  <secssion class="join">
    <div class="join_text_wrappe">
     <h2 class="join_text">ユーザーログイン</h2>
     <p>ニックネーム、メールアドレス、パスワードをご記入しログインを行って下さい。</p>
    </div>
    
   <form action="" method="post">
     
    

    <div class="form_mail">
      <label for="mail">メールアドレス</label>
      <input type="text" id="email" name="email">
        <?php if(isset($error['email']) && $error['email'] === 'blank'):?>
        <p class="error">* メールアドレスを入力してください</p>
        <?php endif; ?>
    </div>

    <div class="form_password">
      <label for="password">パスワード</label>
      <input type="text" id="password" name="password">
      <?php if(isset($error['password']) && $error['password'] === 'blank'):?>
        <p class="error">* パスワードを入力してください</p>
      <?php endif; ?>
      <?php if(isset($error['login']) && $error['login'] === 'failed'):?>
      <p class="error">* ログインに失敗しました、もう一度正しくご記入して下さい。</p>
      <?php endif; ?>
    </div>

    <div class="but">
      <button class="but_inner login_but" type="submit" >ログイン</button>
    </div>
    <p class="lead_text" >ユーザー未登録な方は、こちらから</p>
    <div class="but">
      <button class="but_inner join_but" type="submit" ><a href="../php/join.php">ユーザー登録へ</a></button>
    </div>
   </form>

  </secssion>
</body>
</html>
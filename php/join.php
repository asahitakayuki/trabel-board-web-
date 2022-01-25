<?php
require('../php/dbconnect.php');

$join = [];
$error = [];
$join = [
  'name' => '',
  'email' => '',
  'password' => ''
];

//フォームの内容をチェック
if($_SERVER['REQUEST_METHOD'] === 'POST'){

 $join['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
 if($join['name'] === ''){
   $error['name'] = 'blank';
 }  else if (mb_strlen($join['name']) > 12){
   $error['name'] = 'name_length';
 }
 

 $join['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if($join['email'] === ''){
   $error['email'] = 'blank';
  } else {
   $db = dbconnect();
   $stmt = $db->prepare('select count(*) from members where email=?');
    if(!$stmt){
	   die($db->error);
	  }
   $stmt->bind_param('s', $join['email']);
   $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }
   $stmt->bind_result($cnt);
   $stmt->fetch();
        
    if ($cnt > 0){
    $error['email'] = 'duplicate';
    }
  }


 $join['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
 if($join['password'] === ''){
  $error['password'] = 'blank';
 } else if (strlen($join['password']) < 4){
   $error['password'] = 'pass_length';
  }

  


 //全てのエラーチェックを通過
  if(empty($error)){
   $db = dbconnect();
   $stmt = $db->prepare('insert into members (name, email, password) VALUES (?, ?, ?)');
    if(!$stmt){
	   die($db->error);
	  }

   $password = password_hash($join['password'], PASSWORD_DEFAULT);
   $stmt->bind_param('sss', $join['name'], $join['email'], $password);
    $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }

    header('Location: ../php/community.php');
    exit();
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
     <h2 class="join_text">ユーザー登録</h2>
     <p>ニックネーム、メールアドレス、パスワードをご記入しユーザー登録を行って下さい。</p>
    </div>
    
   <form action="" method="post">
     
    <div class="form_name">
      <label for="name">ニックネーム</label>
      <input type="text" id="name" name="name" value="<?php echo h($join['name']); ?>">

      <?php if (isset($error['name']) && $error['name'] === 'name_length'): ?>
        <p class="error">* ニックネームは12文字以内で入力してください</p>
      <?php else: ?>
        <p>ニックネームは12文字以内で入力してください</p>
      <?php endif; ?>  
      <?php if(isset($error['name']) && $error['name'] === 'blank'):?>
        <p class="error">* ニックネームを入力してください</p>
      <?php endif; ?>
      
    </div>

    <div class="form_mail">
      <label for="mail">メールアドレス</label>
      <input type="text" id="mail" name="email" value="<?php echo h($join['email']); ?>">
        <?php if(isset($error['email']) && $error['email'] === 'blank'):?>
        <p class="error">* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
        <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
        <?php endif; ?>
    </div>

    <div class="form_password">
      <label for="password">パスワード</label>
      <input type="" id="password" name="password" value="<?php echo h($join['password']); ?>">

      <?php if (isset($error['password']) && $error['password'] === 'pass_length'): ?>
        <p class="error">* パスワードは4文字以上で入力してください</p>
      <?php else :?>
        <p>パスワードは4文字以上で入力してください</p>
      <?php endif; ?>  
      <?php if(isset($error['password']) && $error['password'] === 'blank'):?>
        <p class="error">* パスワードを入力してください</p>
      <?php endif; ?>
      
    </div>

    <div class="but">
      <button class="but_inner login_but" type="submit" >ユーザー登録</button>
    </div>
    <p class="lead_text" >ログインは、こちらから</p>
    <div class="but">
      <button class="but_inner join_but" type="submit" ><a href="../php/login.php">ユーザーログインへ</a></button>
    </div>
   </form>

  </secssion>
</body>
</html>
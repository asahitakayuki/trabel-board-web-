<?php
session_start();
require('../php/dbconnect.php');

if(!isset($_SESSION['id']) && !isset($_SESSION['name'])){
   header('Location: ../php/login.php');
  exit();
}

$form = [];
$error = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $form['user_name'] = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    if($form['user_name'] === ''){
      $error['user_name'] = 'blank';
    }

    $form['user_mail'] = filter_input(INPUT_POST, 'user_mail', FILTER_SANITIZE_EMAIL);
    if ($form['user_mail'] === ''){
        $error['user_mail'] = 'blank';
    } else {
      $db = dbconnect();
      $stmt = $db->prepare('select email from members where email=?');
      if(!$stmt){
       die($db->error);
      }
    
      $stmt->bind_param('s', $form['user_mail']);
      $success = $stmt->execute();
      if(!$success){
       die($db->error);
      }
    
      $stmt->bind_result($email);
      $stmt->fetch();
    
      if (!$email){
        $error['user_mail'] = 'duplicate';
      }
    
    }

    $form['your_msg'] = filter_input(INPUT_POST, 'your_msg', FILTER_SANITIZE_STRING);
    if ($form['your_msg'] === ''){
        $error['your_msg'] = 'blank';
    }

    if(empty($error)){
     $_SESSION['form'] = $form;
    header('location: ../php/contact_sent.php');
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
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/contact.css">
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/footer.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&family=Sawarabi+Mincho&display=swap"
    rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>
</head>


<body>
  <!----------header--------->
<div class="contact_img img_ams">
  <header class="header_all">
    <nav class="header_nav">
      <ul class="header_ul">
        <li class="home_icon"><a href="../html/index.html"><img src="../img/outline_home_black_24dp.png" alt="ホームアイコン"></a></li>

        <div class="header_li">
          <li class="header_li2"><a href="../php/library.php">Library</a></li>
          <li class="header_li2"><a href="../php/community.php">Community</a></li>
          <li class="header_li2"><a href="../php/contact.php">Contact</a></li>
        </div>

      </ul>
    </nav>
    <h2 class="header_title">Contact</h2>
  </header>


  <!----------contact---------->
 <main>
    <section class="contact">
    <h3 class="contact_text">使用にあたっての質問や感想など、ご自由に書いてください。</h3>

    <form action="" method="post">

      <div class="form_name">
        <label for="name">Name<span>お名前</span><span class="required">必須</span></label>
        <input type="text" id="name" name="user_name">
        <?php if(isset($error['user_name']) && $error['user_name'] === 'blank'):?>
        <p class="error">* お名前を入力してください</p>
        <?php endif; ?>
      </div>

      <div class="form_mail">
        <label for="mail">Mail<span>メールアドレス</span><span class="required">必須</span></label>
        <input type="mail" id="mail" name="user_mail">
        <?php if(isset($error['user_mail']) && $error['user_mail'] === 'blank'):?>
         <p class="error">* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if(isset($error['user_mail']) && $error['user_mail'] === 'duplicate'):?>
         <p class="error">* メールアドレスが誤っています。</p>
        <?php endif; ?>
      </div>

      <div class="form_text">
        <label for="msg">Message<span>メッセージ</span><span class="required">必須</span></label>
        <textarea id="msg" name="your_msg"></textarea>
        <?php if(isset($error['your_msg']) && $error['your_msg'] === 'blank'):?>
        <p class="error">* メッセージを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="but">
        <button type="submit">確認画面へ</button>
      </div>
      
    </form>
    </section>
  </main>
</div><!-----big_img----->


<!----------footer---------->
  <section class="footer">
    <p>©2021.○○.○○ Asahi Takayuki All Rights Reserved</p>
  </section>

</body>
</html>
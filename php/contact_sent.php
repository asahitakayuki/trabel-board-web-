<?php
session_start();
require('../php/dbconnect.php');

//入力画面からアクセスしてなければ戻す
if (!isset($_SESSION['form'])){
   header('Location:../php/contact.php');
   exit();
} else{
   $form = $_SESSION['form'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
 $db = dbconnect();
 $stmt = $db->prepare('insert into contact (name, email, text) VALUES (?, ?, ?)');
	if (!$stmt){
		die($db->error);
	}
 $stmt->bind_param('sss', $form['user_name'],  $form['user_mail'], $form['your_msg']);
 $success = $stmt->execute();
 if(!$success){
   die($db->error);
 }

  unset($_SESSION['form']);
	header('Location: ../html/thank.html');
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

      <div class="text">
        <label for="name">Name<span>お名前</span></label>
        <p><?php echo $form['user_name']; ?></p>
      </div>

      <div class="text">
        <label for="mail">Mail<span>メールアドレス</span></label>
        <p><?php echo $form['user_mail']; ?></p>
      </div>

      <div class="text">
        <label for="msg">Message<span>メッセージ</span></label>
        <p><?php echo $form['your_msg']; ?></p>
      </div>
      
      <div class="but_wrappe">
        <div class="but_inner">
         <button type="submit"><a href="../php/contact.php">戻る</a>
        </div>
        
        <div class="sent_but">
         <button class="sent_but_inner" type="submit" >送信する</button>
        </div>
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
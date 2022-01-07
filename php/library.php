<?php
session_start();
require('../php/dbconnect.php');

if(!isset($_SESSION['id']) && !isset($_SESSION['name'])){
   header('Location: ../php/login.php');
  exit();
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
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/library.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>
</head>


<body>
  <!----------header--------->
  <header class="header_all library_img img_conf img_ams">
    <nav class="header_nav">
      <ul class="header_ul">
        <li class="home_icon"><a href="../html/index.html"><img src="../img/outline_home_black_24dp.png" alt="ホームアイコン"></a>
        </li>
  
        <div class="header_li">
          <li class="header_li2"><a href="../php/library.php">Library</a></li>
          <li class="header_li2"><a href="../php/community.php">Community</a></li>
          <li class="header_li2"><a href="../php/contact.php">Contact</a></li>
        </div>
  
      </ul>
    </nav>
    <h2 class="header_title">Library</h2>
  </header>
<main>
  <session class="library">
    <div class="wrapper">
     <h2 class="name">
       <?php if (!empty($_POST['nickname'])):?>
       <p><?php echo htmlspecialchars ($_POST['nickname'], ENT_QUOTES);?></p>
       <?php else: ?>
       <?php echo '名無し'; ?>
       <?php endif; ?>
      </h2>
      <p class="edit"><a href="../php/library_edit.php">プロフィール編集</a></p>
    </div>
    
    <div class="inner">
     <p>投稿数<?php echo '○○'; ?></p>
     <p>いいね数<?php echo '○○'; ?></p>
     <p><a href="">いいねした投稿を見る</a></p>
     <div class="but">
       <button type="submit"><a href="../php/post.php">投稿する</a></button>
      </div>
    </div>



  </session>
</main>
</body>
</html>